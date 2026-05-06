@extends('layouts.admin')
@section('title', 'Sales Report')
@section('content')

<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-2xl font-black text-gray-900 dark:text-white">Sales Analysis</h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Detailed breakdown of your store performance.</p>
    </div>
    
    <!-- Date Filter Form -->
    <form action="{{ route('admin.sales.index') }}" method="GET" class="flex flex-wrap items-end gap-3 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Start Date</label>
            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" onchange="this.form.submit()"
                class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-orange-500 focus:border-orange-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all cursor-pointer">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">End Date</label>
            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" onchange="this.form.submit()"
                class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-orange-500 focus:border-orange-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all cursor-pointer">
        </div>
        @if(request()->has('start_date') || request()->has('end_date'))
            <a href="{{ route('admin.sales.index') }}" class="bg-red-50 hover:bg-red-100 border border-red-100 text-red-500 font-bold py-2 px-4 rounded-xl text-sm transition dark:bg-red-900/20 dark:text-red-400 flex items-center gap-2 mb-0.5">
                <i data-lucide="x" class="w-3.5 h-3.5"></i> Clear
            </a>
        @endif
    </form>
</div>

<!-- Primary Stats Overview -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center gap-4 mb-2">
            <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center dark:bg-green-900/20 dark:text-green-400">
                <i data-lucide="banknote" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-bold text-gray-400 uppercase">Total Sales Revenue</span>
        </div>
        <p class="text-2xl font-black text-gray-900 dark:text-white">Tk{{ number_format($stats['total_sales']) }}</p>
        <p class="text-xs text-gray-500 mt-1">Excluding cancelled orders</p>
    </div>
    
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center gap-4 mb-2">
            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center dark:bg-blue-900/20 dark:text-blue-400">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-bold text-gray-400 uppercase">Total Orders</span>
        </div>
        <p class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($stats['total_orders']) }}</p>
        <p class="text-xs text-gray-500 mt-1">In selected range</p>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700 border-l-4 border-l-orange-500">
        <div class="flex items-center gap-4 mb-2">
            <div class="w-10 h-10 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center dark:bg-orange-900/20 dark:text-orange-400">
                <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-bold text-gray-400 uppercase">Pending Orders</span>
        </div>
        <div class="flex justify-between items-end">
            <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $stats['pending_count'] }}</p>
            <p class="text-sm font-bold text-orange-600">Tk{{ number_format($stats['pending_value']) }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700 border-l-4 border-l-green-500">
        <div class="flex items-center gap-4 mb-2">
            <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center dark:bg-green-900/20 dark:text-green-400">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-bold text-gray-400 uppercase">Delivered Orders</span>
        </div>
        <div class="flex justify-between items-end">
            <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $stats['delivered_count'] }}</p>
            <p class="text-sm font-bold text-green-600">Tk{{ number_format($stats['delivered_value']) }}</p>
        </div>
    </div>
</div>

<!-- Secondary Status Row (Confirmed, Shipped, Incomplete, Cancelled) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Confirmed -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700 flex items-center justify-between border-l-4 border-l-cyan-500">
        <div class="flex items-center gap-3">
             <div class="w-10 h-10 rounded-full bg-cyan-50 text-cyan-600 flex items-center justify-center dark:bg-cyan-900/20 dark:text-cyan-400">
                <i data-lucide="thumbs-up" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase">Confirmed Orders</p>
                <p class="text-lg font-black text-gray-900 dark:text-white">{{ $stats['confirmed_count'] }}</p>
            </div>
        </div>
        <p class="text-md font-bold text-cyan-600">Tk{{ number_format($stats['confirmed_value']) }}</p>
    </div>

    <!-- Shipped -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700 flex items-center justify-between border-l-4 border-l-indigo-500">
        <div class="flex items-center gap-3">
             <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center dark:bg-indigo-900/20 dark:text-indigo-400">
                <i data-lucide="truck" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase">Shipped Orders</p>
                <p class="text-lg font-black text-gray-900 dark:text-white">{{ $stats['shipped_count'] }}</p>
            </div>
        </div>
        <p class="text-md font-bold text-indigo-600">Tk{{ number_format($stats['shipped_value']) }}</p>
    </div>

    <!-- Incomplete -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700 flex items-center justify-between border-l-4 border-l-gray-400">
        <div class="flex items-center gap-3">
             <div class="w-10 h-10 rounded-full bg-gray-50 text-gray-600 flex items-center justify-center dark:bg-gray-900/20 dark:text-gray-400">
                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase">Incomplete</p>
                <p class="text-lg font-black text-gray-900 dark:text-white">{{ $stats['incomplete_count'] }}</p>
            </div>
        </div>
        <p class="text-md font-bold text-gray-400">Tk{{ number_format($stats['incomplete_value']) }}</p>
    </div>

    <!-- Cancelled -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700 flex items-center justify-between border-l-4 border-l-red-500">
        <div class="flex items-center gap-3">
             <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center dark:bg-red-900/20 dark:text-red-400">
                <i data-lucide="x-circle" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase">Cancelled Orders</p>
                <p class="text-lg font-black text-gray-900 dark:text-white">{{ $stats['cancelled_count'] }}</p>
            </div>
        </div>
        <p class="text-md font-bold text-red-600">Tk{{ number_format($stats['cancelled_value']) }}</p>
    </div>
