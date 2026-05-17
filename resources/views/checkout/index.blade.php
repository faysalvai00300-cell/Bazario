@extends('layouts.app')
@section('title', 'Checkout - Bazario')
@section('body_bg', '#f9fafb')
@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 bg-[#f9fafb]" x-data="checkoutData">
        <style>
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                50% { transform: translateX(5px); }
                75% { transform: translateX(-5px); }
            }
            .animate-shake {
                animation: shake 0.4s cubic-bezier(.36,.07,.19,.97) both;
                border-color: #ff0000 !important;
                box-shadow: 0 0 0 4px rgba(255, 0, 0, 0.2) !important;
                background-color: #fff1f1 !important;
            }
        </style>

        <p class="text-center text-sm text-gray-500 mb-4">Enter your information to order</p>

        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" @submit="saveToLocal()" novalidate>
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Delivery Info -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <div class="mb-5">
                            <h2 class="font-bold text-gray-900 text-base flex items-center gap-2">
                                <span
                                    class="w-7 h-7 rounded-full bg-[#45b86f] text-white text-xs font-bold flex items-center justify-center">1</span>
                                Delivery Information
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="relative" x-data="{ showSuggestion: false }">
                                <label class="text-sm font-medium text-gray-700 mb-1.5 block">Full Name *</label>
                                <input type="text" name="name" value="{{ optional(auth()->user())->name ?? old('name') }}"
                                    @focus="if(hasSavedAddress) showSuggestion = true" @click.away="showSuggestion = false"
                                    required
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-400 focus:outline-none @error('name') border-red-400 @enderror"
                                    placeholder="Enter your name">

                                <!-- Saved Address Suggestion -->
                                <template x-if="showSuggestion && hasSavedAddress">
                                    <div
                                        class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 shadow-xl rounded-xl z-50 overflow-hidden animate-fade-in">
                                        <button type="button" @click="loadSavedAddress(); showSuggestion = false"
                                            class="w-full text-left p-3 hover:bg-green-50 transition flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-green-100 text-[#45b86f] flex items-center justify-center flex-shrink-0">
                                                <i data-lucide="user" class="w-4 h-4"></i>
                                            </div>
                                            <div>
                                                <p class="text-[11px] font-bold text-gray-900">Use Your Saved Address</p>
                                                <p class="text-[10px] text-gray-500 truncate"
                                                    x-text="JSON.parse(localStorage.getItem('Bazario_address')).name"></p>
                                            </div>
                                            <div class="ml-auto">
                                                <span
                                                    class="text-[9px] font-bold text-[#45b86f] uppercase tracking-wider bg-green-100/50 px-2 py-0.5 rounded">Quick
                                                    Fill</span>
                                            </div>
                                        </button>
                                    </div>
                                </template>
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1.5 block">Phone Number *</label>
                                <input type="tel" name="phone" value="{{ optional(auth()->user())->phone ?? old('phone') }}"
                                    required placeholder="01XXXXXXXXX" maxlength="11"
                                    oninput="const b = {'০':'0','১':'1','২':'2','৩':'3','৪':'4','৫':'5','৬':'6','৭':'7','৮':'8','৯':'9'}; this.value = this.value.split('').map(c => b[c] || (/[0-9]/.test(c) ? c : '')).join('')"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-400 focus:outline-none @error('phone') border-red-400 @enderror">
                                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Division -->
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1.5 block">Division *</label>
                                <select name="division" x-model="division" @change="district = ''; thana = ''" required
                                    :class="{'animate-shake': shakeDivision}"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-400 focus:outline-none bg-white transition-all duration-300">
                                    <option value="">--- Select Division ---</option>
                                    <template x-for="(districts, div) in locations" :key="div">
                                        <option :value="div" x-text="div"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- District -->
                            <div class="relative">
                                <div x-show="!division" @click="triggerDivisionShake()" class="absolute inset-0 z-10 cursor-pointer"></div>
                                <label class="text-sm font-medium text-gray-700 mb-1.5 block">District *</label>
                                <select name="district" x-model="district" @change="thana = ''" required
                                    :disabled="!division"
                                    :class="{'animate-shake': shakeDistrict}"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-400 focus:outline-none bg-white disabled:bg-gray-50 transition-all duration-300">
                                    <option value="">--- Select District ---</option>
                                    <template x-if="division">
                                        <template x-for="(thanas, dist) in locations[division]" :key="dist">
                                            <option :value="dist" x-text="dist"></option>
                                        </template>
                                    </template>
                                </select>
                            </div>

                            <!-- Thana/Upazila -->
                            <div class="relative">
                                <div x-show="!district" @click="!division ? triggerDivisionShake() : triggerDistrictShake()" class="absolute inset-0 z-10 cursor-pointer"></div>
                                <label class="text-sm font-medium text-gray-700 mb-1.5 block">Thana / Upazila *</label>
                                <select name="thana" x-model="thana" required :disabled="!district"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-400 focus:outline-none bg-white disabled:bg-gray-50">
                                    <option value="">--- Select Thana ---</option>
                                    <template x-if="district">
                                        <template x-for="t in locations[division][district]" :key="t">
                                            <option :value="t" x-text="t"></option>
                                        </template>
                                    </template>
                                </select>
                            </div>

                            <!-- Delivery Area -->
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1.5 block">Delivery Area *</label>
                                <select name="delivery_area" x-model="area" @change="updateTotals()" required
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-400 focus:outline-none bg-white">
                                    @if($deliveryAreas->isNotEmpty())
                                        @foreach($deliveryAreas as $da)
                                            <option value="{{ $da->id }}">{{ $da->name }} @if(!$hasFreeShipping)
                                            (৳{{ number_format($da->charge, 0) }}) @endif</option>
                                        @endforeach
                                    @else
                                        <option value="inside">Inside Dhaka City @if(!$hasFreeShipping)
                                        (৳{{ number_format($siteSettings->delivery_charge_inside ?? 70, 0) }}) @endif
                                        </option>
                                        <option value="outside">Outside Dhaka City @if(!$hasFreeShipping)
                                        (৳{{ number_format($siteSettings->delivery_charge_outside ?? 130, 0) }}) @endif
                                        </option>
                                    @endif
                                </select>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium text-gray-700 mb-1.5 block">Full Address *</label>
                                <textarea name="address" required rows="3" placeholder="House No, Road, Area..."
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-400 focus:outline-none resize-none @error('address') border-red-400 @enderror">{{ optional(auth()->user())->address ?? old('address') }}</textarea>
                                @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-50">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" x-model="saveAddress"
                                    class="w-4 h-4 rounded border-gray-300 text-[#45b86f] focus:ring-[#45b86f]">
                                <span
                                    class="text-[11px] font-medium text-gray-500 group-hover:text-gray-700 transition">Save
                                    this information for next time</span>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h2 class="font-bold text-gray-900 text-base mb-5 flex items-center gap-2">
                            <span
                                class="w-7 h-7 rounded-full bg-[#45b86f] text-white text-xs font-bold flex items-center justify-center">2</span>
                            Payment Method
                        </h2>

                        <div class="grid grid-cols-3 gap-2 sm:gap-4">
                            <label @click="selectMethod('cod')"
                                class="relative flex flex-col items-center gap-2 p-3 sm:p-5 rounded-xl border-2 cursor-pointer transition-all duration-300"
                                :class="method === 'cod' ? 'border-[#45b86f] bg-green-50/30' : 'border-gray-100 bg-white hover:border-gray-200'">
                                <input type="radio" name="payment_method" value="cod" :checked="method === 'cod'"
                                    class="sr-only">
                                <div class="w-10 h-10 flex items-center justify-center transition-colors duration-300"
                                    :class="method === 'cod' ? 'text-[#45b86f]' : 'text-gray-300'">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <span
                                    class="text-[9px] sm:text-[11px] font-black text-center leading-tight transition-colors"
                                    :class="method === 'cod' ? 'text-[#45b86f]' : 'text-gray-400'">Cash on Delivery</span>
                            </label>

                            <label @click="selectMethod('bkash')"
                                class="relative flex flex-col items-center gap-2 p-3 sm:p-5 rounded-xl border-2 cursor-pointer transition-all duration-300"
                                :class="method === 'bkash' ? 'border-pink-500 bg-pink-50/30' : 'border-gray-100 bg-white hover:border-gray-200'">
                                <input type="radio" name="payment_method" value="bkash" :checked="method === 'bkash'"
                                    class="sr-only">
                                <div class="w-10 h-10 flex items-center justify-center grayscale transition-all duration-300"
                                    :class="method === 'bkash' ? 'grayscale-0' : 'opacity-40'">
                                    <img src="https://www.logo.wine/a/logo/BKash/BKash-Icon-Logo.wine.svg"
                                        class="w-full h-full object-contain" alt="bKash">
                                </div>
                                <span
                                    class="text-[9px] sm:text-[11px] font-black text-center leading-tight transition-colors"
                                    :class="method === 'bkash' ? 'text-pink-600' : 'text-gray-400'">bKash</span>
                            </label>

                            <label @click="selectMethod('nagad')"
                                class="relative flex flex-col items-center gap-2 p-3 sm:p-5 rounded-xl border-2 cursor-pointer transition-all duration-300"
                                :class="method === 'nagad' ? 'border-red-500 bg-red-50/30' : 'border-gray-100 bg-white hover:border-gray-200'">
                                <input type="radio" name="payment_method" value="nagad" :checked="method === 'nagad'"
                                    class="sr-only">
                                <div class="w-10 h-10 flex items-center justify-center grayscale transition-all duration-300"
                                    :class="method === 'nagad' ? 'grayscale-0' : 'opacity-40'">
                                    <img src="https://download.logo.wine/logo/Nagad/Nagad-Vertical-Logo.wine.png"
                                        class="w-full h-full object-contain" alt="Nagad">
                                </div>
                                <span
                                    class="text-[9px] sm:text-[11px] font-black text-center leading-tight transition-colors"
                                    :class="method === 'nagad' ? 'text-red-600' : 'text-gray-400'">Nagad</span>
                            </label>
                        </div>


                        <!-- Payment Instructions -->
                        <template x-if="(method === 'bkash' || method === 'nagad') && !isGatewayConfigured">
                            <div x-transition class="mt-6 p-5 rounded-2xl bg-red-50 border border-red-100 shadow-sm">
                                <div class="flex items-center flex-col text-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-red-900 text-sm mb-1 uppercase">Payment Closed</h3>
                                    <p class="text-red-700 text-[11px]">Redirecting to COD in 3 seconds...</p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-lg p-6 sticky top-24">
                        <h2 class="font-bold text-gray-900 text-base mb-5">Order Summary</h2>

                        <div class="space-y-4 mb-6 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($cartItems as $item)
                                <div class="flex gap-3">
                                    <div class="relative flex-shrink-0">
                                        <img src="{{ $item['product']->getColorImageUrl($item['color'] ?? '') }}"
                                            class="w-14 h-14 rounded-xl object-cover border border-gray-50"
                                            alt="{{ $item['product']->name }}">
                                        <span
                                            class="absolute -top-2 -right-2 bg-gray-900 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">{{ $item['quantity'] }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-xs font-bold text-gray-800 truncate mb-1">{{ $item['product']->name }}
                                        </h4>
                                        <p class="text-[10px] text-gray-500 line-through">
                                            Tk{{ number_format($item['product']->price * 1.2) }}</p>
                                        <p class="text-xs font-black text-[#45b86f]">
                                            Tk{{ number_format($item['product']->effective_price) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Coupon Code Section -->
                        <div class="mb-5 px-1">
                            <div class="flex gap-2">
                                <input type="text" x-model="coupon" :disabled="applied" placeholder="Coupon"
                                    class="flex-1 min-w-0 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-gray-300 outline-none">
                                <button type="button" @click="handleCoupon()"
                                    :class="applied ? 'bg-red-50 text-red-600' : 'bg-gray-900 text-white'"
                                    class="px-4 rounded-xl text-[10px] font-black h-9">
                                    <span x-text="applied ? 'Remove' : 'Apply'"></span>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Subtotal</span>
                                <span class="font-semibold text-gray-900">Tk{{ number_format($subtotal) }}</span>
                            </div>
                            <div x-show="discount > 0" class="flex justify-between text-sm text-green-600">
                                <span>Discount</span>
                                <span x-text="'-Tk' + discount.toLocaleString()" class="font-bold"></span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Delivery</span>
                                <span class="font-semibold" :class="shipping == 0 ? 'text-green-600' : 'text-gray-900'"
                                    x-text="shipping == 0 ? 'Free' : 'Tk' + shipping.toLocaleString()"></span>
                            </div>
                            <hr class="border-gray-100">
                            <div class="flex justify-between items-center pt-1">
                                <span class="font-bold text-gray-900 text-base">Total</span>
                                <span class="text-2xl font-black text-[#45b86f]"
                                    x-text="'Tk' + total.toLocaleString()"></span>
                            </div>
                        </div>

                        <div class="mt-8">
                            <button type="submit" id="order-submit-btn"
                                class="w-full py-4 rounded-2xl text-base font-black flex items-center justify-center gap-2 transition-all duration-300 shadow-xl shadow-black/10 active:scale-95 disabled:opacity-50 disabled:bg-gray-400"
                                style="background-color: #000000 !important; color: #FFFFFF !important;"
                                :disabled="method !== 'cod' && !isGatewayConfigured">
                                <span x-show="method === 'cod' || isGatewayConfigured">Complete Order</span>
                                <span x-show="method !== 'cod' && !isGatewayConfigured" x-cloak>Payment Closed</span>
                            </button>
                            <p class="text-[11px] text-gray-400 text-center mt-4 uppercase tracking-tighter">By clicking the
                                order button, you agree to all our <a href="#" class="underline hover:text-gray-600">terms
                                    and conditions</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('checkoutData', () => ({
                division: '',
                district: '',
                thana: '',
                method: 'cod',
                isGatewayConfigured: {{ $isGatewayConfigured ? 'true' : 'false' }},
                coupon: '{{ session('coupon_code', '') }}',
                applied: {{ (session('promo_discount') ?? 0) > 0 ? 'true' : 'false' }},
                loading: false,
                discount: {{ (float) (session('promo_discount') ?? 0) }},
                subtotal: {{ (float) $subtotal }},
                shipping: {{ (float) $shipping }},
                total: {{ (float) $total }},
                area: '{{ $deliveryAreas->isNotEmpty() ? $deliveryAreas->first()->id : 'outside' }}',
                hasFreeShipping: {{ $hasFreeShipping ? 'true' : 'false' }},
                deliveryAreas: {!! $deliveryAreas->toJson() !!},
                saveAddress: true,
                hasSavedAddress: false,
                shakeDivision: false,
                shakeDistrict: false,
                triggerDivisionShake() {
                    this.shakeDivision = true;
                    setTimeout(() => { this.shakeDivision = false; }, 400);
                },
                triggerDistrictShake() {
                    this.shakeDistrict = true;
                    setTimeout(() => { this.shakeDistrict = false; }, 400);
                },

                init() {
                    this.updateTotals();
                    this.checkSavedAddress();
                },

                checkSavedAddress() {
                    const saved = localStorage.getItem('Bazario_address');
                    if (saved) {
                        this.hasSavedAddress = true;
                    }
                },

                loadSavedAddress() {
                    const saved = JSON.parse(localStorage.getItem('Bazario_address'));
                    if (saved) {
                        // Set text fields
                        document.getElementsByName('name')[0].value = saved.name || '';
                        document.getElementsByName('phone')[0].value = saved.phone || '';
                        document.getElementsByName('address')[0].value = saved.address || '';

                        // Set Alpine models for selects
                        this.division = saved.division || '';
                        this.$nextTick(() => {
                            this.district = saved.district || '';
                            this.$nextTick(() => {
                                this.thana = saved.thana || '';
                            });
                        });

                        if (saved.area) {
                            this.area = saved.area;
                            this.updateTotals();
                        }
                    }
                },

                saveToLocal() {
                    if (this.saveAddress) {
                        const data = {
                            name: document.getElementsByName('name')[0].value,
                            phone: document.getElementsByName('phone')[0].value,
                            division: this.division,
                            district: this.district,
                            thana: this.thana,
                            area: this.area,
                            address: document.getElementsByName('address')[0].value
                        };
                        localStorage.setItem('Bazario_address', JSON.stringify(data));
                    }
                },

                deleteSavedAddress() {
                    localStorage.removeItem('Bazario_address');
                    this.hasSavedAddress = false;
                },
                updateTotals() {
                    if (this.hasFreeShipping) {
                        this.shipping = 0;
                    } else {
                        const customArea = this.deliveryAreas.find(a => a.id == this.area);
                        if (customArea) {
                            this.shipping = parseFloat(customArea.charge);
                        } else {
                            this.shipping = (this.area === 'inside') ? 70 : 130;
                        }
                    }
                    this.total = (this.subtotal - this.discount) + this.shipping;
                },

                selectMethod(m) {
                    this.method = m;
                    if (!this.isGatewayConfigured && (m === 'bkash' || m === 'nagad')) {
                        setTimeout(() => { if (this.method === m) this.method = 'cod'; }, 3000);
                    }
                },

                handleCoupon() {
                    if (this.applied) {
                        fetch('/remove-coupon', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                            .then(r => r.json()).then(d => {
                                this.applied = false; this.discount = 0; this.updateTotals();
                            });
                    } else if (this.coupon) {
                        fetch('/apply-coupon', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ coupon_code: this.coupon }) })
                            .then(r => r.json()).then(d => {
                                if (d.success) { this.applied = true; this.discount = d.discount; this.updateTotals(); }
                            });
                    }
                },

                locations: {
                    'Dhaka': {
                        'Dhaka': ['Dhanmondi', 'Gulshan', 'Mirpur', 'Uttara', 'Mohammadpur', 'Badda', 'Motijheel', 'New Market', 'Paltan', 'Ramna', 'Tejgaon', 'Keraniganj', 'Savar', 'Dhamrai', 'Nawabganj', 'Dohar'],
                        'Gazipur': ['Gazipur Sadar', 'Kaliakair', 'Kaliganj', 'Kapasia', 'Sreepur', 'Tongi'],
                        'Narayanganj': ['Narayanganj Sadar', 'Araihazar', 'Bandar', 'Rupganj', 'Sonargaon'],
                        'Tangail': ['Tangail Sadar', 'Basail', 'Bhuapur', 'Delduar', 'Ghatail', 'Gopalpur', 'Kalihati', 'Madhupur', 'Mirzapur', 'Nagarpur', 'Sakhipur', 'Dhanbari'],
                        'Kishoreganj': ['Kishoreganj Sadar', 'Austagram', 'Bajitpur', 'Bhairab', 'Hossainpur', 'Itna', 'Karimganj', 'Katiadi', 'Kuliarchar', 'Mithamain', 'Nikli', 'Pakundia', 'Tarail'],
                        'Manikganj': ['Manikganj Sadar', 'Daulatpur', 'Ghiror', 'Harirampur', 'Saturia', 'Shibalaya', 'Singair'],
                        'Munshiganj': ['Munshiganj Sadar', 'Gazaria', 'Lohajang', 'Sirajdikhan', 'Sreenagar', 'Tongibari'],
                        'Narsingdi': ['Narsingdi Sadar', 'Belabo', 'Monohardi', 'Palash', 'Raipura', 'Shibpur'],
                        'Faridpur': ['Faridpur Sadar', 'Alfadanga', 'Bhanga', 'Boalmari', 'Charbhadrasan', 'Madukhali', 'Nagarkanda', 'Sadarpur', 'Saltha'],
                        'Gopalganj': ['Gopalganj Sadar', 'Kashiani', 'Kotalipara', 'Muksudpur', 'Tungipara'],
                        'Madaripur': ['Madaripur Sadar', 'Kalkini', 'Rajoir', 'Shibchar'],
                        'Rajbari': ['Rajbari Sadar', 'Baliakandi', 'Goalandaghat', 'Pangsha', 'Kalukhali'],
                        'Shariatpur': ['Shariatpur Sadar', 'Bhedarganj', 'Damudya', 'Gosairhat', 'Naria', 'Zajira']
                    },
                    'Chattogram': {
                        'Chattogram': ['Kotwali', 'Panchlaish', 'Double Mooring', 'Halishahar', 'Bakalia', 'Bayazid Bostami', 'Chandgaon', 'Chawkbazar', 'EPZ', 'Karnaphuli', 'Khulshi', 'Pahartali', 'Patenga', 'Sitakunda', 'Anwara', 'Banshkhali', 'Boalkhali', 'Chandanaish', 'Fatikchhari', 'Hathazari', 'Lohagara', 'Mirsharai', 'Patiya', 'Rangunia', 'Raozan', 'Sandwip', 'Satkania'],
                        'Coxs Bazar': ['Coxs Bazar Sadar', 'Chakaria', 'Kutubdia', 'Maheshkhali', 'Ramu', 'Teknaf', 'Ukhia', 'Pekua'],
                        'Cumilla': ['Comilla Sadar', 'Barura', 'Brahmanpara', 'Burichang', 'Chandina', 'Chauddagram', 'Daudkandi', 'Debidwar', 'Homna', 'Laksam', 'Monohargonj', 'Meghna', 'Muradnagar', 'Nangalkot', 'Titas'],
                        'Feni': ['Feni Sadar', 'Chhagalnaiya', 'Daganbhuiyan', 'Parshuram', 'Fulgazi', 'Sonagazi'],
                        'Noakhali': ['Noakhali Sadar', 'Begumganj', 'Chatkhil', 'Companiganj', 'Hatiya', 'Senbagh', 'Sonaimuri', 'Subarnachar', 'Kabirhat'],
                        'Lakshmipur': ['Lakshmipur Sadar', 'Raipur', 'Ramganj', 'Ramgati', 'Kamalnagar'],
                        'Chandpur': ['Chandpur Sadar', 'Faridganj', 'Haimchar', 'Haziganj', 'Kachua', 'Matlab North', 'Matlab South', 'Shahrasti'],
                        'Brahmanbaria': ['Brahmanbaria Sadar', 'Akhaura', 'Ashuganj', 'Bancharampur', 'Bijoynagar', 'Kasba', 'Nabinagar', 'Nasirnagar', 'Sarail'],
                        'Rangamati': ['Rangamati Sadar', 'Belaichhari', 'Baghaichhari', 'Barkal', 'Juraichhari', 'Kaptai', 'Kawkhali', 'Langadu', 'Nanidhar', 'Rajasthali'],
                        'Bandarban': ['Bandarban Sadar', 'Alikadam', 'Lama', 'Naikhongchhari', 'Rowangchhari', 'Ruma', 'Thanchi'],
                        'Khagrachhari': ['Khagrachhari Sadar', 'Dighinala', 'Lakshmichhari', 'Mahalchhari', 'Manikkari', 'Matiranga', 'Panchhari', 'Ramgarh']
                    },
                    'Rajshahi': {
                        'Rajshahi': ['Boalia', 'Motihar', 'Rajput', 'Shah Makhdum', 'Godagari', 'Tanore', 'Bagmara', 'Durgapur', 'Puthia', 'Paba', 'Charghat', 'Bagha'],
                        'Bogura': ['Bogra Sadar', 'Adamdighi', 'Dhunat', 'Dhupchanchia', 'Gabtali', 'Kahaloo', 'Nandigram', 'Sariakandi', 'Sherpur', 'Shibganj', 'Sonatala', 'Shahjahanpur'],
                        'Pabna': ['Pabna Sadar', 'Atgharia', 'Bera', 'Bhangura', 'Chatmohar', 'Faridpur', 'Ishwardi', 'Santhia', 'Sujanagar'],
                        'Sirajganj': ['Sirajganj Sadar', 'Belkuchi', 'Chauhali', 'Kamarkhanda', 'Kazipur', 'Raiganj', 'Shahjadpur', 'Tarash', 'Ullapara'],
                        'Naogaon': ['Naogaon Sadar', 'Atrai', 'Badalgachhi', 'Dhamoirhat', 'Mahadevpur', 'Manda', 'Niamatpur', 'Patnitala', 'Porsha', 'Raninagar', 'Sapahar'],
                        'Natore': ['Natore Sadar', 'Bagatipara', 'Baraigram', 'Gurudaspur', 'Lalpur', 'Singra', 'Naldanga'],
                        'Joypurhat': ['Joypurhat Sadar', 'Akkelpur', 'Kalai', 'Khetlal', 'Panchbibi'],
                        'Chapainawabganj': ['Chapainawabganj Sadar', 'Bholahat', 'Gomastapur', 'Nachole', 'Shibganj']
                    },
                    'Khulna': {
                        'Khulna': ['Khulna Sadar', 'Daulatpur', 'Khalishpur', 'Khan Jahan Ali', 'Sonadanga', 'Batiaghata', 'Dacope', 'Dumuria', 'Dighalia', 'Koyra', 'Paikgachha', 'Phultala', 'Rupsha', 'Terokhada'],
                        'Jashore': ['Jessore Sadar', 'Abhaynagar', 'Bagherpara', 'Chaugachha', 'Jhikargachha', 'Keshabpur', 'Manirampur', 'Sharsha'],
                        'Kushtia': ['Kushtia Sadar', 'Bheramara', 'Daulatpur', 'Khoksa', 'Kumarkhali', 'Mirpur'],
                        'Satkhira': ['Satkhira Sadar', 'Assasuni', 'Debhata', 'Kalaroa', 'Kaliganj', 'Shyamnagar', 'Tala'],
                        'Bagerhat': ['Bagerhat Sadar', 'Chitalmari', 'Fakirhat', 'Kachua', 'Mollahat', 'Mongla', 'Morrelganj', 'Rampal', 'Sarankhola'],
                        'Jhenaidah': ['Jhenaidah Sadar', 'Harinakunda', 'Kaliganj', 'Kotchandpur', 'Maheshpur', 'Shailkupa'],
                        'Chuadanga': ['Chuadanga Sadar', 'Alamdanga', 'Damurhuda', 'Jibannagar'],
                        'Chuo': ['Meherpur Sadar', 'Gangni', 'Mujibnagar'],
                        'Narail': ['Narail Sadar', 'Kalia', 'Lohagara'],
                        'Magura': ['Magura Sadar', 'Mohammadpur', 'Shalikha', 'Sreepur']
                    },
                    'Barishal': {
                        'Barishal': ['Barisal Sadar', 'Agailjhara', 'Babuganj', 'Bakerganj', 'Banaripara', 'Gournadi', 'Hizla', 'Mehendiganj', 'Muladi', 'Wazirpur'],
                        'Bhola': ['Bhola Sadar', 'Burhanuddin', 'Char Fasson', 'Daulatkhan', 'Lalmohan', 'Manpura', 'Tazumuddin'],
                        'Patuakhali': ['Patuakhali Sadar', 'Bauphal', 'Dashmina', 'Galachipa', 'Kalapara', 'Mirzaganj', 'Dumki', 'Rangabali'],
                        'Pirojpur': ['Pirojpur Sadar', 'Bhandaria', 'Kawkhali', 'Mathbaria', 'Nazirpur', 'Nesarabad', 'Zianagar'],
                        'Barguna': ['Barguna Sadar', 'Amatali', 'Bamna', 'Betagi', 'Patharghata', 'Taltali'],
                        'Jhalakathi': ['Jhalokati Sadar', 'Kathalia', 'Nalchity', 'Rajapur']
                    },
                    'Sylhet': {
                        'Sylhet': ['Sylhet Sadar', 'Beanibazar', 'Bishwanath', 'Dakshin Surma', 'Fenchuganj', 'Golapganj', 'Gowainghat', 'Jaintiapur', 'Kanaighat', 'Zakiganj', 'Companiganj', 'Osmani Nagar'],
                        'Moulvibazar': ['Moulvibazar Sadar', 'Barlekha', 'Kamalganj', 'Kulaura', 'Rajnagar', 'Sreemangal', 'Juri'],
                        'Habiganj': ['Habiganj Sadar', 'Ajmiriganj', 'Bahubal', 'Baniyachong', 'Chunarughat', 'Lakhai', 'Madhabpur', 'Nabiganj', 'Sayestaganj'],
                        'Sunamganj': ['Sunamganj Sadar', 'Bishwamandarpur', 'Chhatak', 'Derai', 'Dharamapasha', 'Dowarabazar', 'Jagannathpur', 'Jamalganj', 'Sullah', 'Taharirpur', 'Shantiganj']
                    },
                    'Rangpur': {
                        'Rangpur': ['Rangpur Sadar', 'Badarganj', 'Gangachara', 'Kaunia', 'Mithapukur', 'Pirgachha', 'Pirganj', 'Taraganj'],
                        'Dinajpur': ['Dinajpur Sadar', 'Birganj', 'Biral', 'Bochaganj', 'Chirirbandar', 'Phulbari', 'Ghoraghat', 'Hakimpur', 'Kaharole', 'Khansama', 'Nawabganj', 'Parbatipur', 'Birol'],
                        'Gaibandha': ['Gaibandha Sadar', 'Phulchhari', 'Gobindaganj', 'Palashbari', 'Sadullapur', 'Saghata', 'Sundarganj'],
                        'Kurigram': ['Kurigram Sadar', 'Bhurungamari', 'Char Rajibpur', 'Chilmari', 'Phulbari', 'Nageshwari', 'Rajarhat', 'Rowmari', 'Ulipur'],
                        'Nilphamari': ['Nilphamari Sadar', 'Dimla', 'Domar', 'Jaldhaka', 'Kishoreganj', 'Saidpur'],
                        'Thakurgaon': ['Thakurgaon Sadar', 'Baliadangi', 'Haripur', 'Pirganj', 'Ranisankail'],
                        'Lalmonirhat': ['Lalmonirhat Sadar', 'Aditmari', 'Hatibandha', 'Kaliganj', 'Patgram'],
                        'Panchagarh': ['Panchagarh Sadar', 'Atwari', 'Boda', 'Debiganj', 'Tetulia']
                    },
                    'Mymensingh': {
                        'Mymensingh': ['Mymensingh Sadar', 'Bhaluka', 'Dhobaura', 'Fulbaria', 'Gaffargaon', 'Gauripur', 'Haluaghat', 'Ishwarganj', 'Muktagachha', 'Nandail', 'Phulpur', 'Trishal', 'Tara Kandai'],
                        'Jamalpur': ['Jamalpur Sadar', 'Bakshiganj', 'Dewanganj', 'Islampur', 'Madarganj', 'Melandaha', 'Sarishabari'],
                        'Netrokona': ['Netrokona Sadar', 'Atpara', 'Barhatta', 'Durgapur', 'Khaliajuri', 'Kalmakanda', 'Kendua', 'Madan', 'Mohanganj', 'Purbadhala'],
                        'Sherpur': ['Sherpur Sadar', 'Jhenaigati', 'Nakla', 'Nalitabari', 'Sreebardi']
                    }
                }
            }));
        });

        // Form and Lead Capture logic
        document.getElementById('checkout-form').addEventListener('submit', function (e) {
            let isValid = true;
            let firstError = null;
            this.querySelectorAll('.error-msg').forEach(el => el.remove());
            this.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500', 'ring-2', 'ring-red-100'));

            this.querySelectorAll('[required]').forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                    if (!firstError) firstError = field;
                }
            });

            if (!isValid) {
                e.preventDefault();
                if (firstError) {
                    window.scrollTo({ top: firstError.offsetTop - 120, behavior: 'smooth' });
                    firstError.focus();
                }
            }
        });

        function captureLead() {
            const formData = new FormData(document.getElementById('checkout-form'));
            const data = Object.fromEntries(formData);
            if (!data.name && !data.phone) return;
            fetch('{{ route('checkout.capture') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(data)
            });
        }

        document.querySelectorAll('input, select, textarea').forEach(el => el.addEventListener('blur', captureLead));
    </script>

    @if(isset($siteSettings) && $siteSettings->facebook_pixel_id)
        <script>
            fbq('track', 'InitiateCheckout', { value: {{ $total }}, currency: 'BDT', num_items: {{ count($cartItems) }} });
        </script>
    @endif

    @if(isset($siteSettings) && $siteSettings->tiktok_pixel_id)
        <script>
            ttq.track('InitiateCheckout', {
                value: {{ $total }},
                currency: 'BDT',
                quantity: {{ count($cartItems) }}
            });
        </script>
    @endif
@endpush