@php
    $cart = session()->get('cart', []);
    $cartCount = collect($cart)->sum(fn($i) => is_array($i) ? ($i['quantity'] ?? 0) : $i);
@endphp

<script>
    function sideCartData() {
        return {
            loading: false,
            items: [],
            subtotal: 0,
            itemCount: {{ $cartCount }},
            isOpen: false,
            isFull: false,
            showSuccess: false,
            
            init() {
                console.log('SIDE CART LOADED V3');
                this.fetchCart();
                window.sideCart = this;
            },
            
            openCart(full = false) {
                this.isFull = !!full;
                this.showSuccess = true;
                setTimeout(() => this.showSuccess = false, 3000);
                this.fetchCart();
                this.isOpen = true;
            },
            
            fetchCart() {
                if (this.loading) return;
                this.loading = true;
                fetch('/cart/details')
                    .then(res => res.json())
                    .then(data => {
                        this.updateFromData(data);
                        this.loading = false;
                    })
                    .catch(err => {
                        console.error('Error fetching cart', err);
                        this.loading = false;
                    });
            },
            
            updateFromData(data) {
                // Ensure data has the expected structure
                this.items = data.items || [];
                this.subtotal = data.subtotal || 0;
                this.itemCount = data.count || 0;
                if(typeof refreshCartBadges === 'function') refreshCartBadges(this.itemCount);
            },
            
            updateQty(cartKey, qty) {
                if (qty < 1) return;
                const item = this.items.find(i => i.cart_key === cartKey);
                if (item) item.updating = true;
                
                fetch('/cart/update', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ cart_key: cartKey, quantity: qty })
                })
                .then(r => r.json())
                .then(data => {
                    this.updateFromData(data);
                });
            },
            
            removeItem(cartKey) {
                const item = this.items.find(i => i.cart_key === cartKey);
                if (item) item.updating = true;
                
                fetch(`/cart/remove/${cartKey}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    this.updateFromData(data);
                    window.dispatchEvent(new CustomEvent('cart-updated-global'));
                });
            }
        };
    }
</script>

<div 
    x-data="sideCartData()" 
    x-show="isOpen"
    x-cloak
    @open-cart.window="openCart()"
    @cart-updated.window="fetchCart()"
    @keydown.escape.window="isOpen = false"
    class="fixed inset-0 flex items-center justify-end"
    style="z-index: 999999999; display: none;"
    id="side-cart-component"
>
    <style>
        @media (max-width: 639px) {
            #side-cart-overlay {
                background-color: rgba(0,0,0,0.1) !important;
                backdrop-filter: none !important;
            }
            #side-cart-drawer {
                width: 86% !important;
                box-shadow: -10px 0 30px rgba(0,0,0,0.1) !important;
            }
            #side-cart-drawer.is-full {
                width: 100% !important;
                box-shadow: none !important;
            }
        }
        @media (min-width: 640px) {
            #side-cart-overlay {
                background-color: rgba(0,0,0,0.6) !important;
            }
            #side-cart-drawer {
                width: 450px !important;
                box-shadow: -20px 0 50px rgba(0,0,0,0.2) !important;
            }
        }
    </style>

    <div 
        id="side-cart-overlay"
        @click="isOpen = false"
        x-show="isOpen"
        x-transition:enter="transition opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition opacity ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 pointer-events-auto"
    ></div>

    <!-- Drawer -->
    <div 
        id="side-cart-drawer"
        :class="{'is-full': isFull}"
        x-show="isOpen"
        x-transition:enter="transform transition ease-in-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in-out duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="relative h-full flex flex-col z-10"
        style="background-color: #FFFFFF !important;"
    >
        <!-- Header -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 flex-shrink-0">
            <h2 class="text-lg font-bold text-gray-900">Shopping Cart (<span x-text="itemCount"></span>)</h2>
            <button onclick="toggleSideCart(false)" class="p-2 -mr-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Success Message -->
        <div x-show="showSuccess" x-transition.opacity class="px-5 py-3 bg-green-50 border-b border-green-100" style="display: none;">
            <div class="flex items-center gap-2 text-green-700 text-sm font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Product added to cart successfully
            </div>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-5 scrollbar-thin">
            <template x-if="loading">
                <div class="flex justify-center items-center h-32">
                    <span class="loader border-[#45b86f]"></span>
                </div>
            </template>

            <template x-if="!loading && items.length === 0">
                <div class="text-center py-10">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-10H5.4m0 0L7 13m0 0l-1.4 5.6M7 13l-1.4 5.6m0 0H18M9 21a1 1 0 100-2 1 1 0 000 2zm9 0a1 1 0 100-2 1 1 0 000 2z"/></svg>
                    </div>
                    <p class="text-gray-500 font-medium">Your cart is empty</p>
                    <button onclick="toggleSideCart(false)" class="mt-4 text-[#45b86f] font-medium hover:underline">Continue Shopping</button>
                </div>
            </template>

            <template x-if="!loading && items.length > 0">
                <div class="space-y-4">
                    <template x-for="item in items" :key="item.cart_key">
                        <div class="flex gap-4 border-b border-gray-100 pb-4 last:border-0 last:pb-0 relative">
                            <!-- Overlay loader for updating item -->
                            <div x-show="item.updating" class="absolute inset-0 bg-white/60 z-10 flex items-center justify-center">
                                <span class="loader border-[#45b86f] w-5 h-5 border-2"></span>
                            </div>
 
                            <img :src="item.image" :alt="item.name" class="w-20 h-20 object-cover rounded-lg border border-gray-100">
                            
                            <div class="flex-1 flex flex-col justify-between">
                                <div class="mb-2">
                                    <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 mb-1" x-text="item.name"></h3>
                                    
                                    <div class="flex gap-2 mb-1">
                                        <template x-if="item.size">
                                            <span class="text-[9px] bg-gray-100 px-1.5 py-0.5 rounded uppercase font-bold text-gray-500" x-text="'Size: ' + item.size"></span>
                                        </template>
                                        <template x-if="item.color">
                                            <span class="text-[9px] bg-gray-100 px-1.5 py-0.5 rounded uppercase font-bold text-gray-500 flex items-center gap-1.5">
                                                <template x-if="item.color.startsWith('#')">
                                                    <span :style="'background-color: ' + item.color" class="w-2.5 h-2.5 rounded-full border border-gray-300"></span>
                                                </template>
                                                <span x-text="'Color: ' + item.color"></span>
                                            </span>
                                        </template>
                                    </div>

                                    <p class="text-[#45b86f] font-bold text-sm" x-text="`Tk ${item.price.toLocaleString()}`"></p>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden h-8">
                                        <button @click="updateQty(item.cart_key, item.qty - 1)" class="w-8 h-full flex items-center justify-center bg-gray-50 hover:bg-gray-100 text-gray-600 transition">-</button>
                                        <input type="number" readonly :value="item.qty" class="w-10 h-full text-center text-sm font-semibold text-gray-800 focus:outline-none border-x border-gray-200 select-none">
                                        <button @click="updateQty(item.cart_key, item.qty + 1)" class="w-8 h-full flex items-center justify-center bg-gray-50 hover:bg-gray-100 text-gray-600 transition">+</button>
                                    </div>
                                    <button @click="removeItem(item.cart_key)" class="text-gray-400 hover:text-red-500 text-xs font-semibold underline transition">Remove</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-100 p-5 bg-gray-50 mt-auto flex-shrink-0" x-show="!loading && items.length > 0">
            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-600 font-medium">Subtotal</span>
                <span class="text-lg font-bold text-gray-900" x-text="`Tk ${subtotal.toLocaleString()}`"></span>
            </div>
            
            <a href="/checkout" 
               class="w-full py-4 rounded-xl font-bold flex items-center justify-center gap-2 mb-3 shadow-2xl transition-all duration-300 hover:scale-[1.02] active:scale-95"
               style="background-color: #000000 !important; color: #FFFFFF !important;">
                Proceed to Checkout
            </a>
        </div>
    </div>
</div>

<script>
    window.toggleSideCart = function(open, full = false) {
        if (!window.sideCart) {
            // Fallback for direct DOM manipulation if Alpine component is not ready
            const container = document.getElementById('side-cart-component');
            if (!container) return;
            if (open === true) {
                container.classList.remove('hidden');
                container.classList.add('flex');
            } else {
                container.classList.add('hidden');
                container.classList.remove('flex');
            }
            return;
        }

        if (open === true) {
            window.sideCart.isOpen = true;
            window.sideCart.isFull = !!full;
            window.sideCart.fetchCart();
        } else if (open === false) {
            window.sideCart.isOpen = false;
        } else {
            window.sideCart.isOpen = !window.sideCart.isOpen;
            if (window.sideCart.isOpen) {
                window.sideCart.isFull = !!full;
                window.sideCart.fetchCart();
            }
        }
    };
</script>
