@extends('layouts.app')
@section('title', 'SmartLookBD - Best Online Shopping in Bangladesh for Premium Products')
@section('meta_description', 'SmartLookBD is your premium destination for authentic perfumes, luxury fragrances, fashion, and electronics in Bangladesh. Enjoy official warranty and fast home delivery.')

@section('content')
<!-- SEO H1 - Hidden but crawlable -->
<h1 class="sr-only">SmartLookBD: Premium Online Shopping in Bangladesh for Authentic Perfumes & Lifestyle</h1>

@push('styles')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "WebSite",
      "@@id": "{{ url('/') }}/#website",
      "name": "SmartLookBD",
      "url": "{{ url('/') }}",
      "description": "SmartLookBD - Premium Online Shopping Bangladesh",
      "potentialAction": {
        "@@type": "SearchAction",
        "target": "{{ url('/search') }}?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    },
    {
      "@@type": "Organization",
      "@@id": "{{ url('/') }}/#organization",
      "name": "SmartLookBD",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('final logo.jpeg') }}",
      "contactPoint": {
        "@@type": "ContactPoint",
        "telephone": "+8801900000000",
        "contactType": "Customer Service",
        "areaServed": "BD",
        "availableLanguage": ["English", "Bengali"]
      }
    }
  ]
}
</script>
<style>
    @keyframes mega-shine {
        0% { left: -150%; }
        20% { left: 150%; }
        100% { left: 150%; }
    }
    @keyframes rotate-border {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .mega-deal-box {
        position: relative;
        overflow: hidden;
        border: 1px solid #e5e7eb !important;
    }
    .mega-deal-box.rotating-border {
        border: none !important;
        background: #e5e7eb;
        padding: 1px;
    }
    /* The rotating light */
    .rotating-border::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: conic-gradient(transparent, transparent, #ff3f6c, transparent, transparent);
        animation: rotate-border 4s linear infinite;
        z-index: 1;
    }
    /* The inner white background to mask the center */
    .rotating-border::after {
        content: '';
        position: absolute;
        inset: 1px; 
        background: white;
        z-index: 2;
        border-radius: inherit;
    }
    /* Ensure content is above */
    .mega-deal-box > a {
        position: relative;
        z-index: 10;
        background: white;
        display: block;
        height: 100%;
        width: 100%;
    }
    /* Internal shine effect */
    .shine-effect > a::after {
        content: '';
        position: absolute;
        top: 0;
        left: -150%;
        width: 60%;
        height: 100%;
        background: linear-gradient(
            to right,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 255, 255, 0.4) 50%,
            rgba(255, 255, 255, 0) 100%
        );
        transform: skewX(-25deg);
        animation: mega-shine 5s infinite;
        pointer-events: none;
        z-index: 15;
    }
    @keyframes confetti-fall {
        0% { transform: translateY(-20px) rotate(0deg); opacity: 0; }
        10% { opacity: 1; }
        100% { transform: translateY(250px) rotate(720deg); opacity: 0; }
    }
    .confetti-particle {
        position: absolute;
        width: 6px;
        height: 6px;
        border-radius: 1px;
        z-index: 20;
        pointer-events: none;
        animation: confetti-fall 4s linear infinite;
    }
    @keyframes text-shine {
        0% { background-position: 200% center; }
        100% { background-position: -200% center; }
    }
    @keyframes text-pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }
    .text-shine {
        background: linear-gradient(to right, #D32F2F 20%, #FF8A80 40%, #FF8A80 60%, #D32F2F 80%);
        background-size: 200% auto;
        color: #000;
        background-clip: text;
        text-fill-color: transparent;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: text-shine 3s linear infinite, text-pulse 2s ease-in-out infinite;
        display: inline-block;
    }
</style>
@endpush

<!-- Hero Banner Slider (Full Width) -->
<style>
    @media (min-width: 768px) {
        .pc-hero-banner {
            height: 735px !important;
            min-height: 635px !important;
        }
    }
    /* Device specific visibility */
    @media (min-width: 768px) {
        .mobile-only-banner {
            display: none !important;
        }
    }
    @media (max-width: 767px) {
        .desktop-only-banner {
            display: none !important;
        }
    }
    /* Responsive Product Box Aspect Ratio */
    .product-box-aspect {
        aspect-ratio: 1 / 1.15 !important;
    }
    @media (min-width: 640px) {
        .product-box-aspect {
            aspect-ratio: 4 / 5 !important;
        }
    }
</style>
<section class="relative group mt-0 pt-0 border-t-0">
    <div class="w-full mx-auto px-0">
        @php
            $pcBannersCount = $heroBanners->filter(fn($b) => $b->show_on_desktop)->count();
            $mobileBannersCount = $heroBanners->filter(fn($b) => $b->show_on_mobile)->count();
        @endphp
        <div class="swiper hero-swiper relative overflow-hidden bg-transparent rounded-none" 
             data-pc-count="{{ $pcBannersCount }}" 
             data-mobile-count="{{ $mobileBannersCount }}"
             x-ignore>
            <div class="swiper-wrapper">
                @php
                    $displayBanners = $heroBanners;
                    // যদি ব্যানার ২ বা ৩টি হয়, তবে লুপ স্মুথ করার জন্য সেগুলোকে ডুপ্লিকেট করা হচ্ছে
                    if ($heroBanners->count() > 1 && $heroBanners->count() < 4) {
                        $displayBanners = $heroBanners->concat($heroBanners);
                    }
                @endphp
                @foreach($displayBanners as $i => $banner)
                @php
                    $visibilityClass = '';
                    if ($banner->show_on_desktop && !$banner->show_on_mobile) $visibilityClass = 'desktop-only-banner';
                    elseif (!$banner->show_on_desktop && $banner->show_on_mobile) $visibilityClass = 'mobile-only-banner';
                    elseif (!$banner->show_on_desktop && !$banner->show_on_mobile) $visibilityClass = 'hidden';
                @endphp
                <div class="swiper-slide hero-slide {{ $visibilityClass }}">
                    <a href="{{ route('products.index') }}" class="block relative h-44 sm:h-80 pc-hero-banner rounded-none overflow-hidden shadow-none bg-gray-100">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" 
                            class="absolute inset-0 w-full h-full object-cover" 
                            style="object-position: center bottom; will-change: transform;"
                            onerror="this.style.opacity='0'"
                            decoding="sync" 
                            loading="eager"
                            fetchpriority="high">
                        {{-- Skeleton Loader while image loads --}}
                        <div class="absolute inset-0 bg-gray-200 animate-pulse -z-10"></div>

                    </a>
                </div>
                @endforeach
            </div>
            <div class="swiper-button-next !hidden"></div>
            <div class="swiper-button-prev !hidden"></div>
        </div>
    </div>
</section>

<style>
    .promo-bar-container {
        padding-left: 16px !important;
        padding-right: 16px !important;
    }
    @media (min-width: 768px) {
        .promo-bar-container {
            padding-left: 32px !important;
            padding-right: 32px !important;
        }
    }
    @media (min-width: 1280px) {
        .promo-bar-container {
            padding-left: 130px !important;
            padding-right: 133px !important;
        }
    }
</style>
<!-- Mobile Promo Bar -->
<div class="md:hidden bg-white border-b border-gray-100">
    <div class="flex items-center h-12 text-[10px] font-black uppercase tracking-tight text-gray-800">
        <a href="{{ route('products.index') }}" class="flex-1 h-full flex items-center justify-center border-r border-gray-100 bg-gray-50">Shop Now</a>
        <a href="{{ route('products.index', ['gender' => 'Men']) }}" class="flex-1 h-full flex items-center justify-center border-r border-gray-100">Men</a>
        <a href="{{ route('products.index', ['gender' => 'Women']) }}" class="flex-1 h-full flex items-center justify-center border-r border-gray-100">Women</a>
        <a href="{{ route('products.index', ['gender' => 'Kids']) }}" class="flex-1 h-full flex items-center justify-center border-r border-gray-100">Kids</a>
        <a href="{{ route('products.index', ['gender' => 'Sports']) }}" class="flex-1 h-full flex items-center justify-center">Sports</a>
    </div>
</div>

<div class="hidden md:block bg-white border-b border-gray-200 mt-3 overflow-x-auto">
    <div class="min-w-[1000px] xl:min-w-0 max-w-full mx-auto flex items-center h-[90px] text-[13px] xl:text-[15px] font-bold uppercase tracking-wider xl:tracking-widest promo-bar-container">
        <!-- Shop Now -->
        <a href="{{ route('products.index') }}" class="flex-[1.2] xl:flex-[1.5] h-full flex items-center justify-center transition border-r border-gray-200 text-gray-800" style="background-color: #f1f1f1;">
            Shop Now
        </a>
        <!-- Men -->
        <a href="{{ route('products.index', ['gender' => 'Men']) }}" class="flex-1 h-full flex items-center justify-center hover:bg-gray-50 transition border-r border-gray-200 text-gray-800">
            Men
        </a>
        <!-- Women -->
        <a href="{{ route('products.index', ['gender' => 'Women']) }}" class="flex-1 h-full flex items-center justify-center hover:bg-gray-50 transition border-r border-gray-200 text-gray-800">
            Women
        </a>
        <!-- Kids -->
        <a href="{{ route('products.index', ['gender' => 'Kids']) }}" class="flex-1 h-full flex items-center justify-center hover:bg-gray-50 transition border-r border-gray-200 text-gray-800">
            Kids
        </a>
        <!-- Sports -->
        <a href="{{ route('products.index', ['gender' => 'Sports']) }}" class="flex-1 h-full flex items-center justify-center hover:bg-gray-50 transition border-r border-gray-200 text-gray-800">
            Sports
        </a>
        <!-- App Promo -->
        <div class="flex-[1.8] xl:flex-[2] h-full flex items-center justify-center gap-3 xl:gap-6 border-l border-gray-200" style="background-color: #f1f1f1;">
            <span class="text-gray-600 normal-case tracking-normal font-semibold text-xs xl:text-base">Get 5% Off <span class="hidden xl:inline">on App</span></span>
            <div class="flex items-center gap-2 xl:gap-3">
                <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" class="h-7 xl:h-10"></a>
                <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" alt="App Store" class="h-7 xl:h-10"></a>
            </div>
        </div>
    </div>
</div>

<!-- SmartLookBD Style Announcement Strip -->




<!-- Mega Deal Section -->
@if($megaDeals->count() > 0)
<section class="max-w-[1440px] mx-auto px-4 md:px-12 pt-4 md:pt-8 pb-0">
    <a href="{{ route('products.index', ['mega_deal' => 1]) }}" class="mb-4 flex justify-center items-center rounded-sm border border-gray-100 py-4 sm:py-8 hover:brightness-95 transition-all block" style="background: linear-gradient(135deg, #FFF5F5 0%, #FFF9F1 100%); text-decoration: none; border-color: #FFE4E4;">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-black uppercase tracking-[0.3em] mb-0 text-shine" style="font-family: 'Oswald', sans-serif;">
            TOP SELLING
        </h2>
    </a>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4 force-3-cols">
        @foreach($megaDeals as $product)
        @php
            $effect = $product->mega_deal_effect ?? 'all';
            $showShine = in_array($effect, ['shine', 'shine_border', 'all']);
            $showBorder = in_array($effect, ['border', 'shine_border', 'all']);
            $showConfetti = in_array($effect, ['confetti', 'all']);
            
            $animationClasses = 'mega-deal-box';
            if($showBorder) $animationClasses .= ' rotating-border';
            if($showShine) $animationClasses .= ' shine-effect';
        @endphp
        <div class="product-box {{ $animationClasses }} relative group overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-1 hover:border-gray-400 transition-all duration-300 border border-gray-200 rounded-sm product-box-aspect">
            <!-- Celebratory Confetti Effect -->
            @if($showConfetti)
            <div class="absolute inset-0 pointer-events-none overflow-hidden z-20">
                <div class="confetti-particle" style="left: 15%; background: #FF3F6C; animation-delay: 0s; width: 4px; height: 8px;"></div>
                <div class="confetti-particle" style="left: 35%; background: #FFCA28; animation-delay: 1.2s; border-radius: 50%;"></div>
                <div class="confetti-particle" style="left: 55%; background: #33D45E; animation-delay: 0.5s; transform: rotate(45deg);"></div>
                <div class="confetti-particle" style="left: 75%; background: #00D2FF; animation-delay: 2.1s; width: 8px; height: 4px;"></div>
                <div class="confetti-particle" style="left: 90%; background: #9C27B0; animation-delay: 0.8s;"></div>
            </div>
            @endif
            <a href="{{ route('products.show', $product->slug) }}" class="flex h-full w-full items-center justify-center p-0 relative">
                <img src="{{ $product->thumbnail_url }}" 
                    alt="{{ $product->name }}" 
                    decoding="sync"
                    loading="lazy"
                    width="400"
                    height="500"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                
                {{-- Price Badge - Centered Pill Style --}}
                <div class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-white px-3 py-1 rounded-full shadow-md flex flex-col items-center gap-0.5 whitespace-nowrap z-10 font-bold border border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] text-gray-900 font-black">৳{{ number_format($product->effective_price) }}</span>
                        @if($product->effective_price < $product->price)
                        <span class="text-[9px] text-gray-400 line-through">৳{{ number_format($product->price) }}</span>
                        @endif
                    </div>
                </div>

                {{-- Discount Badge --}}
                @if($product->effective_price < $product->price)
                <div class="absolute top-2 left-2 z-20 pointer-events-none">
                    <span class="text-white text-[9px] font-black px-1.5 py-0.5 rounded-[2px] shadow-sm uppercase" style="background-color: #ff3f6c !important; color: #FFFFFF !important; line-height: 1;">
                        -{{ $product->discount_percent }}%
                    </span>
                </div>
                @endif

                {{-- Free Delivery Badge --}}
                @if($product->free_shipping)
                <div class="absolute top-2 right-2 z-10 pointer-events-none">
                    <span class="text-white text-[8px] font-bold px-1.5 py-0.5 rounded-[2px] flex items-center gap-1 shadow-sm uppercase" style="background-color: #009848 !important; color: #FFFFFF !important; line-height: 1;">
                        Free Delivery
                    </span>
                </div>
                @endif
            </a>
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- Categories Grid (Page 1) -->
<section class="max-w-[1440px] mx-auto px-4 md:px-12 pt-2 md:pt-6 pb-0">
    <!-- New Arrival Banner (Clean Text #cc8119 Style) -->
    <a href="{{ route('products.index', ['new' => 1]) }}" class="max-w-[1440px] mx-auto mb-3 flex justify-center items-center rounded-sm border border-gray-100 py-4 sm:py-8 hover:brightness-95 transition-all block" style="background-color: #FBF4E9; text-decoration: none;">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-black uppercase tracking-[0.2em] mb-0" style="font-family: 'Oswald', sans-serif; color: #cc8119;">
            NEW ARRIVAL
        </h2>
    </a>

    <div class="grid grid-cols-3 md:grid-cols-5 lg:grid-cols-6 gap-2 sm:gap-4 force-4-cols">
        @for($i = 1; $i <= 18; $i++)
        @php $category = $page1Cats[$i] ?? null; @endphp
        <div class="category-box relative group overflow-hidden hover:shadow-sm transition-all duration-300 category-mobile-box border-2 border-gray-100/80 rounded-xl @if(!$category) bg-white @endif" style="aspect-ratio: 1/1; backface-visibility: hidden; transform: translateZ(0); -webkit-font-smoothing: antialiased;">
            @if($category)
            <a href="{{ route('category.show', $category->slug) }}" class="flex h-full w-full items-center justify-center p-0 relative">
                <img src="{{ $category->image_url }}" 
                    alt="{{ $category->name }}" 
                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                    style="image-rendering: auto; object-fit: cover; backface-visibility: hidden; transform: scale(1.01); will-change: transform;"
                    decoding="sync"
                    loading="eager"
                    fetchpriority="{{ $i <= 6 ? 'high' : 'auto' }}"
                    width="400"
                    height="400">
                

            </a>
            @else
            <div class="flex h-full w-full items-center justify-center">
                <!-- White empty box -->
            </div>
            @endif
        </div>
        @endfor
    </div>
</section>


@if(!empty($siteSettings->model_notification))
<!-- Model Notification Bar -->
<style>
    .notification-shine-v2 {
        position: relative;
        overflow: hidden;
    }
    .notification-shine-v2::after {
        content: '';
        position: absolute;
        top: 0;
        left: -150%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), rgba(255,255,255,0.35), rgba(255,255,255,0.1), transparent);
        animation: shine-v2 {{ $siteSettings->notification_animation_speed ?? '4' }}s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        transform: skewX(-20deg);
    }
    @keyframes shine-v2 {
        0% { left: -150%; }
        30%, 100% { left: 150%; }
    }

    .notification-pulse {
        animation: pulse-glow-v2 {{ $siteSettings->notification_animation_speed ?? '4' }}s ease-in-out infinite;
    }
    @keyframes pulse-glow-v2 {
        0%, 100% { opacity: 0.9; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.01); }
    }

    .notification-marquee {
        white-space: nowrap;
        animation: marquee-v2 {{ $siteSettings->notification_animation_speed ? ($siteSettings->notification_animation_speed * 3) : '12' }}s linear infinite;
        display: inline-block;
        padding-left: 100%;
    }
    @keyframes marquee-v2 {
        0% { transform: translateX(0); }
        100% { transform: translateX(-100%); }
    }
