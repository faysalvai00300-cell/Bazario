@extends('layouts.app')

@section('title', 'My Account - Bazario')

@section('content')
<style>
    .custom-dashboard-wrapper {
        background-color: #F4F4F6;
        min-height: 100vh;
        padding: 2rem 1rem;
    }
    .custom-dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    .custom-dashboard-layout {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    .custom-dashboard-sidebar {
        width: 100%;
    }
    .custom-dashboard-main {
        width: 100%;
    }

    @media (min-width: 1024px) {
        .custom-dashboard-layout {
            flex-direction: row;
            align-items: flex-start;
        }
        .custom-dashboard-sidebar {
            width: 320px;
            flex-shrink: 0;
        }
        .custom-dashboard-main {
            flex: 1;
            min-width: 0;
        }
    }
    
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.4s ease-out forwards;
    }
</style>

<div class="custom-dashboard-wrapper">
    <div class="custom-dashboard-container">
        
        <div class="custom-dashboard-layout">
            
            <!-- Sidebar -->
            <aside class="custom-dashboard-sidebar">
                <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100">
                    <!-- Profile Info -->
                    <div class="flex flex-col items-center text-center mb-8">
                        <div class="w-24 h-24 flex-shrink-0 aspect-square rounded-full bg-orange-50 flex items-center justify-center text-[#FF6A00] mb-4 ring-8 ring-orange-50/50 relative border-2 border-orange-100 mx-auto">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ $user->name }}</h2>
                        <p class="text-sm font-bold text-gray-400 mt-1">{{ $user->email ?? $user->phone ?? 'User' }}</p>
                    </div>

                    <!-- Navigation -->
                    <nav class="space-y-2">
                        <a href="{{ route('account.dashboard') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl bg-[#FF6A00] text-white font-black text-sm transition-all shadow-lg shadow-orange-500/20" style="background-color: #FF6A00; color: #fff;">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </a>
                        <a href="{{ route('orders.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-bold text-sm transition-all">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            My Orders
                        </a>
                        <a href="{{ route('messages.index') }}" class="flex items-center justify-between px-6 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-bold text-sm transition-all">
                            <div class="flex items-center gap-4">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                Messages
                            </div>
                            @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                                <span class="bg-[#FF6A00] text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-lg shadow-orange-500/20">{{ $unreadMessagesCount }}</span>
                            @endif
                        </a>
                        
                        <div class="pt-6 mt-6 border-t border-gray-100">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl text-[#FF3B30] hover:bg-red-50 font-bold text-sm transition-all">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout Account
                                </button>
                            </form>
                        </div>
                    </nav>
                </div>
            </aside>

            <!-- Main Content Form-like -->
            <main class="custom-dashboard-main">
                <!-- Overview Header Box -->
                <div class="bg-white rounded-3xl p-6 md:p-10 shadow-sm border border-gray-100 mb-8 flex flex-wrap md:flex-nowrap items-center justify-between gap-6" style="display: flex; width: 100%; box-sizing: border-box;">
                    <div style="flex: 1; min-width: 250px;">
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Welcome Back!</h1>
                        <p class="text-gray-500 font-medium whitespace-normal">Manage your orders and stay updated with Bazario.</p>
                    </div>
                    
                    <div class="bg-gray-50 px-8 py-5 rounded-2xl border border-gray-100 flex items-center gap-5" style="min-width: max-content;">
                        <div class="w-14 h-14 rounded-full bg-white shadow-sm flex items-center justify-center text-[#FF6A00] flex-shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Orders</p>
                            <h4 class="text-3xl font-black text-gray-900 tracking-tighter leading-none">{{ count($orders) }}</h4>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Display -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 md:px-8 py-5 md:py-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                        <h3 class="text-lg md:text-xl font-black text-gray-900 tracking-tight">Recent Orders</h3>
                        @if(count($orders) > 0)
                        <a href="{{ route('orders.index') }}" class="text-[10px] md:text-xs font-black text-[#FF6A00] hover:text-[#FF4500] transition-colors uppercase tracking-widest bg-orange-50 px-3 md:px-4 py-2 rounded-lg">View All</a>
                        @endif
                    </div>
                    
                    <div class="divide-y divide-gray-50">
                        @forelse($orders as $order)
                        <div class="p-5 md:p-8 hover:bg-gray-50/50 transition-all duration-300 flex items-center justify-between gap-3 md:gap-6 group">
                            
                            <div class="flex items-center gap-3 md:gap-6 flex-1 min-w-0">
                                <div class="w-12 h-12 md:w-16 md:h-16 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400 flex-shrink-0 group-hover:bg-white group-hover:shadow-md group-hover:border-transparent transition-all">
                                    <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-1">
                                        <h4 class="text-base md:text-lg font-black text-gray-900 tracking-tight truncate">Order #{{ $order->order_number }}</h4>
                                        <div class="flex items-center gap-1 bg-gray-50 px-2 py-0.5 md:px-2.5 md:py-1 rounded border border-gray-100 flex-shrink-0">
                                            <span class="text-[8px] md:text-[9px] font-black text-gray-400 uppercase tracking-widest">Total:</span>
                                            <span class="text-xs md:text-sm font-black text-[#FF6A00] tracking-tight">৳{{ number_format($order->total) }}</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-1.5 md:gap-3">
                                        <p class="text-[9px] md:text-xs text-gray-400 font-bold uppercase tracking-widest">{{ $order->created_at->format('M d, y') }}</p>
                                        <span class="hidden md:block w-1 h-1 rounded-full bg-gray-300"></span>
                                        <span class="inline-block px-1.5 md:px-2.5 py-0.5 md:py-1 rounded md:rounded-md text-[8px] md:text-[10px] font-black uppercase tracking-wider
                                            {{ $order->status === 'pending' ? 'bg-orange-100 text-orange-600' : '' }}
                                            {{ $order->status === 'confirmed' ? 'bg-blue-100 text-blue-600' : '' }}
                                            {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-600' : '' }}
                                            {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-600' : '' }}
                                            {{ $order->status === 'delivered' ? 'bg-green-100 text-green-600' : '' }}
                                            {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-600' : '' }}">
                                            {{ $order->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-shrink-0 ml-1 md:ml-4">
                                <a href="{{ route('orders.show', $order) }}" class="w-9 h-9 md:w-12 md:h-12 rounded-xl bg-gray-900 text-white flex items-center justify-center hover:bg-[#FF6A00] hover:shadow-lg hover:shadow-orange-500/20 hover:-translate-y-1 transition-all duration-300">
                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>

                        </div>
                        @empty
                        <div class="p-16 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mx-auto mb-6">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                            <h4 class="text-2xl font-black text-gray-900 mb-2 tracking-tight">No orders yet</h4>
                            <p class="text-gray-500 text-sm font-medium mb-8">Start exploring our premium collections to place your first order.</p>
                            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-3 px-8 py-4 rounded-full font-black text-white text-sm transition-all hover:scale-105 active:scale-95" style="background: linear-gradient(135deg, #FF6A00 0%, #FF4500 100%); box-shadow: 0 8px 25px rgba(255, 106, 0, 0.25);">
                                Discover Products
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                        @endforelse
                    </div>

                </div>
            </main>
            
        </div>
    </div>
</div>
@endsection
