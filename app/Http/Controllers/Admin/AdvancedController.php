<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;

class AdvancedController extends Controller
{
    public function index()
    {
        // Visitor Stats
        $visitorStats = [
            'today' => Visitor::whereDate('visit_date', today())->count(),
            'last_7_days' => Visitor::where('visit_date', '>=', now()->subDays(7))->count(),
            'last_30_days' => Visitor::where('visit_date', '>=', now()->subDays(30))->count(),
            'last_year' => Visitor::where('visit_date', '>=', now()->subYear())->count(),
            'total' => Visitor::count(),
            'live' => Visitor::where('last_active_at', '>=', now()->subMinutes(2))->count(),
        ];

        // Sales & Order Stats (Real data)
        $salesStats = [
            'orders_today' => \App\Models\Order::whereDate('created_at', today())->count(),
            'sales_today' => \App\Models\Order::whereDate('created_at', today())->where('status', '!=', 'cancelled')->sum('total'),
            'orders_weekly' => \App\Models\Order::where('created_at', '>=', now()->subDays(7))->count(),
            'sales_weekly' => \App\Models\Order::where('created_at', '>=', now()->subDays(7))->where('status', '!=', 'cancelled')->sum('total'),
            'total_orders' => \App\Models\Order::count(),
            'total_sales' => \App\Models\Order::where('status', '!=', 'cancelled')->sum('total'),
        ];

        // Visitor Chart Data (last 30 days)
        $visitorChartData = collect(range(29, 0))->map(function($i) {
            $date = now()->subDays($i);
            return [
                'day' => $date->format('d M'),
                'count' => Visitor::whereDate('visit_date', $date->toDateString())->count()
            ];
        });

        return view('admin.advanced.index', compact('visitorStats', 'visitorChartData', 'salesStats'));
    }
}
