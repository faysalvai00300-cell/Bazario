<!DOCTYPE html>
<html lang="en" x-data="{ 
    open: false,
    toggle() { this.open = !this.open },
    close() { this.open = false }
}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <meta name="robots" content="noindex, nofollow">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('styles')
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; background-color: #f5f5f6; }
        .sidebar-bg { background-color: #1e1e2d; }
        .sidebar-link { color: #a2a3b7; padding: 0.75rem 1.7rem; display: flex; align-items: center; gap: 0.75rem; font-size: 0.85rem; transition: all 0.3s ease; text-decoration: none; border-bottom: 1px solid rgba(255, 255, 255, 0.15); }
        .sidebar-link:hover { color: #ffffff; background-color: #1b1b28; }
        .sidebar-link.active { color: #ffb822; background-color: #1b1b28; border-left: 3px solid #ffb822; padding-left: calc(1.7rem - 3px); }
        .sidebar-icon { width: 1.1rem; height: 1.1rem; opacity: 0.8; }
        .sidebar-link:hover .sidebar-icon, .sidebar-link.active .sidebar-icon { opacity: 1; }
        .topbar-bg { background-color: #1e1e2d; border-bottom: 1px solid #1e1e2d; }
        
        .bg-purple-gradient { background: linear-gradient(to right, #7B66FF, #6c55ef); }
        .text-dark-custom { color: #0d0925; }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #2b2b3c; border-radius: 10px; }
        
        /* Global Admin Primary Button Style Fixes */
        .admin-primary-btn, .btn-primary {
            background-color: #FF6A00 !important;
            color: #ffffff !important;
            border: none;
        }
        .admin-primary-btn:hover, .btn-primary:hover {
            background-color: #FF7A1A !important;
        }
        
        /* Layout Fixes without Tailwind JIT */
        @media (min-width: 768px) {
            .desktop-ml-256 { margin-left: 256px !important; }
            .desktop-w-calc { width: calc(100% - 256px) !important; }
        }
    </style>
</head>
<body class="text-gray-900 font-sans antialiased overflow-x-hidden min-h-screen bg-gray-50 flex flex-col">
    <!-- Top Header Full Width Fix -->
    <div id="admin-header" class="h-16 text-white flex items-center justify-between px-4 fixed top-0 w-full shadow-sm" style="background-color: #1e1e2d; border-bottom: 1px solid #2b2b3c; z-index: 1000;">
        <div class="flex items-center">
            <!-- Logo Area -->
            <div class="w-64 flex items-center justify-between shrink-0 pr-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-white decoration-transparent">
                    <img src="{{ asset('final logo.jpeg') }}" alt="Logo" class="h-7 w-auto object-contain rounded-sm">
                    <span class="text-[22px] font-bold tracking-tight leading-none mt-0.5">SmartLookBD</span>
                </a>
                <button @click="toggle()" class="text-gray-400 hover:text-white transition hidden md:block ml-auto">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
            </div>
            
            <button @click="toggle()" class="md:hidden text-gray-400 hover:text-white transition mr-4">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            
            <a href="{{ route('home') }}" target="_blank" class="text-xs font-medium text-gray-300 hover:text-white hidden sm:flex items-center gap-1.5 ml-4 decoration-transparent">
                <i data-lucide="globe" class="w-3.5 h-3.5"></i> {{ __('Visit Site') }}
            </a>
        </div>
        
        <!-- Right Icons -->
        <div class="flex items-center gap-4 sm:gap-6">
            <a href="{{ route('set-locale', app()->getLocale() === 'bn' ? 'en' : 'bn') }}" class="hidden sm:flex items-center gap-1.5 text-xs font-semibold text-[#ffb822] bg-[#ffb822]/10 px-2.5 py-1 rounded border border-[#ffb822]/20 decoration-transparent">
                <i data-lucide="languages" class="w-3.5 h-3.5"></i>
                <span>{{ app()->getLocale() === 'bn' ? 'English' : 'বাংলা' }}</span>
            </a>
            
            <div class="relative" x-data="notificationData()">
                <script>
                    document.addEventListener('alpine:init', () => {
                        // Global Store for Real-time Stats
                        Alpine.store('stats', {
                            total_orders: {{ $stats['total_orders'] ?? 0 }},
                            total_sales: {{ $stats['total_sales'] ?? 0 }},
                            total_customers: {{ $stats['total_customers'] ?? 0 }},
                            total_products: {{ $stats['total_products'] ?? 0 }},
                            delivered_orders: {{ $stats['delivered_orders'] ?? 0 }},
                            live_visitors: 0
                        });

                        Alpine.store('imageModal', {
                            show: false,
                            url: '',
                            open(url) {
                                this.url = url;
                                this.show = true;
                                document.body.style.overflow = 'hidden';
                            },
                            close() {
                                this.show = false;
                                document.body.style.overflow = '';
                            }
                        });

                        Alpine.data('notificationData', () => ({
                            open: false,
                            unreadCount: {{ \App\Models\Order::where('status', 'pending')->count() }},
                            latestOrderId: {{ \App\Models\Order::latest()->first()?->id ?? 0 }},
                            isCleared: localStorage.getItem('notifications_cleared_at') > '{{ \App\Models\Order::where('status', 'pending')->latest()->first()?->created_at }}',
                            clear() {
                                localStorage.setItem('notifications_cleared_at', new Date().toISOString());
                                this.isCleared = true;
                            },
                            init() {
                                // Background tab support: Ensure interval runs even if tab is inactive
                                const startPing = () => {
                                    setInterval(() => {
                                        fetch('{{ route('admin.stats-ping') }}', { cache: 'no-store' })
                                            .then(res => res.json())
                                            .then(data => {
                                                // Update Global Store for Real-time Dashboard
                                                if (data.stats) {
                                                    Alpine.store('stats').total_orders = data.stats.total_orders;
                                                    Alpine.store('stats').total_sales = data.stats.total_sales;
                                                    Alpine.store('stats').total_customers = data.stats.total_customers;
                                                    Alpine.store('stats').total_products = data.stats.total_products;
                                                    Alpine.store('stats').delivered_orders = data.stats.delivered_orders;
                                                }
                                                Alpine.store('stats').live_visitors = data.live_visitors;

                                                if (data.latest_order_id > this.latestOrderId) {
                                                    this.latestOrderId = data.latest_order_id;
                                                    this.unreadCount = data.pending_orders;
                                                    this.isCleared = false;
                                                    
                                                    // Show browser notification
                                                    if (Notification.permission === 'granted') {
                                                        const n = new Notification('New Order Received!', {
                                                            body: 'Order #' + data.latest_order_id + ' has just arrived.',
                                                            icon: '{{ asset('final logo.jpeg') }}',
                                                            tag: 'new-order'
                                                        });
                                                        n.onclick = () => { window.focus(); n.close(); };
                                                    }
                                                } else if (data.pending_orders !== this.unreadCount) {
                                                    this.unreadCount = data.pending_orders;
                                                }
                                            })
                                            .catch(e => {});
                                    }, 3000);
                                };

                                startPing();
                                
                                if (Notification.permission === 'default') {
                                    const requestNotif = () => {
                                        Notification.requestPermission();
                                        document.removeEventListener('click', requestNotif);
                                    };
                                    document.addEventListener('click', requestNotif);
                                }
                            }
                        }));
                    });
                </script>
                <button @click="open = !open" @click.away="open = false" class="relative text-gray-400 hover:text-white transition focus:outline-none mt-1.5 p-2 rounded-full hover:bg-white/5">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <template x-if="!isCleared && unreadCount > 0">
                        <span class="absolute top-1 right-1 w-4 h-4 bg-[#FF6A00] text-white text-[9px] font-black flex items-center justify-center rounded-full border-2 border-[#1e1e2d] animate-pulse"
                              x-text="unreadCount > 9 ? '9+' : unreadCount">
                        </span>
                    </template>
                </button>

                <!-- Premium Dropdown Menu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                     class="absolute right-0 mt-3 bg-white dark:bg-[#1e1e2d] rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] border border-gray-100 dark:border-gray-700 overflow-hidden z-[1100]" 
                     style="width: 420px;"
                     x-cloak>
                    
                    <!-- Header -->
                    <div class="p-5 bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-extrabold text-gray-900 dark:text-white tracking-tight">Notifications</h3>
                            <p class="text-[10px] text-gray-500 font-medium" x-text="'You have ' + unreadCount + ' pending orders'"></p>
                        </div>
                        <button @click="clear()" class="text-[10px] font-bold text-[#FF6A00] hover:text-[#FF7A1A] transition-colors uppercase tracking-wider flex items-center gap-1">
                            <i data-lucide="check-check" class="w-3 h-3"></i> Mark all read
                        </button>
                    </div>

                    <!-- List -->
                    <div class="max-h-[380px] overflow-y-auto custom-scrollbar bg-white dark:bg-[#1e1e2d]">
                        @php 
                            $recentOrders = \App\Models\Order::latest()->take(5)->get();
                            $lowStockProducts = \App\Models\Product::where('stock', '<=', 5)->latest()->take(5)->get();
                            $unreadCount = \App\Models\Order::where('status', 'pending')->count() + \App\Models\Product::where('stock', '<=', 5)->count();
                        @endphp
                        
                        <!-- Order Section Header -->
                        <div class="px-4 py-2 bg-gray-50/30 dark:bg-gray-800/20 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 dark:border-gray-700/50">
                            Recent Orders
                        </div>
                        
                        @forelse($recentOrders as $order)
                        <a href="{{ route('admin.orders.index', ['search' => $order->order_number]) }}" class="group flex items-start gap-4 p-4 hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-all border-b border-gray-50 dark:border-gray-700/50 decoration-none relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#FF6A00] transform -translate-x-full group-hover:translate-x-0 transition-transform"></div>
                            
                            <div class="w-11 h-11 rounded-xl flex-shrink-0 flex items-center justify-center transition-transform group-hover:scale-110 {{ $order->status === 'pending' ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/20' : 'bg-blue-50 text-blue-600 dark:bg-blue-900/20' }}">
                                <i data-lucide="{{ $order->status === 'pending' ? 'clock-alert' : 'package-check' }}" class="w-5 h-5"></i>
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <p class="text-[12px] font-bold text-gray-900 dark:text-white group-hover:text-[#FF6A00] transition-colors">Order #{{ $order->order_number }}</p>
                                    <span class="text-[9px] font-semibold text-gray-400">{{ $order->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-[11px] text-gray-500 font-medium truncate mb-2">{{ $order->name }} • {{ number_format($order->total, 2) }} Tk</p>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-[9px] px-2 py-0.5 rounded-full font-extrabold uppercase tracking-tighter {{ $order->status === 'pending' ? 'bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400' }}">
                                        {{ $order->status }}
                                    </span>
                                    <i data-lucide="chevron-right" class="w-3 h-3 text-gray-300 group-hover:text-[#FF6A00] transform transition-all group-hover:translate-x-1"></i>
                                </div>
                            </div>
                        </a>
                        @empty
                        <p class="p-4 text-center text-[10px] text-gray-400">No recent orders</p>
                        @endforelse

                        <!-- Stock Section Header -->
                        @if($lowStockProducts->count() > 0)
                        <div class="px-4 py-2 bg-red-50/30 dark:bg-red-900/10 text-[10px] font-bold text-red-400 uppercase tracking-widest border-b border-gray-50 dark:border-gray-700/50 mt-2">
                            Stock Alerts
                        </div>

                        @foreach($lowStockProducts as $product)
                        <a href="{{ route('admin.products.index', ['search' => $product->name]) }}" class="group flex items-start gap-4 p-4 hover:bg-red-50/30 dark:hover:bg-red-900/10 transition-all border-b border-gray-50 dark:border-gray-700/50 decoration-none relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500 transform -translate-x-full group-hover:translate-x-0 transition-transform"></div>
                            
                            <div class="w-11 h-11 rounded-xl flex-shrink-0 flex items-center justify-center transition-transform group-hover:scale-110 bg-red-50 text-red-600 dark:bg-red-900/20">
                                <i data-lucide="{{ $product->stock == 0 ? 'alert-octagon' : 'alert-circle' }}" class="w-5 h-5"></i>
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <p class="text-[12px] font-bold text-gray-900 dark:text-white group-hover:text-red-600 transition-colors">{{ $product->name }}</p>
                                    <span class="text-[9px] font-semibold text-red-400">Low Stock</span>
                                </div>
                                <p class="text-[11px] text-gray-500 font-medium truncate mb-2">Current Quantity: <span class="font-bold text-red-600">{{ $product->stock }}</span></p>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-[9px] px-2 py-0.5 rounded-full font-extrabold uppercase tracking-tighter bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400">
                                        {{ $product->stock == 0 ? 'Out of Stock' : 'Replenish Soon' }}
                                    </span>
                                    <i data-lucide="chevron-right" class="w-3 h-3 text-gray-300 group-hover:text-red-600 transform transition-all group-hover:translate-x-1"></i>
                                </div>
                            </div>
                        </a>
                        @endforeach
                        @endif

                        @if($recentOrders->isEmpty() && $lowStockProducts->isEmpty())
                        <div class="py-12 text-center">
                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i data-lucide="bell-off" class="w-8 h-8 text-gray-300 dark:text-gray-600"></i>
                            </div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">All caught up!</p>
                        </div>
                        @endif
                    </div>

                    <!-- Footer -->
                    <a href="{{ route('admin.orders.index') }}" class="group block p-4 text-center bg-gray-50/30 dark:bg-gray-800/30 hover:bg-gray-50 transition-all decoration-none border-t border-gray-100 dark:border-gray-700">
                        <span class="text-[11px] font-extrabold text-gray-600 dark:text-gray-400 group-hover:text-[#FF6A00] uppercase tracking-widest flex items-center justify-center gap-2 transition-colors">
                            Manage All Orders <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        </span>
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-2 cursor-pointer border-l border-gray-700 pl-4 ml-2">
                <div class="w-8 h-8 rounded-full bg-gray-300 overflow-hidden ring-2 ring-gray-700">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=random" class="w-full h-full object-cover">
                </div>
                <span class="text-xs font-medium text-gray-200 hidden sm:block">Admin</span>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 hidden sm:block"></i>
            </div>
        </div>
    </div>

    <!-- Main Container Layout Fix -->
    <div class="flex min-h-screen w-full relative" style="padding-top: 64px;">
        
        <!-- Mobile Sidebar Overlay -->
        <div x-show="open" x-cloak @click="close()" class="fixed inset-0 bg-black/50 z-[45] md:hidden cursor-pointer" x-transition.opacity></div>

        <!-- Sidebar Fixed -->
        <aside class="w-64 flex-shrink-0 fixed transition-transform duration-300 flex flex-col md:translate-x-0"
               :class="open ? 'translate-x-0' : '-translate-x-full'" style="background-color: #1e1e2d; z-index: 999; top: 64px; bottom: 0; height: calc(100vh - 64px);">
            <nav class="flex-1 overflow-y-auto custom-scrollbar py-4 min-h-0">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i data-lucide="monitor" class="sidebar-icon"></i> {{ __('Dashboard') }}
                </a>
                
                <button type="button" onclick="window.location.reload();" class="sidebar-link w-full text-left bg-transparent border-none cursor-pointer focus:outline-none">
                    <i data-lucide="refresh-cw" class="sidebar-icon"></i> {{ __('Refresh Page') }}
                </button>

                <a href="{{ route('admin.pos.index') }}" class="sidebar-link {{ request()->routeIs('admin.pos.*') ? 'active' : '' }}">
                    <i data-lucide="monitor-dot" class="sidebar-icon"></i> {{ __('POS System') }}
                </a>
                
                <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i data-lucide="box" class="sidebar-icon"></i> {{ __('Products') }}
                </a>
                
                <div x-data="{ isCategoriesOpen: {{ request()->routeIs('admin.categories.*') ? 'true' : 'false' }} }">
                    <button @click="isCategoriesOpen = !isCategoriesOpen" class="w-full flex items-center justify-between sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }} border-none cursor-pointer focus:outline-none">
                        <div class="flex items-center gap-[0.75rem]">
                            <i data-lucide="folder" class="sidebar-icon"></i> <span class="font-medium text-[0.85rem]">{{ __('Categories') }}</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300" :class="isCategoriesOpen ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <div x-show="isCategoriesOpen" {!! request()->routeIs('admin.categories.*') ? '' : 'style="display: none;"' !!} 
                         x-transition:enter="transition-all ease-in-out duration-300" 
                         x-transition:enter-start="opacity-0 max-h-0" 
                         x-transition:enter-end="opacity-100 max-h-96" 
                         x-transition:leave="transition-all ease-in-out duration-300" 
                         x-transition:leave-start="opacity-100 max-h-96" 
                         x-transition:leave-end="opacity-0 max-h-0" 
                         class="bg-[#151521] border-l-2 border-gray-700 ml-4 mt-1 rounded-bl-lg pb-1 overflow-hidden">
                        <a href="{{ route('admin.categories.index') }}" class="block px-6 py-2.5 text-xs font-medium text-gray-400 hover:text-[#ffb822] hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.categories.index') && !request()->has('target_page') ? 'text-[#ffb822] bg-gray-800' : '' }}">
                            <div class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-gray-400"></div> {{ __('All Categories') }}</div>
                        </a>
                        <a href="{{ route('admin.categories.index', ['target_page' => 1]) }}" class="block px-6 py-2.5 text-xs font-medium text-gray-400 hover:text-cyan-400 hover:bg-gray-800 transition-colors {{ request('target_page') == 1 ? 'text-cyan-400 bg-gray-800' : '' }}">
                            <div class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-cyan-400"></div> {{ __('Category Page 1') }}</div>
                        </a>
                        <a href="{{ route('admin.categories.index', ['target_page' => 2]) }}" class="block px-6 py-2.5 text-xs font-medium text-gray-400 hover:text-cyan-400 hover:bg-gray-800 transition-colors {{ request('target_page') == 2 ? 'text-cyan-400 bg-gray-800' : '' }}">
                            <div class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-cyan-400"></div> {{ __('Category Page 2') }}</div>
                        </a>
                        <a href="{{ route('admin.categories.index', ['target_page' => 3]) }}" class="block px-6 py-2.5 text-xs font-medium text-gray-400 hover:text-cyan-400 hover:bg-gray-800 transition-colors {{ request('target_page') == 3 ? 'text-cyan-400 bg-gray-800' : '' }}">
                            <div class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-cyan-400"></div> {{ __('Category Page 3') }}</div>
                        </a>
                        <a href="{{ route('admin.categories.index', ['target_page' => 4]) }}" class="block px-6 py-2.5 text-xs font-medium text-gray-400 hover:text-cyan-400 hover:bg-gray-800 transition-colors {{ request('target_page') == 4 ? 'text-cyan-400 bg-gray-800' : '' }}">
                            <div class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-cyan-400"></div> {{ __('Category Page 4') }}</div>
                        </a>
                        <a href="{{ route('admin.categories.index', ['target_page' => 5]) }}" class="block px-6 py-2.5 text-xs font-medium text-gray-400 hover:text-cyan-400 hover:bg-gray-800 transition-colors {{ request('target_page') == 5 ? 'text-cyan-400 bg-gray-800' : '' }}">
                            <div class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-cyan-400"></div> {{ __('Category Page 5') }}</div>
                        </a>
                    </div>
                </div>
                
                <div x-data="{ isOrdersOpen: {{ request()->routeIs('admin.orders.*') ? 'true' : 'false' }} }">
                    <button @click="isOrdersOpen = !isOrdersOpen" class="w-full flex items-center justify-between sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }} border-none cursor-pointer focus:outline-none">
                        <div class="flex items-center gap-[0.75rem]">
                            <i data-lucide="shopping-cart" class="sidebar-icon"></i> <span class="font-medium text-[0.85rem]">{{ __('Orders') }}</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300" :class="isOrdersOpen ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <div x-show="isOrdersOpen" {!! request()->routeIs('admin.orders.*') ? '' : 'style="display: none;"' !!} 
                         x-transition:enter="transition-all ease-in-out duration-300" 
                         x-transition:enter-start="opacity-0 max-h-0" 
                         x-transition:enter-end="opacity-100 max-h-96" 
                         x-transition:leave="transition-all ease-in-out duration-300" 
                         x-transition:leave-start="opacity-100 max-h-96" 
                         x-transition:leave-end="opacity-0 max-h-0" 
                         class="bg-[#151521] border-l-2 border-gray-700 ml-4 mt-1 rounded-bl-lg pb-1 overflow-hidden">
                        <a href="{{ route('admin.orders.index') }}" class="block px-6 py-2.5 text-xs font-medium text-gray-400 hover:text-yellow-500 hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.orders.index') && !request()->has('status') ? 'text-yellow-500 bg-gray-800' : '' }}">
                            <div class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-gray-400"></div> {{ __('All Orders') }}</div>
                        </a>
                        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="block px-6 py-2.5 text-xs font-medium text-gray-400 hover:text-orange-400 hover:bg-gray-800 transition-colors {{ request('status') === 'pending' ? 'text-orange-400 bg-gray-800' : '' }}">
                            <div class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-orange-400"></div> {{ __('Pending') }}</div>
                        </a>
                        <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" class="block px-6 py-2.5 text-xs font-medium text-gray-400 hover:text-cyan-400 hover:bg-gray-800 transition-colors {{ request('status') === 'confirmed' ? 'text-cyan-400 bg-gray-800' : '' }}">
                            <div class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-cyan-400"></div> {{ __('Confirmed') }}</div>
                        </a>
                        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="block px-6 py-2.5 text-xs font-medium text-gray-400 hover:text-blue-400 hover:bg-gray-800 transition-colors {{ request('status') === 'processing' ? 'text-blue-400 bg-gray-800' : '' }}">
                            <div class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-blue-400"></div> {{ __('Processing') }}</div>
                        </a>
                        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="block px-6 py-2.5 text-xs font-medium text-gray-400 hover:text-indigo-400 hover:bg-gray-800 transition-colors {{ request('status') === 'shipped' ? 'text-indigo-400 bg-gray-800' : '' }}">
                            <div class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div> {{ __('Shipped') }}</div>
                        </a>
                        <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="block px-6 py-2.5 text-xs font-medium text-gray-400 hover:text-green-400 hover:bg-gray-800 transition-colors {{ request('status') === 'delivered' ? 'text-green-400 bg-gray-800' : '' }}">
                            <div class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-green-400"></div> {{ __('Delivered') }}</div>
                        </a>
                        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="block px-6 py-2.5 text-xs font-medium text-gray-400 hover:text-red-400 hover:bg-gray-800 transition-colors {{ request('status') === 'cancelled' ? 'text-red-400 bg-gray-800' : '' }}">
                            <div class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-red-400"></div> {{ __('Cancelled') }}</div>
                        </a>
                        <a href="{{ route('admin.orders.index', ['status' => 'incomplete']) }}" class="block px-6 py-2.5 text-xs font-medium text-gray-400 hover:text-pink-400 hover:bg-gray-800 transition-colors {{ request('status') === 'incomplete' ? 'text-pink-400 bg-gray-800' : '' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-pink-400"></div> {{ __('Incomplete Orders') }}</div>
                                <span class="bg-pink-500/20 text-pink-400 text-[10px] px-1.5 py-0.5 rounded-full font-bold">
                                    {{ \App\Models\Order::where('status', 'incomplete')->count() }}
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
                
                <a href="{{ route('admin.customers.index') }}" class="sidebar-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <i data-lucide="users" class="sidebar-icon"></i> {{ __('Customers') }}
                </a>

                <a href="{{ route('admin.sales.index') }}" class="sidebar-link {{ request()->routeIs('admin.sales.*') ? 'active' : '' }}">
                    <i data-lucide="trending-up" class="sidebar-icon"></i> {{ __('Sales Report') }}
                </a>

                <a href="{{ route('admin.banners.index') }}" class="sidebar-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <i data-lucide="image" class="sidebar-icon"></i> {{ __('Hero Banners') }}
                </a>
                
                <a href="{{ route('admin.middle-banners.index') }}" class="sidebar-link {{ request()->routeIs('admin.middle-banners.*') ? 'active' : '' }}">
                    <i data-lucide="gallery-horizontal" class="sidebar-icon"></i> {{ __('Middle Banners') }}
                </a>
                
                <a href="{{ route('admin.promo-codes.index') }}" class="sidebar-link {{ request()->routeIs('admin.promo-codes.*') ? 'active' : '' }}">
                    <i data-lucide="ticket" class="sidebar-icon"></i> {{ __('Promo Codes') }}
                </a>
                
                <a href="{{ route('admin.reviews.index') }}" class="sidebar-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <i data-lucide="star" class="sidebar-icon"></i> {{ __('Reviews') }}
                </a>

                <a href="{{ route('admin.advanced.index') }}" class="sidebar-link {{ request()->routeIs('admin.advanced.*') ? 'active' : '' }}">
                    <i data-lucide="pie-chart" class="sidebar-icon"></i> {{ __('Advance') }}
                </a>
                
                <a href="{{ route('admin.profile.index') }}" class="sidebar-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                    <i data-lucide="user-cog" class="sidebar-icon"></i> {{ __('Profile Setting') }}
                </a>
                <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i data-lucide="settings" class="sidebar-icon"></i> {{ __('Settings') }}
                </a>
                <a href="{{ route('admin.delivery-charges.index') }}" class="sidebar-link {{ request()->routeIs('admin.delivery-charges.*') ? 'active' : '' }}">
                    <i data-lucide="truck" class="sidebar-icon"></i> {{ __('Delivery Charge') }}
                </a>
                <a href="{{ route('admin.delivery-areas.index') }}" class="sidebar-link {{ request()->routeIs('admin.delivery-areas.*') ? 'active' : '' }}">
                    <i data-lucide="map-pin" class="sidebar-icon"></i> {{ __('Delivery Areas') }}
                </a>
                <a href="{{ route('admin.facebook-pixel.index') }}" class="sidebar-link {{ request()->routeIs('admin.facebook-pixel.*') ? 'active' : '' }}">
                    <i data-lucide="target" class="sidebar-icon"></i> {{ __('Facebook Pixel') }}
                </a>
                <a href="{{ route('admin.tiktok-pixel.index') }}" class="sidebar-link {{ request()->routeIs('admin.tiktok-pixel.*') ? 'active' : '' }}">
                    <i data-lucide="video" class="sidebar-icon"></i> {{ __('TikTok Pixel') }}
                </a>
                <a href="{{ route('admin.popup.index') }}" class="sidebar-link {{ request()->routeIs('admin.popup.*') ? 'active' : '' }}">
                    <i data-lucide="image" class="sidebar-icon"></i> {{ __('Show Pop Up') }}
                </a>
                <a href="{{ route('admin.settings.seo.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.seo.*') ? 'active' : '' }}">
                    <i data-lucide="search" class="sidebar-icon"></i> {{ __('Global SEO Settings') }}
                </a>

                <a href="{{ route('admin.settings.payment.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.payment.*') ? 'active' : '' }}">
                    <i data-lucide="credit-card" class="sidebar-icon"></i> {{ __('Payment Gateway Settings') }}
                </a>

                <a href="{{ route('admin.settings.sms.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.sms.*') ? 'active' : '' }}">
                    <i data-lucide="message-square" class="sidebar-icon"></i> {{ __('SMS Gateway Settings') }}
                </a>

                <a href="{{ route('admin.settings.courier.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.courier.*') ? 'active' : '' }}">
                    <i data-lucide="truck" class="sidebar-icon"></i> {{ __('Courier Settings') }}
                </a>

                <a href="{{ route('admin.settings.slider.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.slider.*') ? 'active' : '' }}">
                    <i data-lucide="monitor-play" class="sidebar-icon"></i> {{ __('Product Image Slider') }}
                </a>
                <a href="{{ route('admin.settings.model-notification.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.model-notification.*') ? 'active' : '' }}">
                    <i data-lucide="bell" class="sidebar-icon"></i> {{ __('Model Notification') }}
                </a>
                <a href="{{ route('admin.ip-blocks.index') }}" class="sidebar-link {{ request()->routeIs('admin.ip-blocks.*') ? 'active' : '' }}">
                    <i data-lucide="shield-alert" class="sidebar-icon"></i> {{ __('IP Blocking') }}
                </a>
                <a href="{{ route('admin.guide') }}" class="sidebar-link {{ request()->routeIs('admin.guide') ? 'active' : '' }}">
                    <i data-lucide="help-circle" class="sidebar-icon"></i> {{ __('Instruction Guide') }}
                </a>
            </nav>
            <div class="p-4 border-t border-[#1b1b28]">
                <form action="{{ session('admin_authenticated') ? route('admin.logout') : route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-2.5 w-full rounded-md text-sm font-semibold text-red-400 bg-red-500/10 hover:text-white hover:bg-red-500 transition-colors duration-200">
                        <i data-lucide="log-out" class="w-5 h-5"></i> {{ __('Logout') }}
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content offset by sidebar desktop-ml-256 -->
        <main class="flex-1 desktop-ml-256 desktop-w-calc p-4 sm:p-6 lg:p-8 w-full" style="background-color: #f5f5f6;">
            @if(session('success'))
            <div id="admin-success-alert" class="bg-green-50 text-green-700 p-4 rounded-lg text-sm flex items-center gap-2 mb-6 border border-green-100 transition-opacity duration-500">
                <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
            </div>
            <script>
                setTimeout(function() {
                    const alert = document.getElementById('admin-success-alert');
                    if (alert) {
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    }
                }, 3000);
            </script>
            @endif
            @if(session('error'))
            <div id="admin-error-alert" class="bg-red-50 text-red-700 p-4 rounded-lg text-sm flex items-center gap-2 mb-6 border border-red-100 transition-opacity duration-500">
                <i data-lucide="alert-circle" class="w-4 h-4"></i> {{ session('error') }}
            </div>
            <script>
                setTimeout(function() {
                    const alert = document.getElementById('admin-error-alert');
                    if (alert) {
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    }
                }, 3000);
            </script>
            @endif
            @if($errors->any())
            <div class="bg-red-50 text-red-700 p-4 rounded-lg text-sm mb-6 border border-red-100">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Global Image Modal -->
    <div x-data x-show="$store.imageModal.show" 
         class="fixed inset-0 flex items-center justify-center p-4"
         style="background-color: rgba(0, 0, 0, 0.85); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); z-index: 9999999;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="$store.imageModal.close()"
         @keydown.escape.window="$store.imageModal.close()"
         x-cloak>
        <div class="relative max-w-5xl w-full h-full flex items-center justify-center" @click.stop>
            <button @click="$store.imageModal.close()" class="absolute top-0 right-0 m-4 text-white hover:text-gray-300 transition-colors z-10">
                <i data-lucide="x" class="w-8 h-8"></i>
            </button>
            <img :src="$store.imageModal.url" class="max-w-full max-h-full object-contain shadow-2xl rounded-lg" alt="Enlarged product image">
        </div>
    </div>

    @stack('scripts')
    <script>
        lucide.createIcons();

        // Sidebar Scroll Persistence
        const sidebarNav = document.querySelector('aside nav');
        if (sidebarNav) {
            // Restore scroll position
            const scrollPos = localStorage.getItem('admin_sidebar_scroll');
            if (scrollPos) {
                sidebarNav.scrollTop = scrollPos;
            }

            // Save scroll position on link click
            sidebarNav.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    localStorage.setItem('admin_sidebar_scroll', sidebarNav.scrollTop);
                });
            });
            
            // Also save on manual scroll (optional but safer)
            sidebarNav.addEventListener('scroll', () => {
                localStorage.setItem('admin_sidebar_scroll', sidebarNav.scrollTop);
            });
        }
    </script>
</body>
</html>
