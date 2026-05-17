<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Services\FacebookAdsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'total_sales' => Order::where('status', '!=', 'cancelled')->sum('total'),
            'total_products' => Product::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
            'new_orders_today' => Order::whereDate('created_at', today())->count(),
        ];

        $recentOrders = Order::latest()->take(10)->get();
        $topProducts = Product::withCount('reviews')->orderBy('review_count', 'desc')->take(5)->get();
        $lowStock = Product::where('stock', '<=', 10)->where('is_active', true)->take(8)->get();

        // Category Sales - Final attempt with zero-sales visibility
        $categorySales = Category::all()->map(function($cat) {
            $totalSales = \App\Models\OrderItem::whereHas('product', function($q) use ($cat) {
                    $q->where('category_id', $cat->id);
                })
                ->whereHas('order', function($q) {
                    $q->where('status', '!=', 'cancelled');
                })
                ->get()
                ->sum(function($item) {
                    return $item->price * $item->quantity;
                });

            return [
                'name' => $cat->name,
                'sales' => (float)$totalSales
            ];
        })->sortByDesc('sales')->values();

        // If even this is empty, let's show categories that have products at least
        if ($categorySales->where('sales', '>', 0)->isEmpty()) {
            // Keep the categories but they will have 0 sales
        }

        // Revenue by month (last 6 months)
        $revenueData = collect(range(5, 0))->map(function($i) {
            $month = now()->subMonths($i);
            return [
                'month' => $month->format('M'),
                'revenue' => Order::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->where('status', '!=', 'cancelled')
                    ->sum('total')
            ];
        });

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'lowStock', 'revenueData', 'categorySales'));
    }

    public function ping()
    {
        $latestOrder = Order::latest()->first();
        
        return response()->json([
            'latest_order_id' => $latestOrder ? $latestOrder->id : 0,
            'pending_orders' => Order::where('status', 'pending')->count(),
            'live_visitors' => \App\Models\Visitor::where('last_active_at', '>=', now()->subMinutes(2))->count(),
            'stats' => [
                'total_orders' => Order::count(),
                'total_sales' => (float)Order::where('status', '!=', 'cancelled')->sum('total'),
                'total_products' => Product::count(),
                'total_customers' => User::where('role', 'customer')->count(),
                'delivered_orders' => Order::where('status', 'delivered')->count(),
            ]
        ]);
    }
}
