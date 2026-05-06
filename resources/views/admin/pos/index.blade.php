@extends('layouts.admin')
@section('title', 'Point of Sale')
@section('content')

<div x-data="posSystem()" class="flex flex-col lg:h-[calc(100vh-130px)] lg:overflow-hidden -mt-6 -mx-4 sm:-mx-6 lg:-mx-8">
    <!-- Main Content Grid -->
    <div class="flex flex-col lg:flex-row flex-1 lg:overflow-hidden">
        
        <!-- Left Side: Invoice/Cart Area - Fixed width on desktop -->
        <div class="order-2 lg:order-1 lg:w-[380px] xl:w-[420px] lg:flex-shrink-0 bg-white dark:bg-gray-800 shadow-2xl lg:shadow-xl rounded-none flex flex-col border-t lg:border-t-0 lg:border-r border-gray-200 dark:border-gray-700 z-20 overflow-hidden">
            <!-- Customer Info -->
            <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="shopping-cart" class="w-5 h-5 text-blue-600"></i> Current Order
                    </h3>
                    <button @click="clearCart()" class="text-xs text-red-500 hover:text-red-700 font-bold uppercase tracking-tighter flex items-center gap-1">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Clear Cart
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <input type="text" x-model="customer.name" placeholder="Customer Name" class="pos-input text-xs px-3 py-2 border rounded-none dark:bg-gray-800 dark:border-gray-700">
                    <input type="text" x-model="customer.phone" placeholder="Mobile Number" class="pos-input text-xs px-3 py-2 border rounded-none dark:bg-gray-800 dark:border-gray-700">
                    <input type="text" x-model="customer.address" placeholder="Shipping Address" class="col-span-2 pos-input text-xs px-3 py-2 border rounded-none dark:bg-gray-800 dark:border-gray-700">
                </div>
            </div>

            <!-- Cart Table Area -->
            <div class="overflow-y-auto custom-scrollbar p-0">
                <table class="w-full text-left text-[11px] table-fixed border-collapse">
                    <thead class="bg-gray-100 dark:bg-gray-900 sticky top-0 uppercase text-[9px] font-black text-gray-500 tracking-wider z-10">
                        <tr>
                            <th class="px-3 py-3 w-[45%]">Item</th>
                            <th class="px-2 py-3 w-[25%] text-center">Qty</th>
                            <th class="px-2 py-3 w-[15%] text-right">Price</th>
                            <th class="px-2 py-3 w-[15%] text-right">Total</th>
                            <th class="px-2 py-3 w-[40px]"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <template x-for="(item, index) in cart" :key="item.id">
                            <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors">
                                <td class="px-3 py-3 overflow-hidden">
                                    <div class="font-bold text-gray-900 dark:text-white truncate" :title="item.name" x-text="item.name"></div>
                                    <div class="text-[9px] text-gray-400 font-bold" x-text="'SKU: ' + item.sku"></div>
                                </td>
                                <td class="px-2 py-3">
                                    <div class="flex items-center justify-center border border-gray-200 dark:border-gray-700 rounded-none overflow-hidden h-7">
                                        <button @click="updateQty(index, -1)" class="w-6 h-full bg-gray-50 hover:bg-gray-100 dark:bg-gray-900 text-gray-600 font-bold">-</button>
                                        <input type="text" :value="item.quantity" class="w-7 text-center bg-transparent border-none text-[10px] font-black p-0" readonly>
                                        <button @click="updateQty(index, 1)" class="w-6 h-full bg-gray-50 hover:bg-gray-100 dark:bg-gray-900 text-gray-600 font-bold">+</button>
                                    </div>
                                </td>
                                <td class="px-2 py-3 text-right font-medium text-gray-600 dark:text-gray-400" x-text="item.price"></td>
                                <td class="px-2 py-3 text-right font-black text-gray-900 dark:text-white" x-text="item.price * item.quantity"></td>
                                <td class="px-2 py-3 text-center">
                                    <button @click="removeFromCart(index)" class="text-red-400 hover:text-red-600">
                                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <template x-if="cart.length === 0">
                            <tr>
                                <td colspan="5" class="px-4 py-20 text-center">
                                    <div class="flex flex-col items-center gap-3 opacity-20">
                                        <i data-lucide="shopping-bag" class="w-16 h-16"></i>
                                        <p class="text-sm font-bold uppercase tracking-widest">Cart is empty</p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Order Summary Section - Sticky at bottom -->
            <div class="p-4 sm:p-5 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 sticky bottom-0 z-10 shadow-[0_-10px_20px_rgba(0,0,0,0.05)]">
                <div class="space-y-2 sm:space-y-3 mb-4 sm:mb-5">
                    <div class="flex justify-between text-xs sm:text-sm">
                        <span class="text-gray-500">Sub Total:</span>
                        <span class="font-bold text-gray-900 dark:text-white" x-text="'৳' + calculateSubtotal()"></span>
                    </div>
                    <div class="flex items-center justify-between text-xs sm:text-sm">
                        <span class="text-gray-500">Shipping:</span>
                        <input type="number" x-model.number="shipping" class="w-16 sm:w-20 text-right bg-transparent border-b border-gray-300 dark:border-gray-700 focus:outline-none focus:border-blue-500 font-bold">
                    </div>
                    <div class="flex items-center justify-between text-xs sm:text-sm">
                        <span class="text-gray-500">Discount:</span>
                        <input type="number" x-model.number="discount" class="w-16 sm:w-20 text-right bg-transparent border-b border-gray-300 dark:border-gray-700 focus:outline-none focus:border-red-500 text-red-600 font-bold">
                    </div>
                    <div class="flex justify-between text-base sm:text-lg font-black border-t border-gray-200 dark:border-gray-700 pt-3 text-gray-900 dark:text-white">
                        <span>Grand Total:</span>
                        <span class="text-blue-600" x-text="'৳' + calculateTotal()"></span>
                    </div>
                </div>
                <button @click="completeSale()" 
                        :disabled="cart.length === 0 || processing"
                        class="admin-primary-btn w-full py-4 text-white font-black uppercase tracking-widest shadow-lg shadow-orange-500/20 active:scale-[0.98] transition-all rounded-none text-xs sm:text-sm">
                    <span x-show="!processing" class="flex items-center justify-center gap-2">
                        <i data-lucide="check-circle" class="w-5 h-5"></i> Complete Sale
                    </span>
                    <span x-show="processing" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> 
                        Processing...
                    </span>
                </button>
            </div>
        </div>

        <!-- Right Side: Product Search Area -->
        <div class="order-1 lg:order-2 flex-1 flex flex-col min-h-0 bg-gray-50 dark:bg-gray-900">
            <!-- Search & Filters -->
            <div class="bg-white dark:bg-gray-800 p-4 lg:p-5 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-30 flex flex-col sm:flex-row gap-3 items-center">
                <div class="relative flex-1 w-full">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                    <input type="text" x-model="search" @input.debounce.300ms="fetchProducts()" 
                        placeholder="Search product..." 
                        class="w-full pl-11 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none rounded-none text-sm">
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                    <select x-model="category_id" @change="fetchProducts()" 
                        class="flex-1 sm:flex-none h-[42px] px-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 focus:outline-none rounded-none text-xs min-w-[120px]">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div class="hidden xl:flex items-center text-[10px] uppercase font-black text-gray-400 tracking-widest bg-gray-100 dark:bg-gray-900 px-3 py-2">
                        <span x-text="products.length"></span> Item
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="flex-1 overflow-y-auto custom-scrollbar p-3 sm:p-4 lg:p-6">
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 xxl:grid-cols-5 gap-3 sm:gap-4">
                    <template x-for="product in products" :key="product.id">
                        <div @click="addToCart(product)" 
                             class="group bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden cursor-pointer hover:border-blue-500 hover:shadow-xl transition-all relative flex flex-col">
                            
                            <!-- Stock Badge -->
                            <div class="absolute top-2 left-2 z-10 flex flex-col gap-1">
                                <span class="bg-orange-500/90 text-white text-[9px] font-black px-2 py-0.5 uppercase tracking-tighter" x-text="'Stock: ' + product.stock"></span>
                            </div>

                            <!-- Preview Button -->
                            <button @click.stop="$store.imageModal.open(product.thumbnail_url || '/placeholder.png')" 
                                    class="absolute top-2 right-2 z-20 bg-white/90 dark:bg-gray-800/90 p-1.5 rounded-lg text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition opacity-0 group-hover:opacity-100 shadow-sm border border-gray-100 dark:border-gray-700">
                                <i data-lucide="maximize-2" class="w-3.5 h-3.5"></i>
                            </button>

                            <!-- Product Image -->
                            <div class="aspect-square bg-gray-50 dark:bg-gray-900 flex items-center justify-center overflow-hidden">
                                <img :src="product.thumbnail_url || '/placeholder.png'" 
                                     :alt="product.name" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>

                            <!-- Product Data -->
                            <div class="p-3 flex flex-col flex-1">
                                <h4 class="text-[12px] font-black text-gray-800 dark:text-white uppercase leading-tight line-clamp-2 h-8" x-text="product.name"></h4>
                                <div class="text-[10px] text-gray-400 mt-1 mb-2 font-bold" x-text="'SKU: ' + product.sku"></div>
                                <div class="mt-auto flex items-center justify-between">
                                    <div class="text-blue-600 font-black text-sm" x-text="'৳' + product.effective_price"></div>
                                    <template x-if="product.sale_price > 0 && product.sale_price < product.price">
                                        <div class="text-[10px] text-gray-400 line-through" x-text="'৳' + product.price"></div>
                                    </template>
                                </div>
                            </div>

                            <!-- Quick Add Overlay (on hover) -->
                            <div class="absolute inset-0 bg-blue-600/90 flex items-center justify-center translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                <div class="text-white text-center">
                                    <i data-lucide="plus-circle" class="w-8 h-8 mb-2 mx-auto"></i>
                                    <span class="text-[11px] font-black uppercase tracking-widest">Add To Order</span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Empty State -->
                <template x-if="products.length === 0">
                    <div class="flex flex-col items-center justify-center py-40 opacity-20">
                        <i data-lucide="search-x" class="w-20 h-20 mb-4"></i>
                        <p class="text-xl font-bold uppercase tracking-widest">No Products Found</p>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<style>
    .pos-input:focus {
        border-color: #2563eb;
        ring: 0;
        outline: none;
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
</style>

@push('scripts')
<script>
function posSystem() {
    return {
        products: [],
        categories: [],
        cart: [],
        search: '',
        category_id: '',
        shipping: 0,
        discount: 0,
        processing: false,
        customer: {
            name: '',
            phone: '',
            address: ''
        },

        init() {
            this.fetchProducts();
            lucide.createIcons();
        },

        async fetchProducts() {
            try {
                const response = await fetch(`{{ route('admin.pos.products') }}?search=${this.search}&category=${this.category_id}`);
                this.products = await response.json();
                this.$nextTick(() => lucide.createIcons());
            } catch (error) {
                console.error('Failed to fetch products:', error);
            }
        },

        addToCart(product) {
            const index = this.cart.findIndex(item => item.id === product.id);
            const price = product.effective_price;

            if (index !== -1) {
                this.cart[index].quantity++;
            } else {
                this.cart.push({
                    id: product.id,
                    name: product.name,
                    sku: product.sku,
                    price: price,
                    quantity: 1
                });
            }
            this.$nextTick(() => lucide.createIcons());
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
        },

        updateQty(index, change) {
            const newQty = this.cart[index].quantity + change;
            if (newQty > 0) {
                this.cart[index].quantity = newQty;
            }
        },

        calculateSubtotal() {
            return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
        },

        calculateTotal() {
            return (this.calculateSubtotal() + this.shipping) - this.discount;
        },

        clearCart() {
            if (confirm('Are you sure you want to clear the cart?')) {
                this.cart = [];
                this.customer = { name: '', phone: '', address: '' };
                this.shipping = 0;
                this.discount = 0;
            }
        },

        async completeSale() {
            if (!this.customer.name || !this.customer.phone) {
                alert('Please provide customer name and phone number.');
                return;
            }

            this.processing = true;

            try {
                const response = await fetch(`{{ route('admin.pos.store') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        customer_name: this.customer.name,
                        customer_phone: this.customer.phone,
                        customer_address: this.customer.address,
                        items: this.cart,
                        shipping_charge: this.shipping,
                        discount: this.discount
                    })
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    window.location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                alert('An error occurred. Please try again.');
            } finally {
                this.processing = false;
            }
        }
    }
}
</script>
@endpush

@endsection