</style>
<div class="max-w-[1440px] mx-auto px-4 md:px-12 mt-4 sm:mt-6 overflow-hidden">
    <!-- Dynamic Premium Version -->
    <div class="relative py-4 md:py-6 px-4 flex items-center justify-center rounded-sm transition-all shadow-[0_10px_30px_-10px_rgba(0,0,0,0.5)] border border-white/5 overflow-hidden 
        @if($siteSettings->notification_effect === 'shine') notification-shine-v2 @elseif($siteSettings->notification_effect === 'pulse') notification-pulse @endif" 
        style="background-color: {{ $siteSettings->notification_bg_color ?? '#1e1e2d' }};">
        
        <div class="relative z-10 flex flex-col items-center w-full">
            <div class="w-full @if($siteSettings->notification_effect === 'marquee') overflow-hidden @endif">
                <p class="font-black leading-tight tracking-[0.15em] uppercase text-center transition-all duration-300 @if($siteSettings->notification_effect === 'marquee') notification-marquee @endif" 
                   style="color: {{ $siteSettings->notification_text_color ?? '#ffffff' }}; 
                          font-size: {{ $siteSettings->notification_text_size ?? '15' }}px;
                          text-shadow: 0 0 15px {{ $siteSettings->notification_text_color }}44; 
                          font-family: 'Inter', sans-serif;
                          white-space: {{ $siteSettings->notification_effect === 'marquee' ? 'nowrap' : 'pre-wrap' }};">
                    {{ $siteSettings->model_notification }}
                </p>
            </div>
        </div>
    </div>
