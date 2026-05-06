@extends('layouts.app')

@section('title', 'My Orders - SmartLookBD')

@section('content')
<div class="bg-[#F9FAFB] min-h-screen py-6 md:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        
        <!-- Header Section -->
        <div class="mb-8 md:mb-12">
            <h1 class="text-3xl md:text-5xl font-black text-gray-900 tracking-tighter mb-4">My Orders</h1>
            <nav class="flex items-center gap-2 text-xs md:text-sm font-bold text-gray-400 uppercase tracking-widest">
                <a href="{{ route('home') }}" class="hover:text-orange-600 transition-colors">Home</a>
                <span class="text-gray-300">/</span>
                <a href="{{ route('account.dashboard') }}" class="hover:text-orange-600 transition-colors">Profile</a>
                <span class="text-gray-300">/</span>
                <span class="text-orange-600">Orders</span>
            </nav>
        </div>

        <div class="space-y-4">
            @forelse($orders as $order)
            <div class="bg-white rounded-2xl md:rounded-[2rem] border border-gray-100 shadow-sm p-6 md:p-10 hover:shadow-md transition-shadow duration-300">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <!-- Order Basic Info -->
                    <div class="flex items-start gap-5">
                        <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400 flex-shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <h3 class="font-black text-gray-900 text-lg md:text-xl tracking-tight">Order {{ $order->order_number }}</h3>
                                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                    {{ $order->status === 'pending' ? 'bg-orange-50 text-orange-500' : '' }}
                                    {{ $order->status === 'processing' ? 'bg-blue-50 text-blue-500' : '' }}
                                    {{ $order->status === 'shipped' ? 'bg-purple-50 text-purple-500' : '' }}
                                    {{ $order->status === 'delivered' ? 'bg-green-50 text-green-500' : '' }}
                                    {{ $order->status === 'cancelled' ? 'bg-red-50 text-red-500' : '' }}">
                                    {{ $order->status }}
                                </span>
                            </div>
                            <p class="text-xs md:text-sm text-gray-500 font-bold uppercase tracking-widest">{{ $order->created_at->format('M d, Y') }} • {{ $order->items->count() }} Items</p>
                        </div>
                    </div>

                    <!-- Price & Action -->
                    <div class="w-full md:w-auto flex items-center justify-between md:justify-end gap-10 pt-6 md:pt-0 border-t md:border-t-0 border-gray-50">
                        <div class="text-left md:text-right">
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1">Total Amount</p>
                            <p class="text-2xl md:text-3xl font-black text-gray-900 tracking-tighter">৳{{ number_format($order->total) }}</p>
                        </div>
                        <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center gap-3 px-6 py-3.5 md:px-8 md:py-4 rounded-xl md:rounded-2xl bg-gray-900 text-white font-black text-xs md:text-sm tracking-tight hover:bg-orange-600 transition-all duration-300 shadow-lg shadow-gray-100">
                            Details
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-20 text-center">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mx-auto mb-8">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <h2 class="text-2xl font-black text-gray-900 mb-4 tracking-tight">No orders yet</h2>
                <p class="text-gray-500 font-medium mb-10 max-w-xs mx-auto">It seems you haven't placed any orders yet. Start exploring our latest collections.</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-3 px-10 py-4 rounded-full font-black text-white text-base transition-all duration-300 hover:scale-105 active:scale-95 hover:shadow-[0_15px_30px_rgb(255,106,0,0.4)]" style="background: linear-gradient(135deg, #FF6A00 0%, #FF4500 100%); box-shadow: 0 8px 25px rgba(255, 106, 0, 0.25);">
                    Discover Products
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </a>
            </div>
            @endforelse
        </div>

        @if($orders->hasPages())
        <div class="mt-12 px-2">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
