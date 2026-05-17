@php
    $isFull = str_contains($class ?? '', 'col-span-full');
    
    // Fetch slider settings
    $settingsCache = \Illuminate\Support\Facades\Cache::remember('site_settings', 60, function() {
        return \App\Models\Setting::first();
    });
    $isSliderActive = $settingsCache->is_product_slider_active ?? true;
    $sliderInterval = $settingsCache->product_slider_interval ?? 4000;
    
    // Prepare images array for the slider
    $images = collect([$product->thumbnail_url]);
    if ($product->images && $product->images->count() > 0) {
        foreach($product->images as $img) {
            $images->push($img->image_url);
        }
    }
    // Clean and convert to JSON
    $imageJson = $images->unique()->values()->take(5)->toJson();
@endphp
<div x-data="{
        images: {{ $imageJson }},
        currentIndex: 0,
        interval: null,
        startSlider() {
            if ({{ $isSliderActive ? 'true' : 'false' }} && this.images.length > 1 && !this.interval) {
                this.interval = setInterval(() => {
                    this.currentIndex = (this.currentIndex + 1) % this.images.length;
                }, {{ $sliderInterval }});
            }
        },
        stopSlider() {
            if (this.interval) {
                clearInterval(this.interval);
                this.interval = null;
            }
        }
    }"
    x-init="startSlider()"
    @mouseenter="stopSlider()"
    @mouseleave="startSlider()"
    class="product-card group bg-white border border-gray-100 overflow-hidden flex flex-col relative transition-all duration-300 hover:shadow-lg {{ $class ?? '' }}"
    {!! !($no_aos ?? false) ? 'data-aos="fade-up" data-aos-delay="' . ($delay ?? 0) . '"' : '' !!}>
    
    {{-- Image Container (Fabrilife Style) --}}
    <div class="relative w-full overflow-hidden bg-[#F2F2F2] pointer-events-auto {{ $product->stock <= 0 ? 'grayscale-[0.6]' : '' }}" style="aspect-ratio: 1/1.1;">
        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full">
            <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" 
                 class="product-main-img w-full h-full object-cover absolute top-0 left-0 z-10"
                 style="transition: transform 0.5s ease-in-out;"
                 :class="currentIndex !== 0 ? 'opacity-0' : 'opacity-100'"
                 loading="lazy">
            
            <template x-for="(img, index) in images.slice(1)" :key="index + 1">
                <img :src="img" alt="{{ $product->name }}" 
                     class="w-full h-full object-cover absolute top-0 left-0 transition-all duration-500 ease-in-out z-0"
                     :class="currentIndex === (index + 1) ? 'opacity-100 z-10' : 'opacity-0 z-0'">
            </template>
        </a>

        {{-- Stock Out Overlay (Premium Style) --}}
        @if($product->stock <= 0)
        <div class="absolute inset-0 z-40 flex items-center justify-center p-4 pointer-events-none">
            <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-[1px]"></div>
            <div class="relative z-50 text-white px-6 py-2.5 shadow-2xl rounded-sm" style="background-color: #ff3f6c !important;">
                <span class="text-[11px] sm:text-[13px] font-black uppercase tracking-[0.1em] whitespace-nowrap block text-center">Out of Stock</span>
            </div>
        </div>
        @endif

        {{-- Slider Indicators (Dots - Fabrilife style) --}}
        <div x-show="images.length > 1" class="absolute bottom-3 left-0 w-full flex justify-center gap-1.5 z-20">
            <template x-for="(img, index) in images" :key="index">
                <div class="h-1.5 w-1.5 rounded-full transition-all duration-300" 
                     :class="currentIndex === index ? 'bg-gray-800 w-3' : 'bg-white/80'"></div>
            </template>
        </div>

        @if($product->effective_price < $product->price)
        <div class="absolute top-2 left-2 z-20 pointer-events-none">
            <span class="text-white text-[11px] sm:text-[13px] font-black px-2 py-1 rounded-[2px] shadow-md uppercase" style="background-color: #ff3f6c !important; color: #FFFFFF !important; line-height: 1;">
                -{{ $product->discount_percent }}%
            </span>
        </div>
        @endif

        {{-- Free Delivery Badge (Bottom Left - Fabrilife Style) --}}
        @if($product->free_shipping)
        <div class="absolute bottom-2 left-2 z-20 pointer-events-none">
            <span class="text-white text-[9px] sm:text-[10px] font-bold px-2 py-1 rounded-[2px] flex items-center gap-1 shadow-sm uppercase" style="background-color: #2BB673 !important; color: #FFFFFF !important;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 011 1v2.5a1.5 1.5 0 01-3 0V17a1 1 0 011-1h2zm7-1a1 1 0 011 1v2.5a1.5 1.5 0 01-3 0V17a1 1 0 011-1h2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9h4l3 3v4h-7V9z"/></svg>
                Free Delivery
            </span>
        </div>
        @endif
 
        {{-- Expand/Quick-View & Wishlist Icons (Fabrilife/Myntra Style) --}}
        <div class="absolute top-2 sm:top-4 right-2 sm:right-4 z-30 flex flex-col gap-2 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-all duration-500">
             <button @click.stop="$dispatch('open-image-modal', { images: images, index: currentIndex })" 
                     class="hidden sm:flex w-8 h-8 sm:w-10 sm:h-10 items-center justify-center bg-white/95 text-gray-900 hover:bg-black hover:text-white rounded-full transition-all duration-300 shadow-md active:scale-95 backdrop-blur-sm"
                     title="View Gallery">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                </svg>
            </button>
            @php
                $inWishlist = auth()->check() && auth()->user()->wishlist && auth()->user()->wishlist->contains('product_id', $product->id);
            @endphp
            <button onclick="event.stopPropagation(); smartToggleWishlist({{ $product->id }})" 
                    id="wishlist-btn-{{ $product->id }}"
                    class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center bg-white/95 {{ $inWishlist ? 'text-red-500' : 'text-gray-900' }} hover:text-red-500 rounded-full transition-all duration-300 shadow-md active:scale-95 backdrop-blur-sm"
                    title="Add to Wishlist">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.5 3c1.557 0 3.046.727 4 2.015Q12.454 3 14.5 3c2.786 0 5.25 2.322 5.25 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Content --}}
    <div class="p-1.5 sm:p-4 pb-1.5 sm:pb-6 flex flex-col flex-1 relative bg-white" style="padding-right: 40px;">
        {{-- Product name --}}
        <a href="{{ route('products.show', $product->slug) }}" class="block mb-0.5 sm:mb-4">
            <h3 class="text-[11px] sm:text-sm text-[#333333] font-bold leading-tight line-clamp-2 hover:text-[#222222] transition-colors">
                {{ $product->name }}
            </h3>
        </a>

        {{-- Save Badge --}}


        {{-- Save Amount Pill (Above Price - Fabrilife Style) --}}
        @if($product->effective_price < $product->price)
        <div class="mb-0.5 sm:mb-1.5">
            <span class="text-white text-[8px] sm:text-[10px] font-bold px-1.5 py-0.5 rounded-sm inline-flex items-center gap-1 shadow-sm" style="background-color: #ff3f6c !important; color: #FFFFFF !important;">
                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12.76 3.76a2 2 0 012.83 0l5.65 5.65a2 2 0 010 2.83l-9.9 9.9a2 2 0 01-2.83 0l-5.65-5.65a2 2 0 010-2.83l9.9-9.9zM6.5 8.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                </svg>
                Save ৳{{ number_format($product->price - $product->effective_price) }}
            </span>
        </div>
        @endif

        {{-- Pricing --}}
        <div class="flex items-center flex-wrap gap-1 sm:gap-2 mt-0.5 sm:mt-auto">
            <span class="font-black text-[13px] sm:text-[17px] text-black leading-none">৳{{ number_format($product->effective_price) }}</span>
            @if($product->effective_price < $product->price)
            <span class="text-gray-400 text-[10px] sm:text-[12px] line-through leading-none">৳{{ number_format($product->price) }}</span>