</div>
@endif


{{-- Category Page 2 - Curated Showcase (SmartLookBD Style) --}}
<style>
    @media (max-width: 767px) {
        .cp3-desktop { display: none !important; }
        .cp3-mobile { display: block !important; }
    }
    @media (min-width: 768px) {
        .cp3-desktop { display: grid !important; }
        .cp3-mobile { display: none !important; }
    }
</style>
<section class="max-w-[1440px] mx-auto px-4 md:px-12 py-4 md:py-6 bg-white">
    <div class="flex flex-col gap-10 md:gap-14">
        @for($catIndex = 1; $catIndex <= 2; $catIndex++)
        @php 
            $category = $page2Cats[$catIndex] ?? null;
            if ($category) {
                $realProducts = $category->products->values();
            }
        @endphp

        @if($category)
        <div class="cp3-mobile w-full">
            <div class="relative rounded-sm overflow-hidden bg-[#f0f0f0] w-full" style="aspect-ratio: 1/1.2 !important;">
                <a href="{{ route('category.show', $category->slug) }}" style="display: block; width: 100%; height: 100%; position: relative; text-decoration: none;">
                    <img src="{{ $category->image_url }}" 
                         alt="{{ $category->name }}"
                         decoding="sync"
                         loading="eager"
                         fetchpriority="high"
                         width="900"
                         height="700"
                         class="bg-gray-100"
                         style="width: 100%; height: 100%; object-fit: cover; will-change: transform;">
                    
                    <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.55) 0%, transparent 45%);"></div>
                    <div style="position: absolute; bottom: 18px; left: 0; right: 0; text-align: center; color: white;">
                        <h2 style="font-size: clamp(20px, 6vw, 30px); font-weight: 900; line-height: 1; margin: 0; text-shadow: 0 4px 15px rgba(0,0,0,1);">
                            {{ $category->name }}
                        </h2>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-2 gap-3 w-full mt-3">
                @for($i = 0; $i < 8; $i++)
                    @php 
                        $product = $realProducts[$i] ?? null;
                        $isDemo = false;
                        if (!$product) {
                            $isDemo = true;
                            $product = (object)[
                                'id' => 'demo-' . $category->id . '-' . $i,
                                'slug' => '#',
                                'thumbnail_url' => 'https://picsum.photos/seed/demo-'.$category->id.'-'.$i.'/400/500',
                                'price' => 1200 + ($i * 50),
                                'old_price' => 1500 + ($i * 50),
                                'name' => 'Demo Product'
                            ];
                        }
                    @endphp
                    <div class="col-span-1 relative bg-[#f9f9f9] rounded-sm overflow-hidden h-fit">
                        <a href="{{ $i == 7 ? route('category.show', $category->slug) : ($isDemo ? '#' : route('products.show', $product->slug)) }}" class="block relative" style="aspect-ratio: 4/5 !important; text-decoration: none;">
                            <img src="{{ ($i == 7 && $category->view_all_image) ? $category->view_all_image_url : $product->thumbnail_url }}" 
                                 alt="{{ $product->name }}"
                                 decoding="async"
                                 loading="lazy"
                                 width="400"
                                 height="500"
                                 class="w-full h-full object-cover">
                            
                            {{-- Price Badge - Centered Pill Style --}}
                            <div class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-white px-3 py-1 rounded-full shadow-md flex flex-col items-center gap-0.5 whitespace-nowrap z-10 font-bold border border-gray-100 {{ $i == 7 ? 'hidden' : '' }}">
                                <div class="flex items-center gap-2">
                                    <span class="text-[11px] text-gray-900 font-black">৳{{ number_format($isDemo ? $product->price : $product->effective_price) }}</span>
                                    @if(!$isDemo && $product->effective_price < $product->price)
                                    <span class="text-[9px] text-gray-400 line-through">৳{{ number_format($product->price) }}</span>
                                    @elseif($isDemo && isset($product->old_price))
                                    <span class="text-[9px] text-gray-400 line-through">৳{{ number_format($product->old_price) }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Discount Badge --}}
                            @if(!$isDemo && $product->effective_price < $product->price)
                            <div class="absolute top-2 left-2 z-20 pointer-events-none">
                                <span class="text-white text-[9px] font-black px-1.5 py-0.5 rounded-[2px] shadow-sm uppercase" style="background-color: #ff3f6c !important; color: #FFFFFF !important; line-height: 1;">
                                    -{{ $product->discount_percent }}%
                                </span>
                            </div>
                            @endif

                            @if($i == 7)
                            <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-white z-20">
                                <div class="text-center font-black tracking-[0.1em] text-2xl md:text-3xl uppercase">
                                    VIEW ALL
                                </div>
                            </div>
                            @endif
                        </a>
                    </div>
                @endfor
            </div>
        </div>

        <div class="cp3-desktop grid grid-cols-2 md:grid-cols-6 gap-3 md:gap-4 w-full">
            
            <!-- Category Banner (Left, spans 2 rows) -->
            <div class="col-span-2 md:col-span-2 md:row-span-2 relative rounded-sm overflow-hidden bg-[#f0f0f0]" style="aspect-ratio: 4/5; grid-row: span 2 / span 2;">
                <a href="{{ route('category.show', $category->slug) }}" style="display: block; width: 100%; height: 100%; position: relative; text-decoration: none;">
                    <img src="{{ $category->image_url }}" 
                         alt="{{ $category->name }}"
                         decoding="async"
                         loading="lazy"
                         width="600"
                         height="900"
                         style="width: 100%; height: 100%; object-fit: cover;">
                    
                    <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, transparent 40%);"></div>
                    <div style="position: absolute; bottom: 18px; left: 0; right: 0; text-align: center; color: white;">
                        <h2 style="font-size: clamp(20px, 4vw, 32px); font-weight: 900; line-height: 1; margin: 0; text-shadow: 0 4px 15px rgba(0,0,0,1);">
                            {{ $category->name }}
                        </h2>
                    </div>
                </a>
            </div>

            <!-- Products (Right, 4 per row next to banner) -->
            @for($i = 0; $i < 8; $i++)
                @php 
                    $product = $realProducts[$i] ?? null;
                    $isDemo = false;
                    if (!$product) {
                        $isDemo = true;
                        $product = (object)[
                            'id' => 'demo-' . $category->id . '-' . $i,
                            'slug' => '#',
                            'thumbnail_url' => 'https://picsum.photos/seed/demo-'.$category->id.'-'.$i.'/400/500',
                            'price' => 1200 + ($i * 50),
                            'old_price' => 1500 + ($i * 50),
                            'name' => 'Demo Product'
                        ];
                    }
                @endphp
                <div class="col-span-1 relative bg-[#f9f9f9] rounded-sm overflow-hidden h-fit">
                    <a href="{{ $i == 7 ? route('category.show', $category->slug) : ($isDemo ? '#' : route('products.show', $product->slug)) }}" class="block relative" style="aspect-ratio: 4/5 !important; text-decoration: none;">
                        <img src="{{ ($i == 7 && $category->view_all_image) ? $category->view_all_image_url : $product->thumbnail_url }}" 
                             alt="{{ $product->name }}"
                             decoding="sync"
                             loading="lazy"
                             width="400"
                             height="500"
                             style="will-change: transform;"
                             class="w-full h-full object-cover">
                        
                        {{-- Price Badge - Centered Pill Style --}}
                        <div class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-white px-3 py-1 rounded-full shadow-md flex flex-col items-center gap-0.5 whitespace-nowrap z-10 font-bold border border-gray-100 {{ $i == 7 ? 'hidden' : '' }}">
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] text-gray-900 font-black">৳{{ number_format($isDemo ? $product->price : $product->effective_price) }}</span>
                                @if(!$isDemo && $product->effective_price < $product->price)
                                <span class="text-[9px] text-gray-400 line-through">৳{{ number_format($product->price) }}</span>
                                @elseif($isDemo && isset($product->old_price))
                                <span class="text-[9px] text-gray-400 line-through">৳{{ number_format($product->old_price) }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Discount Badge --}}
                        @if(!$isDemo && $product->effective_price < $product->price)
                        <div class="absolute top-2 left-2 z-20 pointer-events-none">
                            <span class="text-white text-[9px] font-black px-1.5 py-0.5 rounded-[2px] shadow-sm uppercase" style="background-color: #ff3f6c !important; color: #FFFFFF !important; line-height: 1;">
                                -{{ $product->discount_percent }}%
                            </span>
                        </div>
                        @endif

                        {{-- Free Delivery Badge --}}
                        @if(!$isDemo && $product->free_shipping)
                        <div class="absolute top-2 right-2 z-10 pointer-events-none">
                            <span class="text-white text-[8px] font-bold px-1.5 py-0.5 rounded-[2px] flex items-center gap-1 shadow-sm uppercase" style="background-color: #009848 !important; color: #FFFFFF !important; line-height: 1;">
                                Free Delivery
                            </span>
                        </div>
                        @endif

                        @if($i == 7)
                        <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-white z-20">
                            <div class="text-center font-black tracking-[0.1em] text-2xl md:text-3xl uppercase">
                                VIEW ALL
                            </div>
                        </div>
                        @endif
                    </a>
                </div>
            @endfor
        </div>
        @else
        <div class="w-full h-[300px] md:h-[500px] bg-white border border-gray-100 rounded-sm">
            <!-- White empty box -->
        </div>
        @endif
        @endfor
