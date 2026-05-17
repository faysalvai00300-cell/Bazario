@extends('layouts.admin')
@section('title', 'Sales Report')
@section('content')

<div class="page-header">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-[#FF6A00] rounded-2xl flex items-center justify-center text-white shadow-lg shadow-orange-200">
            <i data-lucide="bar-chart-3" class="w-6 h-6"></i>
        </div>
        <div>
            <h2 class="text-xl font-black text-gray-900 leading-tight">Sales Analysis</h2>
            <p class="text-gray-400 text-[11px] font-bold uppercase tracking-wider">Detailed breakdown of your store performance</p>
        </div>
    </div>
    
    <!-- Premium Date Filter (Light Theme) -->
    <form action="{{ route('admin.sales.index') }}" method="GET">
        <div style="background:#f9fafb; border-radius:12px; padding:10px 16px; display:flex; align-items:center; gap:12px; border:1px solid #f0f0f0;">
            <!-- Start Date -->
            <div>
                <div style="font-size:9px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:4px;">Start Date</div>
                <div style="display:flex; align-items:center; background:#ffffff; border-radius:8px; padding:6px 10px; gap:6px; border:1.5px solid #e5e7eb;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#FF6A00" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}"
                        onchange="this.form.submit()"
                        style="background:transparent; border:none; outline:none; color:#111827; font-size:12px; font-weight:700; cursor:pointer; min-width:110px;">
                </div>
            </div>
            
            <!-- Arrow -->
            <div style="color:#FF6A00; margin-top:14px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </div>

            <!-- End Date -->
            <div>
                <div style="font-size:9px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:4px;">End Date</div>
                <div style="display:flex; align-items:center; background:#ffffff; border-radius:8px; padding:6px 10px; gap:6px; border:1.5px solid #e5e7eb;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#FF6A00" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                        onchange="this.form.submit()"
                        style="background:transparent; border:none; outline:none; color:#111827; font-size:12px; font-weight:700; cursor:pointer; min-width:110px;">
                </div>
            </div>

            @if(request()->has('start_date') || request()->has('end_date'))
            <!-- Clear Button -->
            <a href="{{ route('admin.sales.index') }}" style="margin-top:16px; display:flex; align-items:center; justify-content:center; width:32px; height:32px; background:#fee2e2; border-radius:8px; color:#ef4444;" title="Clear">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </a>
            @endif
        </div>
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

<!-- Secondary Status Row (Confirmed, Shipped, Returned, Net Profit) -->
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

    <!-- Returned -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700 flex items-center justify-between border-l-4 border-l-purple-500">
        <div class="flex items-center gap-3">
             <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center dark:bg-purple-900/20 dark:text-purple-400">
                <i data-lucide="refresh-cw" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase">Returned Orders</p>
                <p class="text-lg font-black text-gray-900 dark:text-white">{{ $stats['returned_count'] }}</p>
            </div>
        </div>
        <p class="text-md font-bold text-purple-600">Tk{{ number_format($stats['returned_value']) }}</p>
    </div>

    <!-- Net Profit -->
    <div class="bg-gradient-to-br from-gray-900 to-black p-5 rounded-2xl border border-gray-800 shadow-xl flex items-center justify-between border-l-4 border-l-[#FF6A00]">
        <div class="flex items-center gap-3">
             <div class="w-10 h-10 rounded-full bg-orange-500/10 text-[#FF6A00] flex items-center justify-center">
                <i data-lucide="trending-up" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-orange-400 uppercase tracking-widest">Net Profit</p>
                <p class="text-xl font-black text-white">Tk{{ number_format($stats['net_profit']) }}</p>
            </div>
        </div>
        <div class="text-right">
            <span class="text-[9px] text-gray-500 font-bold block uppercase">Delivered Only</span>
            <i data-lucide="shield-check" class="w-4 h-4 text-[#FF6A00] ml-auto mt-1"></i>
        </div>
    </div>
</div>