@endif
        </div>

        {{-- Add to Cart Circular Icon --}}
        @once
        <style>
            .smart-cart-btn {
                background-color: #000000 !important;
                width: 30px !important;
                height: 30px !important;
                bottom: 8px !important;
                right: 8px !important;
            }
            .smart-cart-icon {
                width: 16px !important;
                height: 16px !important;
                stroke: #FFFFFF !important;
            }
            @media (min-width: 640px) {
                .smart-cart-btn {
                    background-color: #333333 !important;
                    width: 40px !important;
                    height: 40px !important;
                    bottom: 16px !important;
                    right: 16px !important;
                }
                .smart-cart-btn:hover {
                    background-color: #111111 !important;
                    transform: scale(1.1);
                }
                .smart-cart-icon {
                    width: 20px !important;
                    height: 20px !important;
                }
            }
        </style>
        @endonce
        <button 
                @if($product->stock <= 0)
                    disabled
                    style="background-color: #999999 !important; cursor: not-allowed !important; opacity: 0.7;"
                @else
                    onclick="event.stopPropagation(); window.dispatchEvent(new CustomEvent('open-quick-add', { detail: { id: {{ $product->id }} } }))" 
                @endif
                class="smart-cart-btn absolute rounded-full flex items-center justify-center transition-all duration-300 active:scale-95 shadow-md hover:shadow-lg z-30"
                style="border: none; outline: none; cursor: pointer;">
            {{-- Unified Cart Icon for Mobile & Desktop --}}
            <svg class="smart-cart-icon" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </button>
    </div>
</div>
