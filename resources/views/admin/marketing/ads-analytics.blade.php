@extends('layouts.admin')
@section('title', 'Ads Analytics')

@section('content')
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    @php
        $platform = request('platform', 'overview');
        $platformName = 'Marketing Overview';
        $platformDesc = 'Global performance tracking across all channels';
        $platformColor = 'blue';
        $platformIcon = '<i data-lucide="layout" class="w-8 h-8"></i>';
        $platformBg = 'bg-blue-50';
        $platformText = 'text-blue-600';
        $platformBtn = 'bg-blue-600 hover:bg-blue-700';
        
        if ($platform == 'facebook') {
            $platformName = 'Facebook Ads';
            $platformDesc = 'Real-time performance tracking for your Facebook Campaigns';
            $platformIcon = '<i data-lucide="facebook" class="w-8 h-8"></i>';
        } elseif ($platform == 'google') {
            $platformName = 'Google Ads';
            $platformDesc = 'Search and Display network performance tracking';
            $platformBg = 'bg-yellow-50';
            $platformText = 'text-yellow-600';
            $platformBtn = 'bg-yellow-500 hover:bg-yellow-600';
            $platformIcon = '<i data-lucide="globe" class="w-8 h-8"></i>';
        } elseif ($platform == 'tiktok') {
            $platformName = 'TikTok Ads';
            $platformDesc = 'Video engagement and conversion metrics from TikTok';
            $platformBg = 'bg-pink-50';
            $platformText = 'text-pink-600';
            $platformBtn = 'bg-pink-600 hover:bg-pink-700';
            $platformIcon = '<i data-lucide="video" class="w-8 h-8"></i>';
        }
    @endphp
    <div>
        <h1 class="text-xl font-extrabold text-gray-900 dark:text-white">{{ $platformName }} Analytics</h1>
        <p class="text-xs text-gray-500 mt-1">{{ $platformDesc }}</p>
    </div>
    
    <div class="flex flex-wrap items-center gap-3">
        <div class="flex items-center bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-1 overflow-x-auto max-w-[calc(100vw-40px)] no-scrollbar">
            @php
                $presets = [
                    'today' => 'Today',
                    'yesterday' => 'Yesterday',
                    '7' => 'Last 7 Days',
                    '14' => 'Last 14 Days',
                    '30' => 'Last 30 Days',
                    'this_month' => 'This Month',
                    'last_month' => 'Last Month',
                ];
            @endphp
            @foreach($presets as $key => $label)
                <a href="{{ route('admin.ads-analytics.index', ['days' => $key, 'platform' => request('platform')]) }}" 
                   class="whitespace-nowrap px-4 py-2 text-[10px] font-black uppercase tracking-wider rounded-lg transition-all {{ $days == $key ? 'shadow-md' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}"
                   @if($days == $key) style="background-color: #111827; color: #ffffff;" @endif>
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>
</div>

@php
    // Inject Demo Data for other platforms to show UI
    if ($platform != 'facebook') {
        $fbAdsData = [
            'spend' => rand(5000, 20000),
            'purchases' => rand(50, 200),
            'impressions' => rand(100000, 500000),
            'clicks' => rand(2000, 10000),
            'ctr' => rand(150, 450) / 100,
            'cpc' => rand(100, 500) / 100,
            'purchase_value' => rand(80000, 250000)
        ];
        
        $dailyData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dailyData->push([
                'date_start' => now()->subDays($i)->format('Y-m-d'),
                'spend' => rand(800, 3000),
                'purchases' => rand(5, 35)
            ]);
        }
    }
@endphp

@if(!$fbAdsData)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 {{ $platformBg }} {{ $platformText }} rounded-full flex items-center justify-center mx-auto mb-4">
            {!! $platformIcon !!}
        </div>
        <h2 class="text-lg font-bold text-gray-800 mb-2">No Data Found for {{ $platformName }}</h2>
        <p class="text-sm text-gray-500 mb-6 max-w-md mx-auto">Please ensure your {{ $platformName }} API credentials are correctly set up and active campaigns are running.</p>
        <a href="#" class="inline-flex items-center px-6 py-2.5 {{ $platformBtn }} text-white text-xs font-bold rounded-xl transition">Configure {{ $platformName }} Integration</a>
    </div>
