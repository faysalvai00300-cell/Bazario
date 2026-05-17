@extends('layouts.app')
@section('title', 'Order Confirmed - Bazario')
@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 pt-4 pb-12 sm:py-12 text-center">
    <div class="bg-white shadow-xl border border-green-100 p-8 sm:p-12 transition-all duration-300" style="border-radius: 9px;" data-aos="fade-up">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 mb-2">Order Confirmed! 🎉</h1>
        <p class="text-gray-500 mb-6">Thank you for your order. We'll process it right away.</p>

        <div class="bg-gray-50 p-5 mb-6 text-left space-y-3" style="border-radius: 9px;">
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-500 font-medium">Order Number</span>
                <div class="flex items-center gap-2">
                    <span id="orderNumber" class="font-black text-gray-900 tracking-wider transition-all duration-300">{{ $order->order_number }}</span>
                    <button onclick="copyOrderNumber()" class="p-1.5 hover:bg-gray-200 rounded-lg transition-all active:scale-90 group" title="Copy Order Number">
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-[#FF6A00]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m-3 8h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </button>
                    <span id="copyIndicator" class="absolute right-12 opacity-0 text-[10px] bg-slate-900 text-white px-2 py-0.5 rounded transition-opacity duration-300">Copied!</span>
                </div>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Payment Method</span>
                <span class="font-semibold text-gray-900 capitalize">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : $order->payment_method }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Total Amount</span>
                <span class="font-black text-[#FF6A00] text-base">Tk{{ number_format($order->total) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Delivery To</span>
                <span class="font-medium text-gray-900 text-right max-w-xs">{{ $order->name }}, {{ $order->city }}</span>
            </div>
        </div>

        <div class="bg-[#FFF5F1] border border-orange-100 p-4 mb-8 text-left" style="border-radius: 9px;">
            <p class="text-sm text-orange-700 font-medium">📦 Your order will be delivered within <strong>3-5 business days</strong>.</p>
            <p class="text-xs text-orange-500 mt-1">You'll receive an SMS update when your order is shipped.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            @auth
            <a href="{{ route('orders.show', $order->id) }}" class="flex-1 btn-primary py-3.5 text-sm font-bold flex items-center justify-center gap-2" style="border-radius: 9px;">
                Track My Order
            </a>
            @endauth
            <a href="{{ route('home') }}" class="flex-1 btn-outline py-3.5 text-sm font-bold flex items-center justify-center gap-2" style="border-radius: 9px;">
                Continue Shopping
            </a>
        </div>

        <!-- Home Action -->
        <div class="mt-8 border-t border-gray-100 pt-8">
            <p class="text-[11px] text-gray-500 font-bold uppercase tracking-widest">Thank you for choosing Bazario.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Copy Order Number Logic
    function copyOrderNumber() {
        const orderNumber = document.getElementById('orderNumber').innerText;
        const indicator = document.getElementById('copyIndicator');
        const numberSpan = document.getElementById('orderNumber');
        
        navigator.clipboard.writeText(orderNumber).then(() => {
            // Visual feedback
            indicator.style.opacity = '1';
            numberSpan.classList.add('text-orange-500', 'scale-105');
            
            setTimeout(() => {
                indicator.style.opacity = '0';
                numberSpan.classList.remove('text-orange-500', 'scale-105');
            }, 2000);
        });
    }


</script>

@if(isset($siteSettings) && $siteSettings->facebook_pixel_id)
<script>
    fbq('track', 'Purchase', {
        value: {{ $order->total }},
        currency: 'BDT',
        content_ids: [{{ $order->items->pluck('product_id')->implode(',') }}],
        content_type: 'product'
    }, { eventID: 'purchase_{{ $order->id }}' });
</script>
@endif

@if(isset($siteSettings) && $siteSettings->tiktok_pixel_id)
<script>
    ttq.track('CompletePayment', {
        value: {{ $order->total }},
        currency: 'BDT',
        content_id: '{{ $order->items->pluck('product_id')->implode(',') }}',
        content_type: 'product'
    });
</script>
@endif
@endpush