</section>

@if($promoBanners->isNotEmpty())

<!-- Middle Banner (Slider with Lighting Effect) -->
<section class="max-w-[1440px] mx-auto px-4 md:px-12 pt-0 pb-0">
    <div class="promo-slider-container relative rounded-sm p-0 overflow-hidden shadow-lg border border-gray-100 promo-banner-custom-height">
        
        <div class="swiper promo-swiper relative h-full w-full bg-green-50 rounded-[3px] overflow-hidden">
            <div class="swiper-wrapper">
                @foreach($promoBanners as $banner)
                @php
                    $promoVisibilityClass = '';
                    if ($banner->show_on_desktop && !$banner->show_on_mobile) $promoVisibilityClass = 'desktop-only-banner';
                    elseif (!$banner->show_on_desktop && $banner->show_on_mobile) $promoVisibilityClass = 'mobile-only-banner';
                    elseif (!$banner->show_on_desktop && !$banner->show_on_mobile) $promoVisibilityClass = 'hidden';
                @endphp
                <div class="swiper-slide h-full relative overflow-hidden border-[0.5px] border-black/[0.03] {{ $promoVisibilityClass }}">
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-transparent to-transparent"></div>
                    <div class="absolute inset-0 flex items-center px-8 sm:px-14">
                        <div class="text-white max-w-lg">
                            @if($banner->badge_text)
                            <span class="bg-[#45b86f] text-white text-[10px] sm:text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest mb-3 inline-block shadow-sm">{{ $banner->badge_text }}</span>
                            @endif
                            <h2 class="text-xl sm:text-4xl font-black leading-tight drop-shadow-md">{!! nl2br(e($banner->title)) !!}</h2>
                            @if($banner->subtitle)
                            <p class="text-gray-200 text-xs sm:text-sm mt-2 mb-4 font-medium drop-shadow-sm">{{ $banner->subtitle }}</p>
                            @endif
                            @if($banner->button_text)
                            <a href="{{ $banner->link ?? route('products.index') }}" class="inline-flex items-center gap-2 bg-[#45b86f] hover:bg-white hover:text-[#45b86f] text-white px-5 py-2 sm:px-7 sm:py-3 rounded-full text-xs sm:text-sm font-black transition-all duration-300 shadow-xl active:scale-95">
                                {{ $banner->button_text }} 
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination !bottom-4"></div>
        </div>
    </div>
</section>
@endif