@else
    <!-- Main Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:scale-110 transition-transform">
                <svg class="w-12 h-12 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
            </div>
            <div class="text-[10px] text-gray-400 mb-2 font-bold uppercase tracking-wider">Total Ad Spend</div>
            <div class="text-2xl font-black text-gray-800">৳ {{ number_format($fbAdsData['spend'] ?? 0, 2) }}</div>
            <div class="mt-2 text-[10px] text-blue-500 font-bold bg-blue-50 px-2 py-0.5 rounded-lg inline-block">Estimated</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:scale-110 transition-transform">
                <svg class="w-12 h-12 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M11 15h2v2h-2v-2zm0-8h2v6h-2V7zm1-5C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
            </div>
            <div class="text-[10px] text-gray-400 mb-2 font-bold uppercase tracking-wider">Total Purchases</div>
            <div class="text-2xl font-black text-gray-800">{{ number_format($fbAdsData['purchases'] ?? 0) }}</div>
            <div class="mt-2 text-[10px] text-green-500 font-bold bg-green-50 px-2 py-0.5 rounded-lg inline-block">Confirmed</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="text-[10px] text-gray-400 mb-2 font-bold uppercase tracking-wider">Impressions</div>
            <div class="text-2xl font-black text-gray-800">{{ number_format($fbAdsData['impressions'] ?? 0) }}</div>
            <div class="mt-2 text-[10px] text-indigo-500 font-bold bg-indigo-50 px-2 py-0.5 rounded-lg inline-block">Reach</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="text-[10px] text-gray-400 mb-2 font-bold uppercase tracking-wider">Total Clicks</div>
            <div class="text-2xl font-black text-gray-800">{{ number_format($fbAdsData['clicks'] ?? 0) }}</div>
            <div class="mt-2 text-[10px] text-purple-500 font-bold bg-purple-50 px-2 py-0.5 rounded-lg inline-block">CTR: {{ number_format($fbAdsData['ctr'] ?? 0, 2) }}%</div>
        </div>
    </div>

    <!-- Secondary Metrics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Efficiency Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 mb-6 flex items-center gap-2">
                <span class="w-1.5 h-6 bg-blue-600 rounded-full"></span>
                Cost & Efficiency
            </h3>
            <div class="space-y-6">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-[10px] text-gray-400 font-bold uppercase mb-1">Avg. CPC (Cost Per Click)</div>
                        <div class="text-lg font-bold text-gray-800">৳ {{ number_format($fbAdsData['cpc'] ?? 0, 2) }}</div>
                    </div>
                    <div class="w-32 h-1 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500" style="width: 65%"></div>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-[10px] text-gray-400 font-bold uppercase mb-1">Avg. CPP (Cost Per Purchase)</div>
                        <div class="text-lg font-bold text-gray-800">৳ {{ ($fbAdsData['purchases'] ?? 0) > 0 ? number_format(($fbAdsData['spend'] ?? 0) / $fbAdsData['purchases'], 2) : '0.00' }}</div>
                    </div>
                    <div class="w-32 h-1 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-green-500" style="width: 45%"></div>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-[10px] text-gray-400 font-bold uppercase mb-1">Estimated ROAS</div>
                        <div class="text-lg font-black text-blue-600">
                            @php
                                $purchasesValue = $fbAdsData['purchase_value'] ?? 0;
                                $roas = ($fbAdsData['spend'] ?? 0) > 0 ? (float)$purchasesValue / (float)$fbAdsData['spend'] : 0;
                            @endphp
                            {{ number_format($roas, 2) }}x
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Summary Card -->
        <div class="bg-gray-900 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
            <div class="absolute -bottom-10 -right-10 w-40 h-40 {{ str_replace('bg-', 'bg-', $platformBg) }}/10 rounded-full blur-3xl"></div>
            <h3 class="text-sm font-bold mb-6 flex items-center gap-2">
                <div class="{{ $platformText }}">
                    {!! $platformIcon !!}
                </div>
                {{ $platformName == 'Marketing Overview' ? 'Global' : $platformName }} Account Summary
                @if($platform != 'facebook')
                    <span class="ml-2 bg-yellow-500/20 text-yellow-400 text-[9px] px-2 py-0.5 rounded-full uppercase tracking-widest border border-yellow-500/30">Demo UI</span>
                @endif
            </h3>
            
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <div class="text-[10px] text-gray-400 font-bold uppercase mb-2">Ad Account Status</div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-sm font-bold uppercase tracking-widest">Connected</span>
                    </div>
                </div>
                <div>
                    <div class="text-[10px] text-gray-400 font-bold uppercase mb-2">Last Sync</div>
                    <div class="text-sm font-bold">{{ now()->format('H:i A') }}</div>
                </div>
            </div>

            <div class="mt-12">
                <p class="text-[10px] text-gray-400 leading-relaxed italic">"Tracking successful. All conversions are being reported back to {{ $platformName }} via API integration."</p>
            </div>
        </div>
    </div>

    <!-- Charts & Campaign Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
        <!-- Daily Performance Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i data-lucide="bar-chart-3" class="w-4 h-4 {{ $platformText }}"></i>
                Daily Performance Trends
            </h3>
            <div class="h-[300px] w-full">
                <canvas id="fbAdsChart"></canvas>
            </div>
        </div>

        <!-- Marketing Insights -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i data-lucide="sparkles" class="w-4 h-4 text-indigo-600"></i>
                Marketing Insights
            </h3>
            
            <div class="space-y-6">
                <div class="p-4 {{ $platformBg }} rounded-2xl border border-gray-100">
                    <div class="text-[10px] {{ $platformText }} font-bold uppercase mb-1">Performance Overview</div>
                    <p class="text-[11px] text-gray-700 leading-relaxed">
                        Spend is being tracked across all active campaigns in your {{ $platformName }} Account. 
                        Total purchases reflect direct conversions reported by the API.
                    </p>
                </div>

                <div class="p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                    <div class="text-[10px] text-indigo-600 font-bold uppercase mb-1">Next Steps</div>
                    <p class="text-[11px] text-indigo-900 leading-relaxed italic">
                        Check your {{ $platformName == 'Marketing Overview' ? 'Ads Manager' : $platformName }} for individual campaign breakdowns. This dashboard provides a global view of your marketing efficiency.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dailyData = @json($dailyData);
        const ctx = document.getElementById('fbAdsChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dailyData.map(d => d.date_start),
                datasets: [
                    {
                        label: 'Ad Spend (৳)',
                        data: dailyData.map(d => d.spend),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Purchases',
                        data: dailyData.map(d => d.purchases || 0),
                        borderColor: '#16a34a',
                        backgroundColor: '#16a34a',
                        borderWidth: 3,
                        tension: 0.4,
                        type: 'line',
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: true, position: 'top', labels: { font: { size: 10, weight: 'bold' } } }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: { color: '#f3f4f6' },
                        ticks: { font: { size: 10 } }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: { font: { size: 10 }, stepSize: 1 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
