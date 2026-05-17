@extends('layouts.app')
@section('title', 'Track Your Order - Bazario')
@section('content')
<div class="max-w-5xl mx-auto px-4 py-8 sm:py-12">
    <div class="max-w-3xl mx-auto text-center mb-8">
        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 text-orange-500 shadow-sm border border-orange-100">
            <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
        </div>
        <h1 class="text-2xl sm:text-4xl font-black text-slate-900 mb-2 sm:mb-4 tracking-tight">Track Your Order</h1>
        <p class="text-gray-500 text-sm sm:text-base max-w-lg mx-auto">অর্ডারের সঠিক অবস্থা জানতে আপনার অর্ডার নম্বরটি নিচে লিখুন।</p>
    </div>
    
    <!-- Tracking Form -->
    <div class="max-w-md mx-auto relative mb-10 sm:mb-16">
        <form action="{{ route('pages.track-order') }}" method="GET">
            <div class="relative group">
                <input type="text" name="order_number" value="{{ request('order_number') }}" 
                    placeholder="Tracking Number (e.g. 10001)" 
                    class="w-full border-2 border-gray-200 rounded-2xl px-5 py-3 sm:py-4 text-sm sm:text-base focus:ring-4 focus:ring-orange-100 focus:border-orange-500 focus:outline-none transition-all pr-32 shadow-sm bg-white placeholder-gray-400 font-semibold text-slate-800">
                <button type="submit" class="absolute right-2 top-2 bottom-2 bg-orange-500 hover:bg-orange-600 text-white px-5 sm:px-6 rounded-xl font-bold text-sm transition-all active:scale-95 shadow-md shadow-orange-200">
                    Track Now
                </button>
            </div>
        </form>
    </div>

    @if($order)
    <!-- Tracking Result Display -->
    <div class="max-w-4xl mx-auto px-0 sm:px-4">
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-gray-100 overflow-hidden mb-10 sm:mb-16">
            <!-- Order Header Summary -->
            <div class="bg-slate-900 p-6 sm:p-10 text-white relative overflow-hidden">
                <!-- Decorative background circles -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-orange-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-500/10 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 bg-orange-500/20 px-3 py-1 rounded-full border border-orange-500/30 mb-3">
                            <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                            <p class="text-orange-200 text-xs font-bold uppercase tracking-widest">Tracking Active</p>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black mb-1">Order {{ $order->order_number }}</h2>
                        <p class="text-white/70 text-xs sm:text-sm font-medium">Placed on {{ $order->created_at->format('M d, Y') }} at {{ $order->created_at->format('h:i A') }}</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20 w-full sm:w-auto flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center text-white text-2xl shadow-lg shadow-orange-500/30">
                            📦
                        </div>
                        <div>
                            <p class="text-xs text-white/50 font-bold uppercase tracking-wider mb-0.5">Order Status</p>
                            <p class="text-lg sm:text-xl font-black text-white capitalize leading-none">{{ $order->status }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-10">
                @if($order->status === 'cancelled')
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner">❌</div>
                    <h3 class="text-2xl font-black text-slate-900 mb-2">Order Cancelled</h3>
                    <p class="text-gray-500 max-w-md mx-auto text-sm">আমরা দুঃখিত, আপনার অর্ডারটি বাতিল করা হয়েছে। এটি সাধারণত স্টক শেষ হয়ে গেলে বা কাস্টমার অনুরোধে হয়ে থাকে।</p>
                </div>
                @else
                <!-- Visual Steps -->
                @php
                    $statuses = [
                        [
                            'id' => 'pending', 'label' => 'Pending', 'desc' => 'Awaiting confirmation',
                            'icon' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>'
                        ],
                        [
                            'id' => 'confirmed', 'label' => 'Confirmed', 'desc' => 'Order verified',
                            'icon' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                        ],
                        [
                            'id' => 'processing', 'label' => 'Processing', 'desc' => 'Packaging goods',
                            'icon' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>'
                        ],
                        [
                            'id' => 'shipped', 'label' => 'Shipped', 'desc' => 'Out for delivery',
                            'icon' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>'
                        ],
                        [
                            'id' => 'delivered', 'label' => 'Delivered', 'desc' => 'Successfully received',
                            'icon' => '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>'
                        ],
                    ];
                    
                    $currentIdx = 0;
                    foreach($statuses as $index => $s) {
                        if($s['id'] === $order->status) {
                            $currentIdx = $index;
                            break;
                        }
                    }
                @endphp

                <!-- Progress Bar (Desktop Refined) -->
                <div class="hidden md:block relative py-16 px-12">
                    <!-- Base background line -->
                    <div class="absolute top-24 left-24 right-24 h-1 bg-gray-100 rounded-full z-0"></div>
                    <!-- Active/Completed progress line -->
                    <div class="absolute top-24 left-24 h-1 bg-orange-500 rounded-full z-10 transition-all duration-1000 shadow-[0_0_10px_rgba(249,115,22,0.5)]" style="width:{{ ($currentIdx / (count($statuses) - 1)) * 100 }}%"></div>
                    
                    <div class="relative flex justify-between z-20">
                        @foreach($statuses as $index => $step)
                        <div class="flex flex-col items-center w-24">
                            <!-- Circle/Icon Container -->
                            <div class="relative mb-6">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center transition-all duration-700 shadow-sm
                                    {{ $index <= $currentIdx ? 'bg-orange-500 text-white shadow-orange-200' : 'bg-white border-2 border-gray-100 text-gray-300' }}
                                    {{ $index == $currentIdx ? 'ring-4 ring-orange-100 scale-110' : '' }}">
                                    
                                    @if($index <= $currentIdx)
                                        <!-- Checkmark for completed/current steps -->
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        {!! $step['icon'] !!}
                                    @endif
                                </div>

                                <!-- Tick Badge -->
                                @if($index <= $currentIdx)
                                <div class="absolute -top-1 -right-1 bg-green-500 text-white w-6 h-6 rounded-full border-2 border-white flex items-center justify-center text-[10px] shadow-sm">
                                    ✓
                                </div>
                                @endif
                            </div>

                            <!-- Text Labels -->
                            <div class="text-center">
                                <h4 class="text-[11px] font-black uppercase tracking-widest leading-none mb-2 
                                    {{ $index <= $currentIdx ? 'text-slate-900' : 'text-gray-400' }}">
                                    {{ $step['label'] }}
                                </h4>
                                <p class="text-[9px] text-gray-400 font-medium leading-tight px-1 min-h-[18px]">
                                    {{ $step['desc'] }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Progress List (Mobile Refined) -->
                <div class="md:hidden py-4 px-1">
                    @foreach($statuses as $index => $step)
                    <div class="relative flex items-start gap-4 pb-8">
                        <!-- Line -->
                        @if(!$loop->last)
                        <div class="absolute top-10 left-[1.35rem] bottom-0 w-1 {{ $index < $currentIdx ? 'bg-orange-500' : 'bg-gray-100' }} transition-colors duration-500 rounded-full"></div>
                        @endif
                        
                        <!-- Icon Circle -->
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 z-10 transition-all duration-500 {{ $index <= $currentIdx ? 'bg-orange-500 text-white shadow-md shadow-orange-200 scale-105' : 'bg-gray-50 border border-gray-200 text-gray-400' }}">
                            {!! $step['icon'] !!}
                        </div>

                        <!-- Content Card -->
                        <div class="pt-1 flex-1">
                            <div class="flex items-center justify-between mb-1.5">
                                <h4 class="text-sm font-black {{ $index <= $currentIdx ? 'text-slate-900' : 'text-gray-400' }} uppercase tracking-wider">{{ $step['label'] }}</h4>
                                 @if($index <= $currentIdx)
                                    <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center text-white text-[10px] shadow-sm">✓</div>
                                @endif
                            </div>
                            <p class="text-xs font-semibold {{ $index <= $currentIdx ? 'text-gray-600' : 'text-gray-400' }} leading-relaxed bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                                {{ $step['desc'] }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Order Items (Filling Space) -->
                <div class="mt-8 sm:mt-16 border-t border-dashed border-gray-200 pt-8 sm:pt-10">
                    <h4 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-3">
                        <span class="w-1.5 h-6 bg-orange-500 rounded-full"></span> 
                        Order Items
                    </h4>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                        <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-2xl border border-gray-100">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl overflow-hidden bg-white p-1 border border-gray-100">
                                <img src="{{ $item->product ? $item->product->thumbnail_url : asset('placeholder.png') }}" class="w-full h-full object-cover rounded-lg">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-bold text-gray-900 truncate">{{ $item->product_name }}</p>
                                <p class="text-[10px] sm:text-xs text-gray-500 font-medium">Qty: {{ $item->quantity }} • Tk {{ number_format($item->price) }}</p>
                            </div>
                            <div class="text-right pl-2">
                                <p class="text-sm font-black text-orange-500">Tk {{ number_format($item->price * $item->quantity) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Footer Summary (Compact & Refined) -->
                <div class="mt-8 sm:mt-10 grid grid-cols-1 md:grid-cols-2 gap-6 pt-8">
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                        <h4 class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-4">Delivery Address</h4>
                        <div class="text-sm text-gray-800 space-y-1.5">
                            <p class="font-black text-slate-900 text-base">{{ $order->name }}</p>
                            <p class="font-medium text-gray-600 line-clamp-2 text-xs sm:text-sm">{{ $order->address }}</p>
                            <p class="font-bold text-orange-500 text-xs sm:text-sm">{{ $order->city }}, {{ $order->thana }}</p>
                            <p class="text-gray-500 text-xs pt-1 flex items-center gap-1.5 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $order->phone }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="bg-slate-900 rounded-2xl p-6 text-white overflow-hidden shadow-lg relative">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-10 -mt-10 blur-2xl"></div>
                        <h4 class="text-white/40 text-[10px] font-black uppercase tracking-widest mb-4">Payment Summary</h4>
                        <div class="space-y-3 relative z-10">
                            <div class="flex justify-between text-white/70 text-xs sm:text-sm font-medium">
                                <span>Subtotal</span>
                                <span>Tk {{ number_format($order->subtotal) }}</span>
                            </div>
                            <div class="flex justify-between text-white/70 text-xs sm:text-sm font-medium">
                                <span>Delivery</span>
                                <span>Tk {{ number_format($order->shipping) }}</span>
                            </div>
                            @if($order->discount > 0)
                            <div class="flex justify-between text-orange-400 text-xs sm:text-sm font-bold">
                                <span>Discount</span>
                                <span>- Tk {{ number_format($order->discount) }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between items-end pt-4 border-t border-white/10">
                                <div>
                                    <p class="text-white font-black text-lg sm:text-xl">Tk {{ number_format($order->total) }}</p>
                                    <p class="text-white/40 text-[9px] uppercase font-bold tracking-widest mt-0.5">{{ $order->payment_method }} • {{ $order->payment_status }}</p>
                                </div>
                                <div class="bg-orange-500 text-white px-3 py-1.5 rounded-lg text-[9px] font-black shadow-md">
                                    PAID
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Support Footer -->
            <div class="bg-gray-50 py-6 px-4 sm:px-10 text-center border-t border-gray-100">
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-6">
                    <p class="text-[11px] sm:text-xs text-gray-500 font-bold">অর্ডার সংক্রান্ত সাপোর্টের জন্য কল করুন</p>
                    <a href="tel:{{ $siteSettings->contact_phone ?? '01700000000' }}" class="inline-flex items-center gap-2 sm:gap-3 bg-white text-slate-900 px-4 sm:px-5 py-2 sm:py-2.5 rounded-full border border-gray-200 shadow-sm hover:border-orange-200 transition-all">
                        <div class="w-6 h-6 sm:w-7 sm:h-7 bg-orange-100 rounded-full flex items-center justify-center text-orange-600">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <span class="text-xs sm:text-sm font-black">{{ $siteSettings->contact_phone ?? '01700000000' }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
</div>
@endsection
