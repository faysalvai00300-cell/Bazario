@extends('layouts.admin')
@section('title', 'Advanced Statistics & Analytics')
@section('content')

<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('Advanced Analytics') }}</h2>
        <p class="text-gray-500 text-sm">{{ __('Real-time visitor tracking and platform performance') }}</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2 px-4 py-2 bg-green-50 text-green-600 rounded-2xl text-sm font-bold animate-pulse dark:bg-green-900/20 dark:text-green-400">
            <span class="w-3 h-3 bg-green-500 rounded-full"></span>
            <span id="live-visitor-count">{{ number_format($visitorStats['live']) }}</span> {{ __('Visitors Online') }}
        </div>
    </div>
</div>

<!-- Analytics Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
    <!-- Visitors Card -->
    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg transition-all duration-300 group relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-orange-500/5 -mr-12 -mt-12 rounded-full"></div>
        <div class="flex items-center justify-between mb-6 relative">
            <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition-all duration-300 dark:bg-orange-900/20 dark:text-orange-400">
                <i data-lucide="users" class="w-7 h-7"></i>
            </div>
            <div class="text-right">
                <span class="text-[10px] uppercase font-black text-orange-500 tracking-widest px-2 py-1 bg-orange-50 rounded-lg dark:bg-orange-900/20">{{ __('Active Now') }}</span>
                <p class="text-3xl font-black text-gray-900 dark:text-white mt-1">{{ number_format($visitorStats['live']) }}</p>
            </div>
        </div>
        <div class="space-y-4">
            <div class="flex justify-between items-center text-sm border-b pb-2 dark:border-gray-700">
                <span class="text-gray-500 font-medium">{{ __('Unique Today') }}</span>
                <span class="font-bold text-gray-900 dark:text-white">{{ number_format($visitorStats['today']) }}</span>
            </div>
            <div class="flex justify-between items-center text-sm border-b pb-2 dark:border-gray-700">
                <span class="text-gray-500 font-medium">{{ __('Last 7 Days') }}</span>
                <span class="font-bold text-gray-900 dark:text-white">{{ number_format($visitorStats['last_7_days']) }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-500 font-medium">{{ __('Total Lifetime') }}</span>
                <span class="font-bold text-gray-900 dark:text-white">{{ number_format($visitorStats['total']) }}</span>
            </div>
        </div>
    </div>

    <!-- Sales Card -->
    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg transition-all duration-300 group relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/5 -mr-12 -mt-12 rounded-full"></div>
        <div class="flex items-center justify-between mb-6 relative">
            <div class="w-14 h-14 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition-all duration-300 dark:bg-green-900/20 dark:text-green-400">
                <i data-lucide="banknote" class="w-7 h-7"></i>
            </div>
            <div class="text-right">
                <span class="text-[10px] uppercase font-black text-green-500 tracking-widest px-2 py-1 bg-green-50 rounded-lg dark:bg-orange-900/20">{{ __('Live Sales') }}</span>
                <p class="text-2xl font-black text-gray-900 dark:text-white mt-1">৳{{ number_format($salesStats['sales_today']) }}</p>
            </div>
        </div>
        <div class="space-y-4">
            <div class="flex justify-between items-center text-sm border-b pb-2 dark:border-gray-700">
                <span class="text-gray-500 font-medium">{{ __('Weekly Revenue') }}</span>
                <span class="font-bold text-green-600 dark:text-green-400">৳{{ number_format($salesStats['sales_weekly']) }}</span>
            </div>
            <div class="flex justify-between items-center text-sm border-b pb-2 dark:border-gray-700">
                <span class="text-gray-500 font-medium">{{ __('Total Revenue') }}</span>
                <span class="font-bold text-gray-900 dark:text-white">৳{{ number_format($salesStats['total_sales']) }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-500 font-medium">{{ __('Target Growth') }}</span>
                <span class="font-bold text-blue-500">+12.5%</span>
            </div>
        </div>
    </div>

    <!-- Orders Card -->
    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg transition-all duration-300 group relative overflow-hidden text-white bg-gradient-to-br from-[#111827] to-[#1f2937]">
        <div class="flex items-center justify-between mb-6 relative">
            <div class="w-14 h-14 rounded-2xl bg-white/10 text-orange-400 flex items-center justify-center group-hover:bg-orange-500 group-hover:text-white transition-all duration-300">
                <i data-lucide="shopping-bag" class="w-7 h-7"></i>
            </div>
            <div class="text-right">
                <span class="text-[10px] uppercase font-black text-orange-400 tracking-widest px-2 py-1 bg-white/10 rounded-lg">{{ __('Performance') }}</span>
                <p class="text-3xl font-black mt-1">{{ number_format($salesStats['total_orders']) }}</p>
            </div>
        </div>
        <div class="space-y-4">
            <div class="flex justify-between items-center text-sm border-b border-white/10 pb-2">
                <span class="text-gray-400 font-medium">{{ __('Orders Today') }}</span>
                <span class="font-bold text-white">{{ number_format($salesStats['orders_today']) }}</span>
            </div>
            <div class="flex justify-between items-center text-sm border-b border-white/10 pb-2">
                <span class="text-gray-400 font-medium">{{ __('Weekly Orders') }}</span>
                <span class="font-bold text-white">{{ number_format($salesStats['orders_weekly']) }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-400 font-medium">{{ __('Conversion Rate') }}</span>
                <span class="font-bold text-green-400">4.2%</span>
            </div>
        </div>
    </div>
</div>

<!-- Main Visitor Chart -->
<div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700 mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <h3 class="font-bold text-gray-900 text-lg dark:text-white flex items-center gap-2">
            <i data-lucide="bar-chart-3" class="w-5 h-5 text-[#FF6A00]"></i> Traffic Trends (Last 30 Days)
        </h3>
        <div class="flex items-center gap-4 text-xs font-medium text-gray-500">
            <div class="flex items-center gap-1.5"><span class="w-3 h-3 bg-[#FF6A00] rounded-full"></span> Unique Visitors</div>
        </div>
    </div>
    <div class="relative h-[350px] w-full">
        <canvas id="visitorChart"></canvas>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const isDark = document.documentElement.classList.contains('dark');
    const visCtx = document.getElementById('visitorChart').getContext('2d');
    const visData = @json($visitorChartData);
    
    const gradient = visCtx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, '#FF6A00');
    gradient.addColorStop(1, '#FFAC71');

    new Chart(visCtx, {
        type: 'bar',
        data: {
            labels: visData.map(d => d.day),
            datasets: [{
                label: 'Unique Visitors',
                data: visData.map(d => d.count),
                backgroundColor: gradient,
                hoverBackgroundColor: '#FF7A1A',
                borderRadius: 8,
                barThickness: 'flex',
                maxBarThickness: 30
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111827',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Visitors: ' + context.raw.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { 
                        color: isDark ? '#374151' : '#f3f4f6',
                        drawBorder: false
                    }, 
                    ticks: { 
                        stepSize: 1, 
                        color: '#9ca3af',
                        font: { size: 11 }
                    } 
                },
                x: { 
                    grid: { display: false }, 
                    ticks: { 
                        color: '#9ca3af',
                        font: { size: 10 },
                        autoSkip: true,
                        maxRotation: 0
                    } 
                }
            }
        }
    });
    // Real-time Live Visitor update
    (function() {
        async function updateLiveVisitors() {
            try {
                const response = await fetch('{{ route("admin.stats-ping") }}');
                const data = await response.json();
                if (data.live_visitors !== undefined) {
                    const el = document.getElementById('live-visitor-count');
                    if (el) el.innerText = data.live_visitors.toLocaleString();
                }
            } catch (e) { console.error('Live sync error'); }
        }
        setInterval(updateLiveVisitors, 10000); // 10 seconds
    })();
</script>
@endpush