{{-- Category Page 3 - Curated Showcase (SmartLookBD Style - Duplicate of Page 2) --}}
<section class="max-w-[1440px] mx-auto px-4 md:px-12 py-4 md:py-6 bg-white">
    <div class="flex flex-col gap-10 md:gap-14">
        @for($catIndex = 1; $catIndex <= 3; $catIndex++)
        @php 
            $category = $page3Cats[$catIndex] ?? null;
            if ($category) {
                $realProducts = $category->products->values();
            }
        @endphp

        @if($category)
        <div class="cp3-mobile w-full">
            <div class="relative rounded-sm overflow-hidden bg-[#f0f0f0] w-full" style="aspect-ratio: 1/1.2 !important;">
                <a href="{{ route('category.show', $category->slug) }}" style="display: block; width: 100%; height: 100%; position: relative; text-decoration: none;">
                    <img src="{{ $category->image_url }}" 
                         alt="{{ $category->name }}"
                         decoding="sync"
                         loading="lazy"
                         width="900"
                         height="700"
                         style="width: 100%; height: 100%; object-fit: cover; will-change: transform;">
                    
                    <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.55) 0%, transparent 45%);"></div>
                    <div style="position: absolute; bottom: 18px; left: 0; right: 0; text-align: center; color: white;">
                        <h2 style="font-size: clamp(20px, 6vw, 30px); font-weight: 900; line-height: 1; margin: 0; text-shadow: 0 4px 15px rgba(0,0,0,1);">
                            {{ $category->name }}
                        </h2>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-2 gap-3 w-full mt-3">
                @for($i = 0; $i < 8; $i++)
                    @php 
                        $product = $realProducts[$i] ?? null;
                        $isDemo = false;
                        if (!$product) {
                            $isDemo = true;
                            $product = (object)[
                                'id' => 'demo-' . $category->id . '-' . $i,
                                'slug' => '#',
                                'thumbnail_url' => 'https://picsum.photos/seed/demo-'.$category->id.'-'.$i.'/400/500',
                                'price' => 1200 + ($i * 50),
                                'old_price' => 1500 + ($i * 50),
                                'name' => 'Demo Product'
                            ];
                        }
                    @endphp
                    <div class="col-span-1 relative bg-[#f9f9f9] rounded-sm overflow-hidden h-fit">
                        <a href="{{ $i == 7 ? route('category.show', $category->slug) : ($isDemo ? '#' : route('products.show', $product->slug)) }}" class="block relative" style="aspect-ratio: 4/5 !important; text-decoration: none;">
                            <img src="{{ ($i == 7 && $category->view_all_image) ? $category->view_all_image_url : $product->thumbnail_url }}" 
                                 alt="{{ $product->name }}"
                                 decoding="sync"
                                 loading="lazy"
                                 width="400"
                                 height="500"
                                 style="will-change: transform;"
                                 class="w-full h-full object-cover">
                            
                            {{-- Price Badge - Centered Pill Style --}}
                            <div class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-white px-3 py-1 rounded-full shadow-md flex flex-col items-center gap-0.5 whitespace-nowrap z-10 font-bold border border-gray-100 {{ $i == 7 ? 'hidden' : '' }}">
                                <div class="flex items-center gap-2">
                                    <span class="text-[11px] text-gray-900 font-black">৳{{ number_format($isDemo ? $product->price : $product->effective_price) }}</span>
                                    @if(!$isDemo && $product->effective_price < $product->price)
                                    <span class="text-[9px] text-gray-400 line-through">৳{{ number_format($product->price) }}</span>
                                    @elseif($isDemo && isset($product->old_price))
                                    <span class="text-[9px] text-gray-400 line-through">৳{{ number_format($product->old_price) }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Discount Badge --}}
                            @if(!$isDemo && $product->effective_price < $product->price)
                            <div class="absolute top-2 left-2 z-20 pointer-events-none">
                                <span class="text-white text-[9px] font-black px-1.5 py-0.5 rounded-[2px] shadow-sm uppercase" style="background-color: #ff3f6c !important; color: #FFFFFF !important; line-height: 1;">
                                    -{{ $product->discount_percent }}%
                                </span>
                            </div>
                            @endif

                            @if($i == 7)
                            <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-white z-20">
                                <div class="text-center font-black tracking-[0.1em] text-2xl md:text-3xl uppercase">
                                    VIEW ALL
                                </div>
                            </div>
                            @endif
                        </a>
                    </div>
                @endfor
            </div>
        </div>

        <div class="cp3-desktop grid grid-cols-2 md:grid-cols-6 gap-3 md:gap-4 w-full">
            
            <!-- Category Banner (Left, spans 2 rows) -->
            <div class="col-span-2 md:col-span-2 md:row-span-2 relative rounded-sm overflow-hidden bg-[#f0f0f0]" style="aspect-ratio: 4/5; grid-row: span 2 / span 2;">
                <a href="{{ route('category.show', $category->slug) }}" style="display: block; width: 100%; height: 100%; position: relative; text-decoration: none;">
                    <img src="{{ $category->image_url }}" 
                         alt="{{ $category->name }}"
                         decoding="sync"
                         loading="lazy"
                         width="600"
                         height="900"
                         style="width: 100%; height: 100%; object-fit: cover; will-change: transform;">
                    
                    <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, transparent 40%);"></div>
                    <div style="position: absolute; bottom: 18px; left: 0; right: 0; text-align: center; color: white;">
                        <h2 style="font-size: clamp(20px, 4vw, 32px); font-weight: 900; line-height: 1; margin: 0; text-shadow: 0 4px 15px rgba(0,0,0,1);">
                            {{ $category->name }}
                        </h2>
                    </div>
                </a>
            </div>

            <!-- Products (Right, 4 per row next to banner) -->
            @for($i = 0; $i < 8; $i++)
                @php 
                    $product = $realProducts[$i] ?? null;
                    $isDemo = false;
                    if (!$product) {
                        $isDemo = true;
                        $product = (object)[
                            'id' => 'demo-' . $category->id . '-' . $i,
                            'slug' => '#',
                            'thumbnail_url' => 'https://picsum.photos/seed/demo-'.$category->id.'-'.$i.'/400/500',
                            'price' => 1200 + ($i * 50),
                            'old_price' => 1500 + ($i * 50),
                            'name' => 'Demo Product'
                        ];
                    }
                @endphp
                <div class="col-span-1 relative bg-[#f9f9f9] rounded-sm overflow-hidden h-fit">
                    <a href="{{ $i == 7 ? route('category.show', $category->slug) : ($isDemo ? '#' : route('products.show', $product->slug)) }}" class="block relative" style="aspect-ratio: 4/5 !important; text-decoration: none;">
                        <img src="{{ ($i == 7 && $category->view_all_image) ? $category->view_all_image_url : $product->thumbnail_url }}" 
                             alt="{{ $product->name }}"
                             decoding="sync"
                             loading="lazy"
                             width="400"
                             height="500"
                             style="will-change: transform;"
                             class="w-full h-full object-cover">
                        
                        {{-- Price Badge - Centered Pill Style --}}
                        <div class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-white px-3 py-1 rounded-full shadow-md flex flex-col items-center gap-0.5 whitespace-nowrap z-10 font-bold border border-gray-100 {{ $i == 7 ? 'hidden' : '' }}">
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] text-gray-900 font-black">৳{{ number_format($isDemo ? $product->price : $product->effective_price) }}</span>
                                @if(!$isDemo && $product->effective_price < $product->price)
                                <span class="text-[9px] text-gray-400 line-through">৳{{ number_format($product->price) }}</span>
                                @elseif($isDemo && isset($product->old_price))
                                <span class="text-[9px] text-gray-400 line-through">৳{{ number_format($product->old_price) }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Discount Badge --}}
                        @if(!$isDemo && $product->effective_price < $product->price)
                        <div class="absolute top-2 left-2 z-20 pointer-events-none">
                            <span class="text-white text-[9px] font-black px-1.5 py-0.5 rounded-[2px] shadow-sm uppercase" style="background-color: #ff3f6c !important; color: #FFFFFF !important; line-height: 1;">
                                -{{ $product->discount_percent }}%
                            </span>
                        </div>
                        @endif

                        {{-- Free Delivery Badge --}}
                        @if(!$isDemo && $product->free_shipping)
                        <div class="absolute top-2 right-2 z-10 pointer-events-none">
                            <span class="text-white text-[8px] font-bold px-1.5 py-0.5 rounded-[2px] flex items-center gap-1 shadow-sm uppercase" style="background-color: #009848 !important; color: #FFFFFF !important; line-height: 1;">
                                Free Delivery
                            </span>
                        </div>
                        @endif

                        @if($i == 7)
                        <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-white z-20">
                            <div class="text-center font-black tracking-[0.1em] text-2xl md:text-3xl uppercase">
                                VIEW ALL
                            </div>
                        </div>
                        @endif
                    </a>
                </div>
            @endfor
        </div>
        @else
        <div class="w-full h-[300px] md:h-[500px] bg-white border border-gray-100 rounded-sm">
            <!-- White empty box -->
        </div>
        @endif
        @endfor
    </div>