<!-- Tertiary Row (Incomplete, Cancelled) -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
    <!-- Incomplete -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700 flex items-center justify-between border-l-4 border-l-gray-400">
        <div class="flex items-center gap-3">
             <div class="w-10 h-10 rounded-full bg-gray-50 text-gray-600 flex items-center justify-center dark:bg-gray-900/20 dark:text-gray-400">
                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase">Incomplete / Draft</p>
                <p class="text-md font-black text-gray-900 dark:text-white">{{ $stats['incomplete_count'] }}</p>
            </div>
        </div>
        <p class="text-sm font-bold text-gray-400">Tk{{ number_format($stats['incomplete_value']) }}</p>
    </div>

    <!-- Cancelled -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700 flex items-center justify-between border-l-4 border-l-red-500">
        <div class="flex items-center gap-3">
             <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center dark:bg-red-900/20 dark:text-red-400">
                <i data-lucide="x-circle" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase">Cancelled Orders</p>
                <p class="text-md font-black text-gray-900 dark:text-white">{{ $stats['cancelled_count'] }}</p>
            </div>
        </div>
        <p class="text-sm font-bold text-red-600">Tk{{ number_format($stats['cancelled_value']) }}</p>
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

<!-- Detailed Orders Cards -->
<div class="space-y-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-bold text-gray-900 text-lg dark:text-white">Recent Order Breakdown</h3>
        <span class="text-xs bg-gray-100 px-3 py-1.5 rounded-xl text-gray-500 font-bold dark:bg-gray-700 tracking-tight">{{ $orders->total() }} Total Orders</span>
    </div>

    <div class="grid grid-cols-1 gap-6">
        @forelse($orders as $order)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700 hover:shadow-md transition-shadow">
            <!-- Order Header -->
            <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700 flex flex-wrap justify-between items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="text-sm font-black text-gray-900 dark:text-white">
                        <a href="{{ route('admin.orders.show', $order) }}" class="hover:text-[#FF6A00] flex items-center gap-2">
                            <span class="text-gray-400">#</span>{{ $order->order_number }}
                        </a>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $order->status_badge }}">
                        {{ $order->status }}
                    </span>
                </div>
                <div class="flex items-center gap-6 text-[11px] font-bold text-gray-500">
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="user" class="w-3.5 h-3.5 text-gray-400"></i>
                        {{ $order->name }}
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-gray-400"></i>
                        {{ $order->created_at->format('d M, Y h:i A') }}
                    </div>
                </div>
            </div>

            <!-- Order Content (Items) -->
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center justify-between gap-4 p-3 bg-gray-50/30 dark:bg-gray-900/20 rounded-xl border border-gray-50 dark:border-gray-800">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-gray-100 overflow-hidden shrink-0 border border-gray-200 dark:border-gray-700">
                                <img src="{{ $item->product_image }}" class="w-full h-full object-cover" alt="Product">
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-gray-900 dark:text-white leading-tight mb-1">{{ $item->product_name }}</h4>
                                <p class="text-[10px] font-bold text-gray-400 uppercase">
                                    Qty: <span class="text-gray-900 dark:text-gray-300">{{ $item->quantity }}</span> 
                                    @if($item->size) <span class="mx-1">|</span> Size: <span class="text-gray-900 dark:text-gray-300">{{ $item->size }}</span> @endif
                                    @if($item->color) <span class="mx-1">|</span> Color: <span class="text-gray-900 dark:text-gray-300">{{ $item->color }}</span> @endif
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-0.5">Unit Price</p>
                            <p class="text-xs font-black text-gray-900 dark:text-white">Tk{{ number_format($item->price) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Footer (Totals) -->
            <div class="px-6 py-4 bg-gray-50/20 dark:bg-gray-900/10 border-t border-gray-100 dark:border-gray-700 flex flex-wrap justify-between items-center">
                <div class="flex gap-6">
                    <div>
                        <span class="text-[9px] font-black text-gray-400 uppercase block mb-0.5">Subtotal</span>
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Tk{{ number_format($order->subtotal) }}</span>
                    </div>
                    <div>
                        <span class="text-[9px] font-black text-gray-400 uppercase block mb-0.5">Shipping</span>
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">+ Tk{{ number_format($order->shipping) }}</span>
                    </div>
                    @if($order->discount > 0)
                    <div>
                        <span class="text-[9px] font-black text-gray-400 uppercase block mb-0.5">Discount</span>
                        <span class="text-xs font-bold text-red-500">- Tk{{ number_format($order->discount) }}</span>
                    </div>
                    @endif
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black text-gray-400 uppercase block mb-0.5">Final Amount</span>
                    <span class="text-lg font-black text-[#FF6A00]">Tk{{ number_format($order->total) }}</span>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white p-12 rounded-2xl border border-dashed border-gray-200 text-center dark:bg-gray-800 dark:border-gray-700">
            <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
            <p class="text-gray-500 font-bold">No orders found for this selection.</p>
        </div>
        @endforelse
    </div>

    @if($orders->hasPages())
    <div class="pt-6">
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