</div>

<!-- Daily Sales Chart -->
<div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mb-8 dark:bg-gray-800 dark:border-gray-700">
    <h3 class="font-bold text-gray-900 mb-6 text-lg dark:text-white flex items-center gap-2">
        <i data-lucide="line-chart" class="w-5 h-5 text-[#FF6A00]"></i> Daily Sales Performance
    </h3>
    <div class="relative h-[350px] w-full">
        <canvas id="salesChart"></canvas>
    </div>
</div>

<!-- Detailed Orders Table -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center dark:border-gray-700">
        <h3 class="font-bold text-gray-900 text-lg dark:text-white">Orders List ({{ $startDate->format('M d') }} - {{ $endDate->format('M d') }})</h3>
        <span class="text-xs bg-gray-100 px-2 py-1 rounded-lg text-gray-500 dark:bg-gray-700">{{ $orders->total() }} Orders</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-900/50 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-4 font-semibold">Order #</th>
                    <th class="px-6 py-4 font-semibold">Customer</th>
                    <th class="px-6 py-4 font-semibold">Date</th>
                    <th class="px-6 py-4 font-semibold text-center">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition dark:hover:bg-gray-700/50">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                        <a href="{{ route('admin.orders.show', $order) }}" class="hover:text-[#FF6A00]">{{ $order->order_number }}</a>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $order->name }}</div>
                        <div class="text-xs text-gray-400 dark:text-gray-500">{{ $order->phone }}</div>
                    </td>
                    <td class="px-6 py-4 dark:text-gray-300 text-xs">{{ $order->created_at->format('d M, Y h:i A') }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $order->status_badge }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">Tk{{ number_format($order->total) }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">No orders found for this selection.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/20">
        {{ $orders->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesData = @json($dailySales);
        const isDark = document.documentElement.classList.contains('dark');
        
        // Prepare Labels and Data
        const labels = salesData.map(d => {
            const date = new Date(d.date);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        const revenueValues = salesData.map(d => d.daily_total);
        const orderCounts = salesData.map(d => d.order_count);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Sales Revenue (Tk)',
                        data: revenueValues,
                        backgroundColor: '#FF6A00',
                        borderRadius: 6,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Order Count',
                        data: orderCounts,
                        type: 'line',
                        borderColor: '#002f4b',
                        borderWidth: 2,
                        pointBackgroundColor: '#002f4b',
                        tension: 0.4,
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: isDark ? '#9ca3af' : '#6b7280',
                            usePointStyle: true,
                            font: { size: 11, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        padding: 12,
                        backgroundColor: isDark ? '#1f2937' : '#ffffff',
                        titleColor: isDark ? '#ffffff' : '#1f2937',
                        bodyColor: isDark ? '#d1d5db' : '#4b5563',
                        borderColor: isDark ? '#374151' : '#e5e7eb',
                        borderWidth: 1,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        grid: { borderDash: [4, 4], color: isDark ? '#374151' : '#f3f4f6' },
                        ticks: { 
                            color: isDark ? '#9ca3af' : '#6b7280',
                            callback: value => '৳' + value.toLocaleString()
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: { display: false },
                        ticks: { color: '#002f4b' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: isDark ? '#9ca3af' : '#6b7280' }
                    }
                }
            }
        });
    });
</script>
@endpush
