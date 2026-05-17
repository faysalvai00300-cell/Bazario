@extends('layouts.admin')
@section('title', 'Bazario Dashboard')

@section('content')
<style>
    @keyframes chartSpin {
        0% { transform: rotate(-360deg) scale(0); opacity: 0; }
        70% { transform: rotate(20deg) scale(1.1); opacity: 1; }
        100% { transform: rotate(0deg) scale(1); opacity: 1; }
    }
    .animate-chart-spin {
        animation: chartSpin 1.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        transform-origin: center center;
    }
</style>

<!-- Header -->
<div class="mb-6">
    <h1 class="text-xl font-bold mb-1.5 text-dark-custom">Hi! Welcome To Dashboard</h1>
    <div class="text-xs text-gray-500 font-medium">Home — <span class="text-gray-400">Sales Dashboard</span></div>
</div>

<!-- Banner -->
<div class="bg-purple-gradient rounded-[14px] p-6 sm:p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center text-white mb-6 shadow-sm border border-[#6c55ef]/20">
    <div class="mb-4 sm:mb-0">
        <h2 class="text-[22px] font-bold mb-2">Congratulations Admin 🎉</h2>
        <p class="text-[13px] text-white/90">You have reached your sales milestone! Keep going strong 💪</p>
    </div>
    <div class="text-left sm:text-right">
        <div class="text-[28px] font-extrabold mb-0.5 leading-none tracking-tight">TK <span x-text="$store.stats.total_sales.toLocaleString(undefined, {minimumFractionDigits: 2})"></span></div>
        <div class="text-xs text-white/80">Total Sales</div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-blue-400 to-blue-600"></div>
        <div class="p-5">
            <div class="text-xs text-gray-400 mb-2 font-semibold uppercase tracking-wide">Total Orders</div>
            <div class="text-[26px] font-extrabold text-gray-800" x-text="$store.stats.total_orders.toLocaleString()"></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
        <div class="p-5">
            <div class="text-xs text-gray-400 mb-2 font-semibold uppercase tracking-wide">Total Customers</div>
            <div class="text-[26px] font-extrabold text-gray-800" x-text="$store.stats.total_customers.toLocaleString()"></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-violet-400 to-violet-600"></div>
        <div class="p-5">
            <div class="text-xs text-gray-400 mb-2 font-semibold uppercase tracking-wide">Total Products</div>
            <div class="text-[26px] font-extrabold text-gray-800" x-text="$store.stats.total_products.toLocaleString()"></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-amber-400 to-orange-500"></div>
        <div class="p-5">
            <div class="text-xs text-gray-400 mb-2 font-semibold uppercase tracking-wide">Delivered Orders</div>
            <div class="text-[26px] font-extrabold text-gray-800" x-text="$store.stats.delivered_orders.toLocaleString()"></div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Pie Chart -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col">
        <h3 class="font-bold text-dark-custom text-[14px] mb-8">Sales By Category</h3>
        <div class="relative flex-1 w-full flex items-center justify-center min-h-[220px]">
            <div class="w-full max-w-[200px] h-[200px] relative">
                <canvas id="categoryChart" class="animate-chart-spin"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-2">
                    <span class="text-gray-400 text-[10px] mb-0.5">Total Sales</span>
                    <span class="text-gray-700 font-bold text-xs">৳ {{ number_format($stats['total_sales'] ?? 0) }}</span>
                </div>
            </div>
        </div>
        <div class="flex justify-center items-center gap-4 mt-8 text-[11px] text-gray-500 font-medium w-full flex-wrap">
            @foreach($categorySales->take(4) as $index => $cat)
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ ['#008ffb', '#00e396', '#feb019', '#ff4560'][$index] ?? '#775dd0' }}"></span> 
                    {{ $cat['name'] }}
                </div>
            @endforeach
        </div>
    </div>

    <!-- Area Chart -->
    <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col">
        <h3 class="font-bold text-dark-custom text-[14px] mb-6">Monthly Sales Statistics</h3>
        <div class="relative flex-1 w-full min-h-[250px]">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Doughnut Chart Setup
        const categoryData = @json($categorySales);
        const ctxDoughnut = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: categoryData.map(c => c.name),
                datasets: [{
                    data: categoryData.map(c => c.sales),
                    backgroundColor: ['#008ffb', '#00e396', '#feb019', '#ff4560', '#775dd0'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) { label += ': '; }
                                if (context.raw !== null) {
                                    label += '৳' + new Intl.NumberFormat().format(context.raw);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // Area Chart Setup
        const ctxArea = document.getElementById('monthlyChart').getContext('2d');
        const gradient = ctxArea.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(0, 143, 251, 0.2)');
        gradient.addColorStop(1, 'rgba(0, 143, 251, 0)');

        const revenueData = @json($revenueData ?? []);
        let labels = revenueData.length > 0 ? revenueData.map(d => d.month) : ['2026-03-07', '2026-03-08'];
        let dataValues = revenueData.length > 0 ? revenueData.map(d => d.revenue) : [1420, 13240];

        new Chart(ctxArea, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sales Statistics',
                    data: dataValues,
                    borderColor: '#008ffb',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    tension: 0.4, // Smooth curve
                    fill: true,
                    pointBackgroundColor: '#008ffb',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6',
                            drawBorder: false,
                        },
                        ticks: {
                            color: '#9ca3af',
                            font: { size: 10 },
                            stepSize: 3000
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            color: '#9ca3af',
                            font: { size: 10 }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