</section>

{{-- Category Page 4 - 3-Column Category Grid Showcase (SmartLookBD Style) --}}
<section class="max-w-[1440px] mx-auto px-4 md:px-12 pt-4 md:pt-6 pb-0 bg-white">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
        @for($i = 1; $i <= 3; $i++)
        @php $category = $page4Cats[$i] ?? null; @endphp
        <div class="relative overflow-hidden rounded-[2px] shadow-sm @if(!$category) bg-white border border-gray-100 h-[300px] md:h-auto @endif" style="aspect-ratio: 1/1;">
            @if($category)
            <a href="{{ route('category.show', $category->slug) }}" 
               class="group relative block w-full h-full transition-all duration-300 hover:-translate-y-1" 
               style="text-decoration: none; background-color: #f1f1f1;">
                
                {{-- Category Image --}}
                <img src="{{ $category->image_url }}" 
                     alt="{{ $category->name }}"
                     decoding="sync"
                     loading="lazy"
                     width="800"
                     height="800"
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                
                {{-- Dark Gradient Overlay at Bottom --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-80"></div>
    
                {{-- Category Name Text --}}
                <div class="absolute bottom-5 left-0 right-0 text-center px-4">
                    <h2 class="text-white text-[18px] md:text-[22px] font-black tracking-tight transition-all duration-300 group-hover:scale-110" 
                        style="text-shadow: 0 2px 10px rgba(0,0,0,0.9), 0 0 2px rgba(0,0,0,0.8); font-family: 'Oswald', sans-serif;">
                        {{ $category->name }}
                    </h2>
                </div>
                
                {{-- Optional subtle highlight on hover --}}
                <div class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </a>
            @endif
        </div>
        @endfor
    </div>
</section>

<!-- Highlight Banners (Page 5) -->
<section class="max-w-[1440px] mx-auto px-4 md:px-12 pt-4 md:pt-6 pb-0">
    <div class="space-y-6">

        <style>
            .banner-wide-mobile { height: 180px !important; }
            @media (min-width: 768px) { .banner-wide-mobile { height: 280px !important; } }
        </style>
        <!-- Top Row: 2 Large Banners -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
            @for($i = 1; $i <= 2; $i++)
            @php $category = $category = $page5Cats[$i] ?? null; @endphp
            <div class="relative group overflow-hidden rounded-[4px] w-full block banner-wide-mobile @if(!$category) bg-white border border-gray-100 @endif">
                @if($category)
                <a href="{{ route('category.show', $category->slug) }}" class="block h-full w-full">
                    <img src="{{ $category->image_url }}" 
                        alt="{{ $category->name }}" 
                        decoding="async"
                        loading="lazy"
                        width="800"
                        height="400"
                        class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-80"></div>
                    
                    <div class="absolute inset-0 flex flex-col justify-end items-center text-center pb-4 px-4">
                        <span class="text-white text-[18px] md:text-[24px] font-bold tracking-tight transition-all duration-300 group-hover:scale-110" style="text-shadow: 0 2px 10px rgba(0,0,0,0.9), 0 0 2px rgba(0,0,0,0.8);">{{ $category->name }}</span>
                    </div>
                </a>
                @endif
            </div>
            @endfor
        </div>
        
        <!-- Bottom Row 1: 3 Banners -->
        <style>
            .pc-bot-banner { height: 280px; }
            @media (min-width: 768px) {
                .pc-bot-banner { height: 400px !important; }
            }
        </style>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mt-3 sm:mt-4">
            @for($i = 3; $i <= 5; $i++)
            @php $category = $page5Cats[$i] ?? null; @endphp
            <div class="relative group overflow-hidden rounded-[4px] block pc-bot-banner @if(!$category) bg-white border border-gray-100 @endif">
                @if($category)
                <a href="{{ route('category.show', $category->slug) }}" class="block h-full w-full">
                    <img src="{{ $category->image_url }}" 
                        alt="{{ $category->name }}" 
                        decoding="async"
                        loading="lazy"
                        width="600"
                        height="800"
                        class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-80"></div>
                    
                    <div class="absolute inset-0 flex flex-col justify-end items-center text-center pb-5 px-2">
                        <span class="text-white text-[18px] md:text-[24px] font-bold tracking-tight transition-all duration-300 group-hover:scale-110" style="text-shadow: 0 2px 10px rgba(0,0,0,0.9), 0 0 2px rgba(0,0,0,0.8);">{{ $category->name }}</span>
                    </div>
                </a>
                @endif
            </div>
            @endfor
        </div>

    </div>
</section>

{{-- Unified Product Showcase for the 3 Categories --}}
@php 
    $combinedProducts = collect();
    foreach($page4Cats as $cat) {
        $combinedProducts = $combinedProducts->concat($cat->products);
    }
    $finalDisplayProducts = $combinedProducts->unique('id')->take(10);
@endphp

@if($finalDisplayProducts->count() > 0)
<section class="max-w-[1440px] mx-auto px-4 md:px-12 pt-4 md:pt-6 pb-0 bg-white">
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 md:gap-4 w-full">
        @foreach($finalDisplayProducts as $product)
        <div class="product-box relative group overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-1 hover:border-gray-400 transition-all duration-300 border border-gray-200 rounded-sm product-box-aspect">
            <a href="{{ route('products.show', $product->slug) }}" class="flex h-full w-full items-center justify-center p-0 relative">
                <img src="{{ $product->thumbnail_url }}" 
                    alt="{{ $product->name }}" 
                    decoding="sync"
                    loading="lazy"
                    width="400"
                    height="500"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                
                {{-- Price Badge - Centered Pill Style --}}
                <div class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-white px-3 py-1 rounded-full shadow-md flex flex-col items-center gap-0.5 whitespace-nowrap z-10 font-bold border border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] text-gray-900 font-black">৳{{ number_format($product->effective_price) }}</span>
                        @if($product->effective_price < $product->price)
                        <span class="text-[9px] text-gray-400 line-through">৳{{ number_format($product->price) }}</span>
                        @endif
                    </div>
                </div>

                {{-- Discount Badge --}}
                @if($product->effective_price < $product->price)
                <div class="absolute top-2 left-2 z-20 pointer-events-none">
                    <span class="text-white text-[9px] font-black px-1.5 py-0.5 rounded-[2px] shadow-sm uppercase" style="background-color: #ff3f6c !important; color: #FFFFFF !important; line-height: 1;">
                        -{{ $product->discount_percent }}%
                    </span>
                </div>
                @endif

                {{-- Free Delivery Badge --}}
                @if($product->free_shipping)
                <div class="absolute top-2 right-2 z-10 pointer-events-none">
                    <span class="text-white text-[8px] font-bold px-1.5 py-0.5 rounded-[2px] flex items-center gap-1 shadow-sm uppercase" style="background-color: #009848 !important; color: #FFFFFF !important; line-height: 1;">
                        Free Delivery
                    </span>
                </div>
                @endif

            </a>
        </div>
        @endforeach
    </div>
</section>
@endif



<!-- Brand Philosophy Section (SmartLookBD Style) -->
<section class="max-w-[1440px] mx-auto px-4 md:px-12 py-2 md:py-4 bg-white overflow-hidden">
    <div class="flex flex-col lg:flex-row items-center gap-6 lg:gap-14">
        <!-- Text Content -->
        <div class="w-full lg:w-[60%]">
            <div class="mb-2">
                <h2 class="text-[22px] md:text-[45px] font-bold text-gray-900 inline-flex items-center gap-1.5 leading-none">
                    SmartLookBD <span class="text-[#45b86f] font-light text-2xl md:text-5xl translate-y-[2px]">›</span>
                </h2>
            </div>
            
            <h3 class="text-base md:text-[32px] text-gray-800 font-normal leading-tight mb-4 md:max-w-2xl" style="font-family: 'Inter', sans-serif;">
                Because comfort and confidence go hand in hand.
            </h3>
            
            <p class="text-[#5b6c8f] text-[13px] md:text-[19px] leading-relaxed font-normal md:max-w-3xl">
                We focus on carefully selecting the best clothing that is comfortable, looks great, and makes you confident. Apart from the fabric, design and fit, we go through strict quality control parameters to give you what you truly deserve. The power of a good outfit is how it can influence your perception of yourself.
            </p>
        </div>
 
        <!-- Image Content (Stack of fabric) -->
        <div class="w-full lg:w-[40%] flex justify-center lg:justify-end mt-2 lg:mt-0">
            <div class="w-full rounded-none overflow-hidden" style="height: 260px;">
                <img src="{{ asset('638b1d9333f59.png') }}" 
                     alt="SmartLookBD Brand Philosophy" 
                     class="w-full h-full object-cover transition-transform duration-700 hover:scale-105"
                     loading="lazy">
            </div>
        </div>
    </div>
</section>


<style>
    /* Premium Modern Hero Swiper Styles */
    .hero-swiper {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        min-height: 240px !important;
        overflow: hidden !important;
    }
    @media (max-width: 767px) {
        .hero-slide a {
            height: 240px !important;
        }
    }
    @media (min-width: 1024px) {
        .hero-swiper {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            min-height: 614px !important;
        }
        .hero-slide a {
            height: 735px !important;
        }
    }
    @media (max-width: 767px) {
        .new-arrival-mobile-box {
            aspect-ratio: 1 / 1.4 !important;
        }
        .category-mobile-box {
            aspect-ratio: 1 / 1.1 !important; 
        }
    }
    .hero-slide {
        opacity: 1;
    }
    .hero-slide.swiper-slide-active {
        opacity: 1;
        z-index: 20;
    }
    
    /* Modern Pill Pagination */
    .hero-swiper .swiper-pagination-bullet {
        width: 8px;
        height: 8px;
        background: #333333;
        opacity: 0.2;
        transition: all 0.3s ease;
        border-radius: 4px;
    }
    .hero-swiper .swiper-pagination-bullet-active {
        width: 30px;
        opacity: 1;
        background: #45b86f;
    }

    /* Floating Badge for Hero */
    .hero-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(69, 184, 111, 0.9);
        backdrop-blur: 10px;
        color: white;
        padding: 6px 15px;
        border-radius: 100px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        box-shadow: 0 10px 20px rgba(69,184,111,0.3);
        z-index: 100;
        animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    .promo-banner-custom-height { height: 150px; }
    @media (min-width: 640px) { .promo-banner-custom-height { height: 320px; } }
    @media (min-width: 1024px) { .promo-banner-custom-height { height: 400px; } }
    
    .lighting-animation {
        background: conic-gradient(from 0deg, transparent 70%, #FFD700 85%, #45b86f 100%);
        animation: rotate-border 2s linear infinite;
    }
    @media (max-width: 640px) {
        .lighting-animation { animation: none !important; background: transparent !important; }
        .promo-slider-container { border: 1.5px solid #45b86f !important; padding: 0 !important; }
    }

    /* Category Card Lighting Border */
    .cat-card-animated {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .cat-card-animated::before {
        content: '';
        position: absolute;
        width: 200%;
        height: 200%;
        top: -50%;
        left: -50%;
        background: conic-gradient(transparent, transparent, transparent, #FFD700, #45b86f);
        animation: rotate-border 4s linear infinite;
        z-index: 0;
    }
    .cat-card-inner {
        position: relative;
        z-index: 10;
        height: calc(100% - 4px);
        width: calc(100% - 4px);
        margin: 2px;
        background: white;
        border-radius: 0.95rem;
        border: 1px solid black;
        overflow: hidden;
    }
    @keyframes rotate-border {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    /* Category Name Badge Effect */
    .cat-name-badge {
        position: relative;
        overflow: hidden;
    }
    .cat-name-badge::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            45deg,
            transparent 45%,
            rgba(255, 255, 255, 0.4) 50%,
            transparent 55%
        );
        transform: rotate(-45deg);
        animation: shimmer-text 3s infinite;
    }
    @keyframes shimmer-text {
        0% { transform: translateX(-150%) rotate(-45deg); }
        100% { transform: translateX(150%) rotate(-45deg); }
    }
    .promo-swiper .swiper-pagination-bullet { width: 8px; height: 8px; background: rgba(255,255,255,0.5); opacity: 1; }
    .promo-swiper .swiper-pagination-bullet-active { background: #45b86f; width: 24px; border-radius: 4px; }
</style>


<!-- New Arrivals -->
<section class="max-w-[1440px] mx-auto px-4 md:px-12 pt-4 md:pt-6 pb-10">
    {{-- Removed New Arrivals Heading as requested --}}
    <div class="relative flex flex-col items-center justify-center text-center mb-6 hidden">
        <h2 class="text-2xl sm:text-3xl font-black text-gray-900">New Arrivals</h2>
        <p class="text-gray-500 text-sm mt-1">Fresh products added to our store</p>
        <a href="{{ route('products.index') }}?new=1" class="absolute right-0 hidden sm:flex items-center gap-2 text-sm font-medium text-[#45b86f] hover:text-[#3ba35f] transition">
            View All <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
        @foreach($newArrivals as $i => $product)
        <div class="product-box relative group overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-1 hover:border-gray-400 transition-all duration-300 border border-gray-200 rounded-sm product-box-aspect">
            <a href="{{ route('products.show', $product->slug) }}" class="flex h-full w-full items-center justify-center p-0 relative">
                <img src="{{ $product->thumbnail_url }}" 
                    alt="{{ $product->name }}" 
                    decoding="sync"
                    loading="lazy"
                    width="400"
                    height="500"
                    style="will-change: transform;"
                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                
                {{-- Mobile-Style Price Badge (Pill at bottom) --}}
                <div class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-white px-3 py-1 rounded-full shadow-md flex flex-col items-center gap-0.5 whitespace-nowrap z-10 font-bold border border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] text-gray-900 font-black">৳{{ number_format($product->effective_price) }}</span>
                        @if($product->effective_price < $product->price)
                        <span class="text-[9px] text-gray-400 line-through">৳{{ number_format($product->price) }}</span>
                        @endif
                    </div>
                </div>

                {{-- Discount Badge --}}
                @if($product->effective_price < $product->price)
                <div class="absolute top-2 left-2 z-20 pointer-events-none">
                    <span class="text-white text-[9px] font-black px-1.5 py-0.5 rounded-[2px] shadow-sm uppercase" style="background-color: #ff3f6c !important; color: #FFFFFF !important; line-height: 1;">
                        -{{ $product->discount_percent }}%
                    </span>
                </div>
                @endif

                {{-- Free Delivery Badge --}}
                @if($product->free_shipping)
                <div class="absolute top-2 right-2 z-10 pointer-events-none">
                    <span class="text-white text-[8px] font-bold px-1.5 py-0.5 rounded-[2px] flex items-center gap-1 shadow-sm uppercase" style="background-color: #009848 !important; color: #FFFFFF !important; line-height: 1;">
                        Free Delivery
                    </span>
                </div>
                @endif

                {{-- Hover Name Overlay (PC only hidden/shown) --}}
                <div class="absolute inset-x-0 bottom-0 p-2 bg-gradient-to-t from-black/80 via-black/40 to-transparent pt-6 translate-y-full group-hover:translate-y-0 transition-transform duration-300 hidden md:block">
                    <p class="text-[10px] font-bold text-white uppercase tracking-tight truncate">{{ $product->name }}</p>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</section>

<!-- Customer Reviews -->
<section style="background-color: #FFFFFF !important;" class="py-12 md:py-16">
    <div class="max-w-[1440px] mx-auto px-4 md:px-12">
        <div class="text-center mb-8">
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900">What Our Customers Say</h2>
            <p class="text-gray-500 text-sm mt-2">Trusted by thousands of happy shoppers</p>
            <div class="flex items-center justify-center gap-1 mt-3">
                <span class="text-yellow-400">★★★★★</span>
                <span class="text-gray-500 text-sm ml-2">4.8/5 from 10,000+ reviews</span>
            </div>
        </div>

        <div class="flex flex-wrap justify-center gap-4 sm:gap-8 mt-4">
            @foreach(\App\Models\Category::where('is_active', true)->orderBy('sort_order')->take(8)->get() as $cat)
            <a href="{{ route('category.show', $cat->slug) }}" class="group flex flex-col items-center gap-2 sm:gap-3">
                <div class="relative w-16 h-16 sm:w-16 sm:h-16 rounded-full border-[2px] border-[#45b86f] group-hover:border-[#002f4b] p-[2px] flex items-center justify-center mx-auto transition-all duration-300 shadow-sm bg-green-50 hover-border-blue overflow-hidden">
                    <img src="{{ $cat->image_url }}" 
                        alt="{{ $cat->name }}" 
                        class="w-full h-full rounded-full object-cover" 
                        decoding="sync"
                        loading="lazy"
                        width="100"
                        height="100"
                        style="will-change: transform;">
                </div>
                <p class="text-[11px] sm:text-sm font-medium sm:font-semibold text-gray-600 sm:text-gray-700 group-hover:text-[#45b86f] transition-colors leading-tight w-full truncate px-1 text-center">{{ $cat->name }}</p>
            </a>
            @endforeach
        </div>

        <div class="swiper review-swiper mt-8 overflow-hidden">
            <div class="swiper-wrapper pb-4">
                @foreach($reviews as $i => $review)
                <div class="swiper-slide">
                    <div class="review-card bg-green-50 rounded-2xl p-6 shadow-sm h-full flex flex-col border border-gray-200">
                        <div class="flex items-center gap-1 mb-3">
                            @for($s = 1; $s <= 5; $s++)
                            <svg class="w-4 h-4 {{ $s <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <h4 class="font-semibold text-gray-900 text-sm mb-2">{{ $review->title ?? 'Great Product!' }}</h4>
                        <p class="text-gray-500 text-sm leading-relaxed mb-4 flex-1">{{ $review->body }}</p>
                        <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                {{ substr($review->reviewer_name ?? 'U', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $review->reviewer_name ?? 'Happy Customer' }}</p>
                                <p class="text-xs text-gray-400">Verified Buyer · {{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <!-- Pagination dots -->
            <div class="swiper-pagination mt-4"></div>
        </div>
    </div>
</section>



@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Nobin Style Hero Swiper (Ultra Stable Reliable Loop)
    const heroSwiper = new Swiper('.hero-swiper', {
        slidesPerView: 1,
        loop: true,
        grabCursor: true,
        autoplay: { 
            delay: 4500, 
            disableOnInteraction: false,
            stopOnLastSlide: false, 
            pauseOnMouseEnter: false,
        },
        speed: 1000,
        observer: true,
        observeParents: true,
        resizeObserver: true,
        watchSlidesProgress: true,
        watchSlidesVisibility: true,
        updateOnWindowResize: true,
        watchOverflow: true, // ১টি স্লাইড থাকলে স্লাইডার ডিজেবল করবে
        on: {
            init: function() {
                const isMobile = window.matchMedia('(max-width: 767px)').matches;
                const pcCount = parseInt(this.el.dataset.pcCount) || 0;
                const mobileCount = parseInt(this.el.dataset.mobileCount) || 0;
                
                const currentCount = isMobile ? mobileCount : pcCount;

                // যদি বর্তমানে ১টি বা ০টি ব্যানার থাকে, তবে স্ক্রলিং বন্ধ
                if (currentCount <= 1) {
                    this.autoplay.stop();
                    this.allowTouchMove = false;
                    this.params.loop = false;
                    this.params.autoplay.enabled = false;
                    this.update();
                }
            },
            autoplayTimeLeft(s, time, progress) {
                if (progress === 0) s.autoplay.run();
            }
        }
    });
    
    // Explicitly start autoplay only if not disabled by smart scroll logic
    if (heroSwiper && heroSwiper.autoplay && heroSwiper.params.autoplay.enabled !== false) {
        // অতিরিক্ত চেক: যদি স্লাইডারটি ডিজেবল করা না থাকে
        if (heroSwiper.allowTouchMove !== false) {
            heroSwiper.autoplay.start();
        }
    }

    // Flash Sale Swiper
    new Swiper('.flash-swiper', {
        slidesPerView: 'auto',
        spaceBetween: 16,
        freeMode: true,
        navigation: { nextEl: '.flash-swiper .swiper-button-next', prevEl: '.flash-swiper .swiper-button-prev' },
    });

    // Category Swiper (Continuous Auto Scroll)
    const catContainer = document.querySelector('.category-swiper');
    const catCount = catContainer ? parseInt(catContainer.dataset.count) : 0;
    new Swiper('.category-swiper', {
        slidesPerView: Math.min(catCount, 3),
        spaceBetween: 10,
        loop: true,
        watchOverflow: true,
        speed: 800,
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
        },
        allowTouchMove: true, 
        grabCursor: true,
        centerInsufficientSlides: true,
        breakpoints: {
            640: {
                slidesPerView: Math.min(catCount, 5.5),
                spaceBetween: 16,
            },
            1024: {
                slidesPerView: Math.min(catCount, 6),
                spaceBetween: 24,
            }
        }
    });

    // Review Swiper (Horizontal scroll with auto-play, 1 card at a time on mobile)
    new Swiper('.review-swiper', {
        slidesPerView: 1,
        spaceBetween: 16,
        grabCursor: true,
        loop: true,
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.review-swiper .swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 24,
            }
        }
    });

    // Promo Swiper (Middle Banner)
    const promoSwiper = new Swiper('.promo-swiper', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
            pauseOnMouseEnter: true
        },
        pagination: {
            el: '.promo-swiper .swiper-pagination',
            clickable: true,
        },
        effect: 'slide',
        speed: 800,
    });
    
    // Explicitly start autoplay in case it's stalled
    if (promoSwiper.autoplay) {
        promoSwiper.autoplay.start();
    }
});
</script>
@endpush
