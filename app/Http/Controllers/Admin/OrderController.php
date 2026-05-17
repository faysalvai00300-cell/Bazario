<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query()->with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('product_id')) {
            $query->whereHas('items', function($q) use ($request) {
                $q->where('product_id', $request->product_id);
            });
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', "%{$request->search}%")
                  ->orWhere('name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%")
                  ->orWhere('transaction_id', 'like', "%{$request->search}%")
                  ->orWhereHas('items.product', function($pq) use ($request) {
                      $pq->where('sku', 'like', "%{$request->search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(20)->withQueryString();
        $products = \App\Models\Product::orderBy('name')->get(['id', 'name']);
        
        return view('admin.orders.index', compact('orders', 'products'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, \App\Models\Order $order, \App\Services\SmsService $smsService, \App\Services\FacebookCapiService $fbCapi)
    {
        // Status Lockdown Logic
        if ($order->status === 'delivered') {
            $request->validate(['status' => 'required|in:delivered,returned,cancelled']);
            
            if ($order->status !== $request->status) {
                $order->update(['status' => $request->status]);
                return redirect()->route('admin.orders.index')->with('success', 'Order status updated from Delivered!');
            }
            
            return redirect()->route('admin.orders.index')->with('error', 'This order is already delivered and locked.');
        }

        $request->validate(['status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,returned']);
        
        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Send confirmation SMS if status changed TO confirmed
        if ($order->status === 'confirmed' && $oldStatus !== 'confirmed') {
            try {
                $smsService->sendOrderConfirmation($order);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Order Confirmation SMS Failed: " . $e->getMessage());
            }
        }

        // Send Facebook CAPI Purchase event if status changed TO delivered
        if ($order->status === 'delivered' && $oldStatus !== 'delivered') {
            try {
                $fbCapi->sendPurchaseEvent($order);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Facebook CAPI Delivered Event Failed: " . $e->getMessage());
            }
        }

        return redirect()->route('admin.orders.index')->with('success', 'Order status updated!');
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate(['payment_status' => 'required|in:pending,paid,failed']);
        $order->update(['payment_status' => $request->payment_status]);
        return back()->with('success', 'Payment status updated!');
    }

    public function create() { }
    public function store(Request $request) { }
    public function edit(Order $order) { }
    public function destroy(Order $order)
    {
        $order->delete();
        return back()->with('success', 'Order deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }
        
        if (!$ids || !is_array($ids)) {
            return back()->with('error', 'No orders selected!');
        }
        Order::whereIn('id', $ids)->delete();
        return back()->with('success', count($ids) . ' orders deleted successfully!');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $ids = $request->ids;
        $status = $request->status;
        
        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }

        if (!$ids || !is_array($ids)) {
            return back()->with('error', 'No orders selected!');
        }
        
        if (!$status) {
            return back()->with('error', 'No status selected!');
        }

        Order::whereIn('id', $ids)->update(['status' => $status]);
        return back()->with('success', count($ids) . ' orders updated to ' . $status);
    }

    public function updateNotes(Order $order, Request $request)
    {
        $request->validate([
            'notes' => 'nullable|string',
            'admin_note' => 'nullable|string',
        ]);

        $order->update($request->only(['notes', 'admin_note']));
        return back()->with('success', 'Notes updated successfully!');
    }

    public function sendToCourier(Order $order, Request $request, \App\Services\CourierService $courierService)
    {
        $result = $courierService->sendOrder($order, $request->courier_type);
        
        if ($result['success']) {
            return back()->with('success', $result['message']);
        }
        
        return back()->with('error', $result['message']);
    }

    public function bulkSendToCourier(Request $request, \App\Services\CourierService $courierService)
    {
        $ids = $request->ids;
        $courierType = $request->courier_type;
        $areaData = $request->except(['ids', 'courier_type', '_token']);

        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }

        if (!$ids || !is_array($ids)) {
            return back()->with('error', 'No orders selected!');
        }

        $successCount = 0;
        $errors = [];

        foreach ($ids as $id) {
            $order = Order::find($id);
            if ($order) {
                // Pass area data for specific couriers
                if ($courierType === 'redx') {
                    $result = $courierService->sendOrder($order, $courierType, $areaData['redx_area_id'] ?? null);
                } elseif ($courierType === 'pathao') {
                    $result = $courierService->sendOrder($order, $courierType, $areaData);
                } else {
                    $result = $courierService->sendOrder($order, $courierType);
                }

                if ($result['success']) {
                    $successCount++;
                } else {
                    $errors[] = "Order #{$order->order_number}: {$result['message']}";
                }
            }
        }

        if ($successCount > 0 && count($errors) > 0) {
            return back()->with('success', "{$successCount} orders sent. Some failed: " . implode(', ', $errors));
        } elseif ($successCount > 0) {
            return back()->with('success', "{$successCount} orders sent successfully to " . ucfirst($courierType));
        } else {
            return back()->with('error', "Failed to send orders: " . implode(', ', $errors));
        }
    }

    public function getCourierLocations(Request $request, \App\Services\CourierService $courierService)
    {
        $type = $request->type;
        if ($type === 'pathao_cities') return response()->json($courierService->getPathaoCities());
        if ($type === 'pathao_zones') return response()->json($courierService->getPathaoZones($request->city_id));
        if ($type === 'pathao_areas') return response()->json($courierService->getPathaoAreas($request->zone_id));
        if ($type === 'redx_areas') return response()->json($courierService->getRedXAreas());
        
        return response()->json([]);
    }

    public function bulkPrintInvoices(Request $request)
    {
        $ids = explode(',', $request->ids);
        $orders = Order::whereIn('id', $ids)->with('items.product')->get();
        $settings = \App\Models\Setting::first();
        return view('admin.orders.invoices', compact('orders', 'settings'));
    }

    public function updateItem(\App\Models\OrderItem $item, Request $request)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        
        $item->update([
            'quantity' => $request->quantity,
            'total' => $item->price * $request->quantity
        ]);

        $this->recalculateOrder($item->order);

        return back()->with('success', 'Item quantity updated!');
    }

    public function removeItem(\App\Models\OrderItem $item)
    {
        $order = $item->order;
        $item->delete();

        $this->recalculateOrder($order);

        if ($order->items()->count() === 0) {
            $order->update(['status' => 'cancelled']);
            return redirect()->route('admin.orders.index')->with('success', 'Order cancelled because it has no items.');
        }

        return back()->with('success', 'Item removed from order!');
    }

    public function fraudCheck(Order $order, \App\Services\CourierService $courierService)
    {
        $result = $courierService->checkFraud($order->phone);
        return response()->json($result);
    }

    private function recalculateOrder(Order $order)
    {
        $subtotal = $order->items()->sum('total');
        $total = $subtotal + $order->shipping - $order->discount;
        
        $order->update([
            'subtotal' => $subtotal,
            'total' => max(0, $total)
        ]);
    }
}
