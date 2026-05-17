<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->latest()->take(24)->get();
        $products->each->append(['thumbnail_url', 'effective_price']);
        
        $categories = \App\Models\Category::all();
        $settings = Setting::first();
        return view('admin.pos.index', compact('products', 'categories', 'settings'));
    }

    public function searchProducts(Request $request)
    {
        $query = Product::where('is_active', true);

        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->take(48)->get();
        $products->each->append(['thumbnail_url', 'effective_price']);

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_charge' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                // Generate Order ID
                $orderId = 'POS-' . date('ymd') . '-' . strtoupper(Str::random(4));

                $subtotal = 0;
                foreach ($request->items as $item) {
                    $product = Product::find($item['id']);
                    $price = $product->sale_price > 0 ? $product->sale_price : $product->price;
                    $subtotal += $price * $item['quantity'];
                }

                $total = ($subtotal + $request->shipping_charge) - ($request->discount ?? 0);

                // Create Order
                $order = Order::create([
                    'order_number' => $orderId,
                    'user_id' => null, // POS walk-in
                    'name' => $request->customer_name,
                    'email' => 'pos@Bazario.com',
                    'phone' => $request->customer_phone,
                    'address' => $request->customer_address ?? 'POS Customer',
                    'city' => 'POS',
                    'subtotal' => $subtotal,
                    'shipping' => $request->shipping_charge,
                    'discount' => $request->discount ?? 0,
                    'total' => $total,
                    'payment_method' => 'cash',
                    'payment_status' => 'paid',
                    'status' => 'delivered',
                    'notes' => 'Created via POS System',
                ]);

                // Create Order Items and Update Stock
                foreach ($request->items as $item) {
                    $product = Product::find($item['id']);
                    $price = $product->sale_price > 0 ? $product->sale_price : $product->price;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $item['quantity'],
                        'price' => $price,
                        'buying_price' => $product->buying_price,
                        'total' => $price * $item['quantity'],
                    ]);

                    // Update Stock if managed
                    if ($product->stock >= $item['quantity']) {
                        $product->decrement('stock', $item['quantity']);
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'POS Order Created Successfully!',
                    'order_id' => $order->order_id
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
