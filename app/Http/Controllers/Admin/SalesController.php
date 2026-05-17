<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        // Query orders within date range
        $query = Order::whereBetween('created_at', [$startDate, $endDate]);

        // Overall Stats
        $deliveredOrders = (clone $query)->where('status', 'delivered')->with('items')->get();
        $returnedOrders = (clone $query)->where('status', 'returned')->get();

        $deliveredRevenue = $deliveredOrders->sum('subtotal');
        $deliveredCogs = $deliveredOrders->sum(function($order) {
            return $order->items->sum(function($item) {
                return ($item->buying_price ?? 0) * $item->quantity;
            });
        });
        $returnedShippingLoss = $returnedOrders->sum('shipping');
        
        $netProfit = $deliveredRevenue - $deliveredCogs - $returnedShippingLoss;

        $stats = [
            'total_sales' => (clone $query)->whereIn('status', ['pending', 'confirmed', 'shipped', 'delivered'])->sum('total'),
            'total_orders' => (clone $query)->count(),
            'pending_count' => (clone $query)->where('status', 'pending')->count(),
            'pending_value' => (clone $query)->where('status', 'pending')->sum('total'),
            'confirmed_count' => (clone $query)->where('status', 'confirmed')->count(),
            'confirmed_value' => (clone $query)->where('status', 'confirmed')->sum('total'),
            'shipped_count' => (clone $query)->where('status', 'shipped')->count(),
            'shipped_value' => (clone $query)->where('status', 'shipped')->sum('total'),
            'delivered_count' => (clone $query)->where('status', 'delivered')->count(),
            'delivered_value' => (clone $query)->where('status', 'delivered')->sum('total'),
            'returned_count' => (clone $query)->where('status', 'returned')->count(),
            'returned_value' => (clone $query)->where('status', 'returned')->sum('total'),
            'cancelled_count' => (clone $query)->where('status', 'cancelled')->count(),
            'cancelled_value' => (clone $query)->where('status', 'cancelled')->sum('total'),
            'incomplete_count' => (clone $query)->where('status', 'incomplete')->count(),
            'incomplete_value' => (clone $query)->where('status', 'incomplete')->sum('total'),
            'net_profit' => $netProfit,
        ];

        // Daily Sales Chart Data
        $dailySales = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['pending', 'confirmed', 'shipped', 'delivered'])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as daily_total'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Get Recent Orders in this range
        $orders = (clone $query)->latest()->paginate(20)->withQueryString();

        return view('admin.sales.index', compact('stats', 'dailySales', 'orders', 'startDate', 'endDate'));
    }
}
