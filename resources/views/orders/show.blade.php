@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' - SmartLookBD')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen py-8 md:py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        
        <!-- Premium Order Header -->
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.02)] p-6 md:p-8 mb-8" style="border: 2px solid #E5E7EB;">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <a href="{{ route('orders.index') }}" class="w-11 h-11 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-orange-600 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h1 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight">Order {{ $order->order_number }}</h1>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                {{ $order->status === 'pending' ? 'bg-orange-50 text-orange-500' : '' }}
                                {{ $order->status === 'processing' ? 'bg-blue-50 text-blue-500' : '' }}
                                {{ $order->status === 'shipped' ? 'bg-purple-50 text-purple-500' : '' }}
                                {{ $order->status === 'delivered' ? 'bg-green-50 text-green-500' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-red-50 text-red-500' : '' }}">
                                {{ $order->status }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Placed on {{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            <!-- Left Column: Items & Payment Summary -->
            <div class="md:col-span-8 space-y-8">
                <!-- Ordered Items -->
                <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.02)] overflow-hidden" style="border: 2px solid #E5E7EB;">
                    <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                        <h3 class="font-black text-gray-900 text-sm md:text-base uppercase tracking-widest">Order Items</h3>
                    </div>
                    <div class="p-6 md:p-8 divide-y divide-gray-50">
                        @foreach($order->items as $item)
                        <div class="py-6 first:pt-0 last:pb-0 flex gap-5">
                            <div class="w-20 h-20 md:w-24 md:h-24 rounded-2xl bg-gray-50 border border-gray-100 overflow-hidden flex-shrink-0">
                                @php
                                    $imgSource = $item->product_image ?: ($item->product ? $item->product->thumbnail : '');
                                    $thumbnailUrl = (Str::startsWith($imgSource, ['http://', 'https://']) 
                                        ? $imgSource 
                                        : asset('storage/' . $imgSource));
                                @endphp
                                <img src="{{ $thumbnailUrl }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                <h4 class="font-bold text-gray-900 text-sm md:text-base truncate mb-1">{{ $item->product_name }}</h4>
                                <p class="text-[11px] md:text-xs text-gray-400 font-bold uppercase tracking-widest">{{ $item->quantity }} × ৳{{ number_format($item->price) }}</p>
                                @if($item->size || $item->color)
                                <div class="flex gap-2 mt-2">
                                    @if($item->size)
                                    <span class="text-[9px] bg-gray-50 text-gray-500 px-2 py-0.5 rounded font-black uppercase tracking-widest">Size: {{ $item->size }}</span>
                                    @endif
                                    @if($item->color)
                                    <span class="text-[9px] bg-gray-50 text-gray-500 px-2 py-0.5 rounded font-black uppercase tracking-widest">Color: {{ $item->color }}</span>
                                    @endif
                                </div>
                                @endif
                                <div class="mt-3 font-black text-orange-600 text-base md:text-lg tracking-tight">৳{{ number_format($item->quantity * $item->price) }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.02)] p-6 md:p-8" style="border: 2px solid #E5E7EB;">
                    <h3 class="font-black text-gray-900 text-sm md:text-base uppercase tracking-widest mb-6">Payment Summary</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between text-xs md:text-sm text-gray-500 font-bold uppercase tracking-widest">
                            <span>Subtotal</span>
                            <span class="text-gray-900 font-black">৳{{ number_format($order->subtotal) }}</span>
                        </div>
                        <div class="flex justify-between text-xs md:text-sm text-gray-500 font-bold uppercase tracking-widest">
                            <span>Shipping</span>
                            <span class="text-gray-900 font-black">৳{{ number_format($order->shipping) }}</span>
                        </div>
                        @if($order->discount > 0)
                        <div class="flex justify-between text-xs md:text-sm text-red-500 font-bold uppercase tracking-widest">
                            <span>Discount</span>
                            <span class="font-black">−৳{{ number_format($order->discount) }}</span>
                        </div>
                        @endif
                        <div class="pt-6 mt-6 border-t border-gray-100 flex justify-between items-center">
                            <span class="font-black text-gray-900 uppercase tracking-widest text-[11px]">Total Amount</span>
                            <span class="text-2xl md:text-4xl font-black text-orange-600 tracking-tighter">৳{{ number_format($order->total) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Shipping Details -->
            <div class="md:col-span-4 space-y-8">
                <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.02)] p-6 md:p-8" style="border: 2px solid #E5E7EB;">
                    <h3 class="font-black text-gray-900 text-sm md:text-base uppercase tracking-widest mb-6">Delivery info</h3>
                    <div class="space-y-5">
                        <div>
                            <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">Recipient</p>
                            <p class="font-bold text-gray-900 text-sm">{{ $order->name }}</p>
                            <p class="text-xs text-gray-500 font-medium mt-0.5">{{ $order->phone }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">Address</p>
                            <p class="text-xs text-gray-500 font-medium leading-relaxed">{{ $order->address }}</p>
                        </div>
                        <div class="pt-5 border-t border-gray-50">
                            <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2">Payment Status</p>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $order->payment_status === 'paid' ? 'bg-green-500' : 'bg-orange-500' }}"></span>
                                <span class="text-xs font-black uppercase tracking-widest {{ $order->payment_status === 'paid' ? 'text-green-500' : 'text-orange-500' }}">{{ $order->payment_status }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl p-6 md:p-8 text-white" style="background-color: #000000 !important; color: #ffffff !important; border: 2px solid #000000;">
                    <h3 class="font-black text-[10px] uppercase tracking-widest text-gray-400 mb-4" style="color: #9CA3AF !important;">Payment Method</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <span class="font-black uppercase tracking-widest text-sm">{{ $order->payment_method }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
