@extends('layouts.app')
@section('title', 'Shopping Cart - SmartLookBD')
@section('body_bg', '#f9fafb')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <h1 class="text-2xl sm:text-3xl font-black text-gray-900 mb-8">Shopping Cart</h1>

    @if(count($cartItems) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-4">
            @foreach($cartItems as $item)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex gap-4 items-center" id="cart-item-{{ $item['cart_key'] }}">
                <a href="{{ route('products.show', $item['product']->slug) }}" class="flex-shrink-0">
                    <img src="{{ $item['product']->getColorImageUrl($item['color'] ?? '') }}" alt="{{ $item['product']->name }}" class="w-20 h-20 rounded-xl object-cover">
                </a>
                <div class="flex-1 min-w-0">
                    <a href="{{ route('products.show', $item['product']->slug) }}" class="font-semibold text-gray-900 text-sm hover:text-[#45b86f] transition line-clamp-2">{{ $item['product']->name }}</a>
                    
                    @if($item['size'] || $item['color'])
                    <div class="flex gap-2 mt-1">
                        @if($item['size'])
                        <span class="text-[10px] bg-gray-50 text-gray-500 px-2 py-0.5 rounded font-bold uppercase select-none">Size: {{ $item['size'] }}</span>
                        @endif
                        @if($item['color'])
                        <span class="text-[10px] bg-gray-50 text-gray-500 px-2 py-0.5 rounded font-bold uppercase select-none">Color: {{ $item['color'] }}</span>
                        @endif
                    </div>
                    @endif

                    <p class="text-xs text-gray-400 mt-1">{{ $item['product']->brand }}</p>
                    <p class="text-[#45b86f] font-black mt-1">Tk{{ number_format($item['product']->effective_price) }}</p>
                </div>
                <div class="flex flex-col items-end gap-3">
                    <!-- Quantity -->
                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                        <button onclick="updateCartQty('{{ $item['cart_key'] }}', {{ $item['quantity'] - 1 }})" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 text-gray-600 font-bold">−</button>
                        <span class="w-10 text-center text-sm font-semibold">{{ $item['quantity'] }}</span>
                        <button onclick="updateCartQty('{{ $item['cart_key'] }}', {{ $item['quantity'] + 1 }})" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 text-gray-600 font-bold">+</button>
                    </div>
                    <p class="font-black text-gray-900 text-sm">Tk{{ number_format($item['product']->effective_price * $item['quantity']) }}</p>
                    <button onclick="removeCartItem('{{ $item['cart_key'] }}')" class="text-xs text-red-400 hover:text-red-600 transition flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Remove
                    </button>
                </div>
            </div>
            @endforeach

            <!-- Promo Code -->
            <div x-data="{ code: '{{ session('promo_code', '') }}', applied: {{ session('promo_code') ? 'true' : 'false' }}, msg: '{{ session('promo_message', '') }}' }" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-semibold text-gray-900 mb-3">Promo Code</h3>
                <form action="{{ route('cart.promo') }}" method="POST" class="flex gap-3">
                    @csrf
                    <input type="text" name="promo_code" x-model="code" placeholder="Enter promo code (e.g. WELCOME10)" class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-400 focus:outline-none">
                    <button type="submit" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-semibold">Apply</button>
                </form>
                @if(session('promo_message'))
                <p class="text-sm mt-2 {{ session('promo_discount') ? 'text-green-600' : 'text-red-500' }}">
                    {{ session('promo_message') }}
                </p>
                @endif
                <p class="text-xs text-gray-400 mt-2">Try: WELCOME10 · SAVE20 · FLASH30 · FLAT500</p>
            </div>
        </div>

        <!-- Order Summary -->
        <div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-24">
                <h3 class="font-bold text-gray-900 text-base mb-5">Order Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal ({{ array_sum(array_column($cartItems, 'quantity')) }} items)</span>
                        <span>Tk{{ number_format($subtotal) }}</span>
                    </div>
                    @if(session('promo_discount') > 0)
                    <div class="flex justify-between text-sm text-green-600">
                        <span>Discount ({{ session('promo_code') }})</span>
                        <span>−Tk{{ number_format(session('promo_discount')) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Shipping</span>
                        <span class="{{ $subtotal >= 1000 ? 'text-green-600' : '' }}">
                            {{ $subtotal >= 1000 ? 'FREE' : 'Tk60' }}
                        </span>
                    </div>
                    @if($subtotal < 1000)
                    <p class="text-xs text-[#45b86f] bg-green-50 rounded-lg px-3 py-2">Add Tk{{ number_format(1000 - $subtotal) }} more for free shipping!</p>
                    @endif
                    <hr class="border-gray-100">
                    <div class="flex justify-between font-black text-gray-900 text-lg">
                        <span>Total</span>
                        <span class="text-[#45b86f]">Tk{{ number_format($total) }}</span>
                    </div>
                </div>
                <a href="{{ route('checkout.index') }}" 
                   class="w-full py-4 rounded-2xl text-sm font-bold flex items-center justify-center gap-2 mt-6 shadow-2xl transition-all duration-300 hover:scale-[1.02] active:scale-95 text-white"
                   style="background-color: #45b86f !important; color: #FFFFFF !important;">
                    Proceed to Checkout
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('products.index') }}" class="block text-center text-sm text-gray-500 hover:text-[#45b86f] mt-3 transition">← Continue Shopping</a>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-24">
        <div class="text-8xl mb-5">🛒</div>
        <h2 class="text-2xl font-black text-gray-900 mb-3">Your cart is empty</h2>
        <p class="text-gray-500 mb-8">Looks like you haven't added any products yet!</p>
        <a href="{{ route('products.index') }}" class="btn-primary inline-flex items-center gap-2 px-8 py-4 rounded-2xl font-semibold text-base shadow-lg">
            Start Shopping <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
    @endif
</div>
@endsection
@push('scripts')
<script>
function updateCartQty(cartKey, qty) {
    if (qty < 1) { removeCartItem(cartKey); return; }
    fetch('/cart/update', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' },
        body: JSON.stringify({ cart_key: cartKey, quantity: qty })
    }).then(r => r.json()).then(d => { if(d.items) location.reload(); });
}
function removeCartItem(cartKey) {
    fetch('/cart/remove/' + cartKey, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    }).then(r => r.json()).then(d => { if(d.items) location.reload(); });
}
</script>
@endpush
