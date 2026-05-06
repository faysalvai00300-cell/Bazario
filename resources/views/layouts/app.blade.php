<!DOCTYPE html>
<html lang="en" class="overflow-x-hidden">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Speed Optimization: DNS Prefetch & Preconnect -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @php
        $defaultTitle = $siteSettings->seo_meta_title ?? 'SmartLookBD - Premium Online Shopping in Bangladesh';
        $defaultDesc = $siteSettings->seo_meta_description ?? 'Shop the best perfumes, fragrances, and luxury items at SmartLookBD. Authentic products with fast delivery in Bangladesh.';
        $defaultKeywords = $siteSettings->seo_meta_keywords ?? 'SmartLookBD, Smart Look BD, SmartLook BD, SmartLukBD, Smart BD, LookBD, Smart Look Bangladesh, SmartLookBD.com, SmartLook BD Online Shopping, Smart লুক বিডি, smartlook, bd smartlock, smrtlok, smartlock, smartlok';

        $pageTitle = View::hasSection('meta_title') ? View::getSection('meta_title') : (View::hasSection('title') ? View::getSection('title') : $defaultTitle);
        $pageDesc = View::hasSection('meta_description') ? View::getSection('meta_description') : $defaultDesc;
        $pageKeywords = View::hasSection('meta_keywords') ? View::getSection('meta_keywords') : $defaultKeywords;
    @endphp
    
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDesc }}">
    <meta name="keywords" content="{{ $pageKeywords }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="author" content="SmartLookBD">
    <meta name="geo.region" content="BD">
    <meta name="geo.placename" content="Dhaka">

    <!-- Open Graph / Facebook -->
    <meta property="og:site_name" content="SmartLookBD">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:image" content="@yield('og_image', asset('final logo.jpeg'))">
    <meta property="og:locale" content="en_US">
    <meta property="og:locale:alternate" content="bn_BD">

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('final logo.jpeg') }}">
    <link rel="shortcut icon" href="{{ asset('final logo.jpeg') }}">
    <link rel="apple-touch-icon" href="{{ asset('final logo.jpeg') }}">
    <link rel="image_src" href="{{ asset('final logo.jpeg') }}">
    <!-- Priority Image Loading for Logo -->
    <link rel="preload" as="image" href="{{ asset('final logo.jpeg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $pageTitle }}">
    <meta property="twitter:description" content="{{ $pageDesc }}">
    <meta property="twitter:image" content="@yield('og_image', asset('final logo.jpeg'))">

    <!-- Google Site Verification -->
    <meta name="google-site-verification" content="Am0GjVZ6-DmUyisHtR4GX-9PAfWwHY0_Swfb0A57n5w" /> 

    <!-- Custom Header Tags from Admin -->
    @if(isset($siteSettings) && $siteSettings->custom_html_tags)
        {!! $siteSettings->custom_html_tags !!}
    @endif

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@graph": [
        {
          "@@type": "Organization",
          "@@id": "{{ url('/') }}/#organization",
          "name": "SmartLookBD",
          "alternateName": ["Smart Look BD", "SmartLook BD", "SmartLukBD", "Smart BD", "SmartLook Bangladesh", "Smart লুক বিডি", "smartlook", "bd smartlock", "smrtlok", "smartlock"],
          "url": "{{ url('/') }}",
          "logo": {
            "@@type": "ImageObject",
            "url": "{{ asset('final logo.jpeg') }}",
            "width": 600,
            "height": 600
          },
          "contactPoint": {
            "@@type": "ContactPoint",
            "telephone": "{{ $siteSettings->contact_phone ?? '+880 1700-000000' }}",
            "contactType": "customer service",
            "areaServed": "BD",
            "availableLanguage": ["en", "bn"]
          },
          "sameAs": [
            "https://www.facebook.com/SmartLookBD",
            "https://www.instagram.com/SmartLookBD",
            "{{ $siteSettings->facebook_page_link ?? '#' }}"
          ]
        },
        {
          "@@type": "WebSite",
          "@@id": "{{ url('/') }}/#website",
          "url": "{{ url('/') }}",
          "name": "SmartLookBD",
          "alternateName": ["Smart Look BD", "SmartLook BD", "Smart BD", "smartlook", "smartlock"],
          "description": "{{ $defaultDesc }}",
          "publisher": { "@@id": "{{ url('/') }}/#organization" },
          "potentialAction": {
            "@@type": "SearchAction",
            "target": "{{ url('/products') }}?q={search_term_string}",
            "query-input": "required name=search_term_string"
          },
          "inLanguage": "en-US"
        }
      ]
    }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,700;1,700&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* Mega Menu Styles */
        .mega-menu {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(15px);
            width: 900px;
            background: white;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            opacity: 0;
            visibility: hidden;
            transition: all 0.1s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 50;
            border: 1px solid #f1f1f1;
            border-top: none;
            border-radius: 0 0 10px 10px;
            pointer-events: none;
        }

        .nav-item-container:hover .mega-menu {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
            pointer-events: auto;
        }

        .mega-menu-content {
            padding: 1rem 1.5rem;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }

        .mega-menu-column h3 {
            font-size: 10px;
            font-weight: 900;
            color: #ff3f6c;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
            border-bottom: 1px solid #f1f1f1;
            padding-bottom: 0.15rem;
        }

        .mega-menu-column ul {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.25rem 1rem;
        }

        .mega-menu-column ul li {
            margin-bottom: 0.25rem;
        }

        .mega-menu-column ul li a {
            font-size: 12px;
            color: #606371;
            transition: all 0.2s;
            font-weight: 500;
            display: inline-block;
        }

        .mega-menu-column ul li a:hover {
            color: #45b86f;
            transform: translateX(4px);
        }

        .new-arrivals-column {
            background-color: #fafafa;
            padding: 0.75rem;
            border-radius: 4px;
        }

        .new-arrivals-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
        }

        .mega-product-card {
            background: white;
            padding: 0.25rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            border: 1px solid #f9f9f9;
        }

        .mega-product-card:hover {
            border-color: #45b86f;
            transform: translateY(-2px);
        }

        .mega-product-img {
            height: 130px;
            width: 100%;
            object-fit: cover;
            border-radius: 3px;
            margin-bottom: 0.4rem;
        }

        .mega-product-name {
            font-size: 10px;
            font-weight: 700;
            color: #333;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            line-height: 1.2;
            margin-bottom: 2px;
            height: 24px;
        }

        /* Premium Mobile Menu Styles */
        .premium-menu-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            margin: 6px 15px;
            border-radius: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            background: #f9fafb;
            border: 1px solid #f3f4f6;
        }

        .premium-menu-item:active {
            transform: scale(0.97);
            background: #f3f4f6;
        }

        .premium-menu-item .icon-wrapper {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .premium-menu-item:hover .icon-wrapper {
            transform: scale(1.1) rotate(-8deg);
        }

        .premium-menu-item .menu-label {
            font-size: 14px;
            font-weight: 700;
            color: #374151;
            letter-spacing: -0.01em;
        }

        .premium-menu-item .arrow-icon {
            margin-left: auto;
            color: #9ca3af;
            transition: transform 0.3s ease;
        }

        .premium-menu-item:hover .arrow-icon {
            transform: translateX(3px);
            color: #45b86f;
        }

        /* Active State */
        .premium-menu-item.active-link {
            background: white;
            border-color: #45b86f;
            box-shadow: 0 10px 15px -3px rgba(69, 184, 111, 0.1);
        }
        
        .premium-menu-item.active-link .menu-label {
            color: #45b86f;
        }

        /* Icon Gradients */
        .bg-gradient-home { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .bg-gradient-all { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bg-gradient-men { background: linear-gradient(135deg, #13547a 0%, #80d0c7 100%); }
        .bg-gradient-women { background: linear-gradient(135deg, #ff0844 0%, #ffb199 100%); }
        .bg-gradient-kids { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .bg-gradient-cat { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); }
        .bg-gradient-acc { background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); }
        .bg-gradient-track { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .bg-gradient-wa { background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); }
        .bg-gradient-login { background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        .custom-cart-btn {
            background-color: #000000 !important;
            width: 36px !important;
            height: 36px !important;
            bottom: 12px !important;
            right: 12px !important;
        }
        @media (min-width: 640px) {
            .custom-cart-btn {
                background-color: #333333 !important;
                width: 40px !important;
                height: 40px !important;
                bottom: 16px !important;
                right: 16px !important;
            }
            .custom-cart-btn:hover {
                background-color: #10B981 !important;
            }
        }
    </style>

    <!-- Third Party CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <!-- Custom Tailwind Overrides for Uncompiled Classes -->
    <style>
        @if(request()->routeIs('home'))
            /* Preloader Styles */
            #preloader {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: #ffffff;
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 100000;
            }
            .preloader-spinner {
                width: 38px;
                height: 38px;
                border-radius: 50%;
                background: transparent;
                box-shadow: 2.2px 2.2px 0 0 #1a1a1a;
                animation: spin 0.65s linear infinite;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            body.loaded #preloader {
                display: none !important;
            }
        @endif

        @media (min-width: 768px) {
            .max-w-6xl { max-width: 1280px !important; margin-left: auto; margin-right: auto; padding-left: 20px; padding-right: 20px; }
        }
        
        /* Laptop Specific Optimization (1024px - 1366px) */
        @media (min-width: 1024px) and (max-width: 1366px) {
            .max-w-6xl { max-width: 1140px !important; }
            .nav-item-container { margin-right: 1.5rem !important; }
            .mega-menu { width: 800px !important; }
        }
        
        [x-cloak] { display: none !important; }

        /* Prevent image vanishing on scroll in some browsers */
        img {
            content-visibility: visible !important;
        }
        
        body {
            -webkit-overflow-scrolling: touch;
        }
        
        /* Force Mobile Nav Visibility with Ultra High Priority */
        @media (max-width: 767px) {
            #mobile-bottom-nav {
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
                width: 100vw !important;
                height: 58px !important; /* Precision height */
                z-index: 99999999 !important;
                padding-bottom: env(safe-area-inset-bottom) !important;
                background-color: #FFFFFF !important;
                transform: none !important; /* Prevent AOS/Transition issues */
                transition: none !important;
            }
            body { 
                padding-bottom: calc(58px + env(safe-area-inset-bottom)) !important; 
                overflow-x: clip !important;
            }
        }

        /* Explicitly hide on Desktop */
        @media (min-width: 768px) {
            #mobile-bottom-nav {
                display: none !important;
            }
            body {
                overflow-x: visible !important; /* Allow sticky on desktop */
                overflow-x: clip !important; /* Modern way to hide without breaking sticky */
            }
            #main-mobile-navbar {
                position: sticky !important;
                top: 0 !important;
                transform: none !important;
                background-color: white !important;
                z-index: 1000 !important;
                box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important;
            }
        }
        
        /* Global fix for PC product stretching */
        @media (min-width: 768px) {
            .product-card.col-span-full {
                grid-column: span 1 / span 1 !important;
            }
        }

        /* Category Box Hover Border */
        .group:hover .hover-border-blue {
            border-color: #002f4b !important;
        }

        /* Product Card Hover Enhancements */
        .product-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        /* Clean Unified Box Style (Fabrilife Inspired) */
        .product-box, .category-box, .product-card {
            background: #f3f4f6 !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 5px !important;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease !important;
            overflow: hidden !important;
        }

        .product-box:hover, .category-box:hover, .product-card:hover {
            border-color: #45b86f !important;
            box-shadow: 0 10px 25px -5px rgba(69, 184, 111, 0.15) !important;
            transform: translateY(-5px) !important;
        }

        .product-card {
            position: relative;
        }

        .product-card:hover .product-img {
            transform: scale(1.08) !important;
        }

        /* Hero Banner Slider Arrows */
        .hero-swiper .swiper-button-next,
        .hero-swiper .swiper-button-prev {
            background-color: rgba(0, 0, 0, 0.4);
            width: 44px !important;
            height: 44px !important;
            border-radius: 50%;
            transition: all 0.3s ease;
            backdrop-filter: blur(4px);
        }
        .hero-swiper .swiper-button-next::after,
        .hero-swiper .swiper-button-prev::after {
            content: "" !important; /* Hide default font icon */
        }
        .hero-swiper .swiper-button-next {
            background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23ffffff' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M9 18l6-6-6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 24px;
        }
        .hero-swiper .swiper-button-prev {
            background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23ffffff' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M15 18l-6-6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 24px;
        }
        .hero-swiper .swiper-button-next:hover,
        .hero-swiper .swiper-button-prev:hover {
            background-color: #45b86f;
            transform: scale(1.1);
        }

        .brand-logo-font {
            font-family: 'Playfair Display', serif !important;
            font-weight: 700 !important;
            letter-spacing: -0.01em !important;
            text-transform: none;
            color: #1a1a1a;
            white-space: nowrap;
            font-size: 1rem !important; /* Slightly smaller for tight header */
        }
        @media (min-width: 768px) {
            .brand-logo-font {
                font-size: 2rem !important; /* Desktop font size */
            }
        }
        /* ===== MOBILE HEADER — Warm Champagne Attar Theme ===== */
        @media (max-width: 767px) {

            @keyframes amber-shimmer {
                0%   { background-position: -200% center; }
                100% { background-position: 200% center; }
            }

            #main-mobile-navbar {
                background: linear-gradient(135deg,
                    #FFFFFF 0%,
                    #FFFFFF 40%,
                    #FFFFFF 70%,
                    #FFFFFF 100%
                ) !important;
                border-bottom: 1px solid #f3f4f6 !important;
                box-shadow: 0 1px 4px rgba(0,0,0,0.06) !important;
                position: sticky !important;
                top: 0 !important;
                z-index: 1000 !important;
                overflow: visible;
            }

            /* Amber shimmer line at bottom - DISABLED */
            #main-mobile-navbar::before {
                display: none !important;
            }

            #main-mobile-navbar::after { display: none; }
            .mobile-brand-container {
                margin-left: -10px !important;
            }

            .brand-logo-font {
                font-size: 1.35rem !important;
                color: #1a1a1a !important;
                letter-spacing: 0.01em;
                text-shadow: none;
                margin-left: -4px !important; /* টেক্সটকে লোগোর কাছে টানবে */
            }

            #main-mobile-navbar svg {
                color: #333333 !important;
                filter: none;
            }

            #main-mobile-navbar button,
            #main-mobile-navbar a {
                transition: all 0.2s ease;
            }

            #main-mobile-navbar button:hover {
                background: rgba(0, 0, 0, 0.05) !important;
            }

        }

        .brand-logo-font .f-orange {
            color: #45b86f;
        }
        @media (max-width: 767px) {
            .logo-accent {
                color: #45b86f !important;
                text-shadow: none;
            }
        }
        @media (min-width: 768px) {
            .brand-logo-font { font-size: 2.3rem; }
        }

        /* Global Input Border Fix */
        input:not([type="radio"]):not([type="checkbox"]), textarea, select {
            border: 1.5px solid #000000 !important;
        }
    </style>

    <!-- Meta Pixel Code -->
    @if(isset($siteSettings) && $siteSettings->facebook_pixel_id)
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');

        @php 
                $userCapiData = [];
            if (auth()->check()) {
                $userCapiData['em'] = hash('sha256', strtolower(trim(auth()->user()->email)));
                if (auth()->user()->phone)
                    $userCapiData['ph'] = hash('sha256', preg_replace('/[^0-9]/', '', auth()->user()->phone));
                $names = explode(' ', auth()->user()->name);
                $userCapiData['fn'] = hash('sha256', strtolower(trim($names[0])));
            }
        @endphp

        fbq('init', '{{ $siteSettings->facebook_pixel_id }}' {!! auth()->check() ? ', ' . json_encode($userCapiData) : '' !!});
        fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id={{ $siteSettings->facebook_pixel_id }}&ev=PageView&noscript=1"
        /></noscript>
    @endif
    <!-- End Meta Pixel Code -->

    <!-- TikTok Pixel Code -->
    @if(isset($siteSettings) && $siteSettings->tiktok_pixel_id)
    <script>
        !function (w, d, t) {
            w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=d.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=d.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};
            ttq.load('{{ $siteSettings->tiktok_pixel_id }}');
            ttq.page();
        }(window, document, 'ttq');
    </script>
    @endif
    <!-- End TikTok Pixel Code -->
    <style>
        @media (min-width: 768px) {
            .pc-modal-img {
                max-height: 90vh !important;
                max-width: 95% !important;
                width: auto !important;
                height: auto !important;
                object-fit: contain !important;
            }
            .pc-modal-container {
                max-width: 100% !important;
            }
        }
        
        /* Smooth Scale for Medium Screens */
        @media (min-width: 768px) and (max-width: 1200px) {
            html { font-size: 15px; }
            .container { padding-left: 1rem; padding-right: 1rem; }
        }
    </style>
</head>
<body class="font-inter pb-16 md:pb-0 overflow-x-clip w-full flex flex-col min-h-screen" style="background-color: @yield('body_bg', '#FFFFFF') !important;" x-data="mobileNav()">
    
@if(!request()->routeIs('checkout.*') && !request()->routeIs('login') && !request()->routeIs('login.verify'))
    <!-- Bottom Mobile Navigation (At very top of body for instant rendering) -->
    <div id="mobile-bottom-nav" class="fixed bottom-0 left-0 w-full bg-white md:hidden shadow-[0_-10px_40px_rgba(0,0,0,0.15)] border-t border-gray-100 flex items-center justify-between z-[9999]">
        <a href="{{ route('home') }}" class="flex-1 flex flex-col items-center justify-center {{ request()->routeIs('home') ? 'text-[#007BFF]' : 'text-gray-500' }}" style="color: {{ request()->routeIs('home') ? '#007BFF' : '#6B7280' }};">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
            <span class="text-[9px] font-bold uppercase mt-0.5">Home</span>
        </a>
        <a href="{{ route('categories.index') }}" class="flex-1 flex flex-col items-center justify-center {{ request()->routeIs('categories.index') || request()->routeIs('category.show') ? 'text-[#007BFF]' : 'text-gray-500' }}" style="color: {{ request()->routeIs('categories.index') || request()->routeIs('category.show') ? '#007BFF' : '#6B7280' }};">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
            <span class="text-[9px] font-bold uppercase mt-0.5">Category</span>
        </a>
        <a href="javascript:void(0)" onclick="toggleSideCart(true, true)" class="flex-1 flex flex-col items-center justify-center {{ request()->routeIs('cart.*') ? 'text-[#007BFF]' : 'text-gray-500' }}" style="color: {{ request()->routeIs('cart.*') ? '#007BFF' : '#6B7280' }};">
            <div class="relative inline-block">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                <span id="mobile-cart-badge" class="cart-count-badge absolute -top-1.5 -right-2 bg-[#FF6A00] text-white text-[9px] font-black w-4 h-4 rounded-full flex items-center justify-center shadow-lg z-50" 
                      x-show="$store.cart.count > 0" x-text="$store.cart.count" x-cloak>
                </span>
            </div>
            <span class="text-[9px] font-bold uppercase mt-0.5">Cart</span>
        </a>
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings->contact_phone ?? '8801700000000') }}" target="_blank" class="flex-1 flex flex-col items-center justify-center text-gray-500">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" /></svg>
            <span class="text-[9px] font-bold uppercase mt-0.5">Chat</span>
        </a>
        <a href="{{ route('account.dashboard') }}" class="flex-1 flex flex-col items-center justify-center {{ request()->routeIs('account.*') || request()->routeIs('login') ? 'text-[#007BFF]' : 'text-gray-500' }}" style="color: {{ (request()->routeIs('account.*') || request()->routeIs('login')) ? '#007BFF' : '#6B7280' }};">
            <div class="relative">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                @endif
            </div>
            <span class="text-[9px] font-bold uppercase mt-0.5">{{ auth()->check() ? 'Account' : 'Login' }}</span>
        </a>
    </div>
@endif


    @if(request()->routeIs('home'))
        <!-- Preloader -->
        <div id="preloader">
            <div class="preloader-spinner"></div>
        </div>
        <script>
            window.addEventListener('load', function() {
                const preloader = document.getElementById('preloader');
                if (preloader) {
                    preloader.style.opacity = '0';
                    setTimeout(() => {
                        preloader.style.display = 'none';
                        document.body.classList.add('loaded');
                    }, 300);
                }
            });
            // Fallback for slow connections
            setTimeout(function() {
                const preloader = document.getElementById('preloader');
                if (preloader && preloader.style.display !== 'none') {
                    preloader.style.display = 'none';
                    document.body.classList.add('loaded');
                }
            }, 3000);
        </script>
    @endif

@if(isset($siteSettings) && $siteSettings->is_popup_active && !empty($siteSettings->popup_image))
    <!-- Entry Popup Modal -->
    <div x-data="{ showPopup: false }" 
         x-init="setTimeout(() => { if (!sessionStorage.getItem('welcomePopupShown')) showPopup = true; }, 800);"
         x-show="showPopup" 
         x-cloak
         class="fixed inset-0 flex items-center justify-center p-4 sm:p-6"
         style="display: none; z-index: 999999999;">

        <!-- Backdrop -->
        <div x-show="showPopup"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0"
             style="z-index: 999999998; background-color: rgba(0, 0, 0, 0.55); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);"
             @click="showPopup = false; sessionStorage.setItem('welcomePopupShown', 'true')"></div>

        <!-- Modal Content -->
        <div x-show="showPopup"
             x-transition:enter="transition ease-out duration-400 delay-100"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative mx-auto bg-transparent flex flex-col items-center justify-center pointer-events-auto rounded-md"
             style="z-index: 999999999; width: 90vw; max-width: 500px; box-shadow: 0 30px 100px rgba(0,0,0,0.8);">

            <!-- Close Button (Outside top right) -->
            <button type="button" @click="showPopup = false; sessionStorage.setItem('welcomePopupShown', 'true')" 
                    class="absolute flex items-center justify-center text-white hover:text-red-500 transition-all rounded-full p-2"
                    style="z-index: 999999999; top: -50px; right: 0px; background: rgba(0, 0, 0, 0.4); border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="block w-full">
                @if(!empty($siteSettings->popup_link))
                    <a href="{{ $siteSettings->popup_link }}" class="block w-full h-full" @click="sessionStorage.setItem('welcomePopupShown', 'true')">
                        <img src="{{ asset('storage/' . $siteSettings->popup_image) }}" alt="Welcome Banner" class="w-full h-auto object-contain rounded-md block pointer-events-auto" style="max-height: 70vh;">
                    </a>
                @else
                    <img src="{{ asset('storage/' . $siteSettings->popup_image) }}" alt="Welcome Banner" class="w-full h-auto object-contain rounded-md block pointer-events-auto" style="max-height: 70vh;">
                @endif
            </div>
        </div>
    </div>
@endif

@include('partials.auth-modal')
<script>
    window.addEventListener('open-auth-modal', function() {
        console.log('Opening auth modal...');
    });
</script>

<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-[9999] flex flex-col gap-2 max-w-sm w-full pointer-events-none" style="pointer-events: none">
    <div style="pointer-events: auto"></div>
</div>

<script>
    // reinit so toasts appear properly
    document.getElementById('toast-container').style.pointerEvents = 'auto';
</script>

@if(isset($siteSettings) && $siteSettings->announcement_text)
    @php $annoSpeed = max(5, (int) ($siteSettings->announcement_speed ?? 45)); @endphp
    <div class="announcement-bar bg-black text-white py-1.5 md:py-4 text-center text-[10px] sm:text-xs md:text-sm font-bold uppercase tracking-widest relative z-[60] overflow-hidden" style="background-color: #000000 !important; color: #ffffff !important;">
        <div id="ticker-strip" style="position:absolute; left:0; top:0; white-space:nowrap; will-change:transform;">
            <span style="padding:0 5rem;">{{ $siteSettings->announcement_text }}</span>
            <span style="padding:0 5rem;">{{ $siteSettings->announcement_text }}</span>
            <span style="padding:0 5rem;">{{ $siteSettings->announcement_text }}</span>
        </div>
    </div>
    <script>
    (function(){
        var strip = document.getElementById('ticker-strip');
        if (!strip) return;
        var speed = {{ $annoSpeed }};
        var pxPerFrame = Math.max(0.2, 50 / speed);
        var oneThird = 0;
        var pos;

        function init() {
            oneThird = strip.scrollWidth / 3;
            pos = window.innerWidth; // start from RIGHT edge
        }

        function tick() {
            pos -= pxPerFrame;
            // when first copy is fully gone off the left, jump back one copy width (seamless)
            if (oneThird > 0 && pos <= -oneThird) {
                pos += oneThird;
            }
            strip.style.transform = 'translateX(' + pos + 'px)';
            requestAnimationFrame(tick);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function(){ init(); requestAnimationFrame(tick); });
        } else {
            init();
            requestAnimationFrame(tick);
        }
    })();
    </script>
@endif

@if(!request()->routeIs('login') && !request()->routeIs('login.verify'))
    <style>
        .main-header-content {
            height: 52px !important; /* Even smaller mobile height */
        }
        @media (min-width: 768px) {
            .main-header-content {
                height: 70px !important;
            }
        }
    </style>
    <nav id="main-mobile-navbar" 
         class="navbar mb-0 md:sticky top-0 z-50 bg-white" 
         x-data="{ searchOpen: false }">
        <div class="max-w-[1440px] mx-auto px-4 md:px-8 xl:px-[15%]">
            <div class="flex items-center main-header-content gap-4 md:gap-0 relative">
                <!-- Mobile Menu Button -->
                <button type="button" @click="toggle()" class="md:hidden p-2 -ml-2 rounded-xl hover:bg-black/5 transition relative z-20">
                    <svg class="w-6 h-6 text-[#333333]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Logo (Centered on mobile, static on PC) -->
                <div class="flex items-center flex-shrink-0 absolute left-1/2 -translate-x-1/2 md:static md:translate-x-0 pointer-events-none md:pointer-events-auto z-10 w-auto mobile-brand-container">
                    <a href="{{ route('home') }}" class="flex items-center justify-center gap-1 group pointer-events-auto mx-auto w-full md:w-auto">
                        <img src="{{ asset('final logo.jpeg') }}" 
                            alt="SmartLookBD Logo" 
                            fetchpriority="high"
                            loading="eager"
                            title="SmartLookBD - Premium Online Shopping Bangladesh"
                            class="h-6 md:h-12 w-auto object-contain transition-transform group-hover:scale-105">
                        <span class="brand-logo-font text-gray-900 group-hover:text-black transition-all duration-300 whitespace-nowrap" style="line-height: 1.2;">
                            <span style="font-size: 1.1em; color: inherit;">S</span>martLookBD
                        </span>
                    </a>
                </div>

                <!-- Desktop Navigation Menu -->
                <div class="hidden md:flex items-center gap-10 xl:gap-14" style="margin-left: 40px !important;">
                    @foreach(['Men', 'Women', 'Kids', 'Sports'] as $gender)
                    <div class="nav-item-container flex items-center h-[70px]">
                        @php
                            $isSports = $gender === 'Sports';
                            $routeParams = $isSports ? ['category' => 'sports'] : ['gender' => $gender];
                            $isActive = $isSports ? request('category') === 'sports' : request('gender') === $gender;
                        @endphp
                        <a href="{{ route('products.index', $routeParams) }}" class="text-[12px] font-black {{ $isActive ? 'text-[#45b86f]' : 'text-[#606371]' }} hover:text-[#45b86f] transition-all uppercase tracking-[0.12em] whitespace-nowrap py-10">
                            {{ $gender }}
                        </a>
                        
                        <!-- Mega Menu Content -->
                        <div class="mega-menu">
                            <div class="mega-menu-content">
                                <!-- Link Column -->
                                <div class="mega-menu-column">
                                    <h3>Categories</h3>
                                    <ul>
                                        @php
                                            $cacheKeyCats = "mega_menu_cats_v2_{$gender}";
                                            $genderCats = \Illuminate\Support\Facades\Cache::remember($cacheKeyCats, 3600, function() use ($isSports, $gender) {
                                                if($isSports) {
                                                    return \App\Models\Category::where('is_active', true)->where('name', 'LIKE', '%Sports%')->orderBy('sort_order')->take(12)->get();
                                                } else {
                                                    return \App\Models\Category::where('is_active', true)
                                                        ->whereJsonContains('target_gender', $gender)
                                                        ->orderBy('sort_order')
                                                        ->take(12)
                                                        ->get();
                                                }
                                            });
                                        @endphp
                                        @foreach($genderCats as $gCat)
                                            <li><a href="{{ route('category.show', $gCat->slug) }}">{{ $gCat->name }}</a></li>
                                        @endforeach
                                        @if($genderCats->isEmpty())
                                            <li><a href="{{ route('products.index', $routeParams) }}">All Items</a></li>
                                        @endif
                                    </ul>
                                    <div class="mt-4">
                                        <a href="{{ route('products.index', $routeParams) }}" class="text-[10px] font-black text-[#ff3f6c] uppercase hover:underline">View All {{ $gender }} &rarr;</a>
                                    </div>
                                </div>

                                <!-- Right Side: New Arrivals Showcase -->
                                <div class="new-arrivals-column">
                                    <h3 style="color: #333; border-bottom: none;">Latest Arrivals products</h3>
                                    <div class="new-arrivals-grid">
                                        @php
                                            $cacheKeyProds = "mega_menu_prods_v2_{$gender}";
                                            $genderProds = \Illuminate\Support\Facades\Cache::remember($cacheKeyProds, 3600, function() use ($isSports, $gender) {
                                                $prods = \App\Models\Product::active()
                                                    ->when($isSports, function($q) {
                                                        $q->whereHas('category', function($cq) {
                                                            $cq->where('name', 'LIKE', '%Sports%');
                                                        });
                                                    }, function($q) use ($gender) {
                                                        $q->whereHas('category', function($cq) use ($gender) {
                                                            $cq->whereJsonContains('target_gender', $gender);
                                                        });
                                                    })
                                                    ->latest()
                                                    ->take(6)
                                                    ->get();

                                                return $prods;
                                            });
                                        @endphp
                                        @foreach($genderProds as $naProd)
                                            <a href="{{ route('products.show', $naProd->slug) }}" class="mega-product-card">
                                                <img src="{{ $naProd->thumbnail_url }}" alt="{{ $naProd->name }}" class="mega-product-img">
                                                <div class="mega-product-name">{{ $naProd->name }}</div>
                                                <div class="text-[10px] font-black text-[#45b86f]">৳{{ number_format($naProd->effective_price) }}</div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Search Bar (Right Aligned) -->
                <div class="hidden md:flex items-center ml-auto px-4 mr-10" x-data="searchbar()">
                    <div class="relative w-full max-w-[300px]">
                        <form action="{{ route('products.index') }}" method="GET" class="relative group">
                            <input type="text" name="q" x-model="query" @input.debounce.300ms="search()" @focus="showResults = query.length > 1" @click.away="showResults = false" placeholder="Search" 
                                   class="w-full border-none px-5 py-2.5 rounded-sm text-[13px] focus:outline-none focus:ring-1 focus:ring-gray-200 transition-all shadow-none" 
                                   style="background-color: #f5f5f6 !important;"
                                   autocomplete="off">
                        </form>
                        <!-- Search Results Dropdown -->
                        <div x-show="showResults" x-cloak x-transition @click.away="showResults = false" class="absolute top-full right-0 w-[450px] bg-white rounded-xl shadow-[0_20px_70px_-15px_rgba(0,0,0,0.3)] border border-gray-100 mt-4 overflow-hidden z-[1001] max-h-[500px] overflow-y-auto" style="display: none;">
                            <template x-if="results.length > 0">
                                <div class="py-2">
                                    <div class="px-5 py-2.5 bg-gray-50/50 border-b border-gray-100 mb-2">
                                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Suggested Products</p>
                                    </div>
                                    <template x-for="item in results" :key="item.id">
                                        <a :href="`/products/${item.slug}`" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition border-b border-gray-50 last:border-0 group">
                                            <div class="relative w-14 h-14 flex-shrink-0">
                                                <img :src="item.thumbnail_url" :alt="item.name" class="w-full h-full rounded-lg object-cover shadow-sm group-hover:scale-105 transition-transform">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-[13px] font-bold text-gray-900 leading-tight truncate" x-text="item.name"></p>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <p class="text-[13px] text-[#45b86f] font-black" x-text="`৳${item.effective_price.toLocaleString()}`"></p>
                                                    <template x-if="item.old_price">
                                                        <p class="text-[11px] text-gray-400 line-through" x-text="`৳${item.old_price.toLocaleString()}`"></p>
                                                    </template>
                                                </div>
                                            </div>
                                            <div class="text-gray-300 transform group-hover:translate-x-1 transition-transform">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                            </div>
                                        </a>
                                    </template>
                                    <a :href="`/products?q=${encodeURIComponent(query)}`" class="block w-full py-4 text-center text-[11px] font-black text-white bg-black uppercase tracking-[0.2em] hover:bg-gray-900 transition-colors mt-2">
                                        View All Search Results
                                    </a>
                                </div>
                            </template>
                            <template x-if="results.length === 0 && !loading && query.length >= 2">
                                <div class="px-8 py-16 text-center">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-5">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <p class="text-[18px] text-gray-900 font-bold">No results found</p>
                                    <p class="text-[13px] text-gray-500 mt-2 max-w-[250px] mx-auto">We couldn't find any products matching "<span x-text="query" class="text-black font-bold"></span>"</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Nav Icons (Right) -->
                <div class="flex items-center justify-end gap-1 sm:gap-4 md:gap-4 xl:gap-8 ml-auto md:ml-0 md:mr-4 xl:mr-16">
                    <!-- Search Icon (Mobile only) -->
                    <button type="button" @click="searchOpen = !searchOpen; if(searchOpen) { $nextTick(() => { $refs.mobileSearchInput.focus() }) }" class="md:hidden p-2 rounded-full hover:bg-gray-100 transition">
                        <svg class="w-6 h-6 text-[#333333]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
                    </button>

                    <!-- Wishlist Heart (Mobile only) -->
                    <a href="{{ route('wishlist.index') }}" class="md:hidden p-2 rounded-full hover:bg-gray-100 transition relative">
                        <svg class="w-6 h-6 text-[#333333]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </a>

                    <!-- Track Order (PC Only) -->
                    <a href="{{ route('pages.track-order') }}" class="hidden md:flex flex-col items-center group">
                        <svg class="w-6 h-6 md:w-8 md:h-8 text-gray-700 group-hover:text-black transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <circle cx="12" cy="11" r="3" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        <span class="text-[11px] font-bold text-gray-500 mt-1.5 leading-none">Track</span>
                    </a>

                    <!-- Wishlist (PC Only) -->
                    <a href="{{ route('wishlist.index') }}" class="hidden md:flex flex-col items-center group">
                        <svg class="w-6 h-6 md:w-8 md:h-8 text-gray-700 group-hover:text-black transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span class="text-[11px] font-bold text-gray-500 mt-1.5 leading-none">Wishlist</span>
                    </a>

                    <!-- Cart (PC Only) -->
                    <a href="javascript:void(0)" onclick="toggleSideCart(true)" class="hidden md:flex flex-col items-center group relative">
                        <div class="relative">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-700 group-hover:text-black transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            <span class="cart-count-badge absolute -top-1.5 -right-1.5 bg-[#FF6A00] text-white text-[8px] font-bold w-4 h-4 rounded-full flex items-center justify-center"
                                  x-show="$store.cart.count > 0" x-text="$store.cart.count" x-cloak>
                            </span>
                        </div>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-500 mt-1 md:mt-1.5 leading-none">Bag</span>
                    </a>

                    <!-- Account / Login Section -->
                    @auth
                        <div class="relative" x-data="{ profileOpen: false }">
                            <!-- Desktop Profile Button -->
                            <button @click="profileOpen = !profileOpen" class="hidden md:flex flex-col items-center group relative">
                                <div class="relative">
                                    <svg class="w-6 h-6 md:w-8 md:h-8 text-gray-700 group-hover:text-black transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                                        <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                                    @endif
                                </div>
                                <span class="text-[11px] font-bold text-gray-500 mt-1.5 leading-none">Profile</span>
                            </button>
                            <!-- Mobile Profile Link -->
                            <a href="{{ route('account.dashboard') }}" class="hidden md:hidden p-2 rounded-full hover:bg-gray-100 transition relative">
                                <div class="relative">
                                    <svg class="w-6 h-6 md:w-8 md:h-8 text-[#333333]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                                        <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                                    @endif
                                </div>
                            </a>

                            <div x-show="profileOpen" x-cloak @click.away="profileOpen = false" x-transition class="absolute right-0 top-full mt-2 w-52 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-50">
                                <div class="px-4 py-3 border-b border-gray-50 bg-gray-50/50">
                                    <p class="font-bold text-gray-900 text-xs truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="py-1">
                                    <a href="{{ route('account.dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        My Account
                                    </a>
                                    <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        My Orders
                                    </a>
                                    <a href="{{ route('messages.index') }}" class="flex items-center justify-between px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                            Messages
                                        </div>
                                        @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                                            <span class="bg-[#FF6A00] text-white text-[9px] font-black px-1.5 py-0.5 rounded-full">{{ $unreadMessagesCount }}</span>
                                        @endif
                                    </a>
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-xs text-[#45b86f] font-bold hover:bg-gray-50 transition-colors">
                                            Admin Panel
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-50 mt-1">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-xs text-red-600 hover:bg-red-50 transition-colors">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hidden md:flex flex-col items-center group">
                            <svg class="w-6 h-6 md:w-14 md:h-14 text-gray-700 group-hover:text-black transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="text-[11px] font-bold text-gray-500 mt-1.5 leading-none">Login</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Full-Width Navigation Menu (Mobile/Desktop Hidden if using Header Nav) -->
        <div class="hidden shadow-lg" style="background-color: black !important; border-top: 1px solid #333333;">
            <div class="max-w-7xl mx-auto w-full flex items-center justify-center gap-8 py-3.5 overflow-x-auto text-[13px] font-extrabold tracking-wide" style="color: white !important;">
                <a href="{{ route('home') }}" class="hover:text-[#45b86f] hover:scale-105 transition-all duration-300 whitespace-nowrap flex items-center gap-1.5 {{ request()->routeIs('home') ? 'text-[#45b86f]' : '' }}" style="color: inherit;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Home
                </a>
                <a href="{{ route('products.index') }}" class="hover:text-[#45b86f] hover:scale-105 transition-all duration-300 whitespace-nowrap flex items-center gap-1.5 {{ request()->routeIs('products.index') && !request()->hasAny(['q', 'category', 'featured', 'new']) ? 'text-[#45b86f]' : '' }}" style="color: inherit;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    All Products
                </a>
                @foreach(\App\Models\Category::where('is_active', true)->where('slug', '!=', 'grocery-food')->orderBy('sort_order')->take(7)->get() as $cat)
                    <a href="{{ route('category.show', $cat->slug) }}" class="hover:text-[#45b86f] hover:scale-105 transition-all duration-300 whitespace-nowrap flex items-center gap-1.5 {{ request()->is('category/' . $cat->slug) ? 'text-[#45b86f]' : '' }}" style="color: inherit;">
                        <span class="text-base">{{ $cat->icon }}</span> {{ ucwords(str_replace('-', ' ', $cat->slug)) }}
                    </a>
                @endforeach
                <a href="{{ route('products.flash-sales') }}" class="font-black whitespace-nowrap flex items-center gap-1.5 hover:scale-110 transition-all uppercase tracking-tighter px-3 py-1 rounded-full bg-red-600 text-white hover:bg-white hover:text-red-600 animate-pulse-slow shadow-[0_0_15px_rgba(255,0,0,0.5)]">
                    <span class="text-base text-yellow-300">🔥</span> Flash Sale
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <!-- Mobile Search Bar (Hidden by default, used if icon is clicked) -->
            <div x-show="searchOpen" x-transition class="md:hidden pb-3" style="display: none;" x-data="searchbar()">
                <form action="{{ route('products.index') }}" method="GET">
                    <div class="relative">
                        <input type="text" x-ref="mobileSearchInput" name="q" x-model="query" @input.debounce.300ms="search()" @focus="showResults = query.length > 1" @click.away="showResults = false" placeholder="Search products..." class="w-full pl-4 pr-12 py-2.5 rounded-full border border-black bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-[#45b86f]" autocomplete="off">
                        <!-- Loading Indicator (Mobile) -->
                        <div x-show="loading" class="absolute right-12 top-1/2 -translate-y-1/2">
                            <svg class="animate-spin h-4 w-4 text-[#45b86f]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                        <button type="submit" class="absolute right-1 top-1 bg-[#45b86f] text-white p-1.5 rounded-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
                        </button>

                        <!-- Live Search Results (Mobile) -->
                        <div x-show="showResults" x-cloak x-transition class="absolute top-full left-0 right-0 bg-white rounded-xl shadow-[0_15px_50px_-10px_rgba(0,0,0,0.3)] border border-gray-100 mt-3 overflow-hidden z-[1001] max-h-[70vh] overflow-y-auto" style="display: none;">
                            <template x-if="results.length > 0">
                                <div class="py-2">
                                    <template x-for="item in results" :key="item.id">
                                        <a :href="`/products/${item.slug}`" class="flex items-center gap-4 px-4 py-3.5 hover:bg-gray-50 transition border-b border-gray-50 last:border-0 group">
                                            <div class="relative w-14 h-14 flex-shrink-0">
                                                <img :src="item.thumbnail_url" :alt="item.name" class="w-full h-full rounded-lg object-cover shadow-sm group-hover:scale-105 transition-transform">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-[13px] font-bold text-gray-900 leading-tight truncate" x-text="item.name"></p>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <p class="text-[13px] text-[#45b86f] font-black" x-text="`৳${item.effective_price.toLocaleString()}`"></p>
                                                    <template x-if="item.old_price">
                                                        <p class="text-[11px] text-gray-400 line-through" x-text="`৳${item.old_price.toLocaleString()}`"></p>
                                                    </template>
                                                </div>
                                            </div>
                                            <div class="text-gray-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                            </div>
                                        </a>
                                    </template>
                                    <a :href="`/products?q=${encodeURIComponent(query)}`" class="block w-full py-3 text-center text-[12px] font-black text-white bg-black uppercase tracking-widest hover:bg-gray-900 transition-colors">
                                        View All Results
                                    </a>
                                </div>
                            </template>
                            <template x-if="results.length === 0 && !loading && query.length >= 2">
                                <div class="px-5 py-12 text-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <p class="text-[15px] text-gray-900 font-bold">No products found</p>
                                    <p class="text-[12px] text-gray-500 mt-1 px-4">We couldn't find anything matching "<span x-text="query" class="text-black font-bold"></span>"</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </nav>
@endif

@if(session('success'))
    <div id="success-alert" class="max-w-[1440px] mx-auto px-4 mt-4 transition-opacity duration-500">
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    </div>
    <script>
        setTimeout(function() {
            const alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 3000);
    </script>
@endif

@if(session('error'))
    <div id="error-alert" class="max-w-[1440px] mx-auto px-4 mt-4 transition-opacity duration-500">
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
    </div>
    <script>
        setTimeout(function() {
            const alert = document.getElementById('error-alert');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 3000);
    </script>
@endif

<main class="mt-0 p-0 flex-1">
    @yield('content')
</main>

@if(!request()->routeIs('login') && !request()->routeIs('login.verify'))
    <!-- Footer -->
<footer class="text-white mt-20 pb-4 md:pb-0 {{ request()->routeIs('checkout.index') ? 'hidden md:block' : '' }} pt-6 md:pt-[80px]" style="background-color: #2D2D2D !important;">
        <!-- Footer Top -->
        <div class="max-w-[1440px] mx-auto px-6 pt-0 pb-12 sm:py-20">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-x-24 xl:gap-x-32 gap-y-12">
                
                <!-- Left Column: Brand & Nav -->
                <div class="col-span-1">
                    <!-- Mobile Logo (Centered) -->
                    <div class="md:hidden text-center mb-8">
                        <span class="brand-logo-font text-3xl font-black tracking-tighter" style="color: white !important;">SmartLook<span class="text-[#45b86f]">BD</span></span>
                        <div class="flex flex-col items-center mt-4 gap-1.5 opacity-50">
                            <div class="w-12 h-[1px] bg-[#45b86f]"></div>
                            <div class="w-8 h-[1px] bg-white"></div>
                        </div>
                    </div>
                    
                    <!-- PC Logo (Left Aligned) -->
                    <div class="hidden md:block mb-8 mt-[-10px]">
                        <span class="brand-logo-font text-3xl font-black tracking-tighter" style="color: white !important;">SmartLook<span class="text-[#45b86f]">BD</span></span>
                    </div>
                    
                    <nav class="space-y-4">
                        <ul class="space-y-3 text-[13px] font-bold text-gray-300">
                            <!-- Mobile Version -->
                            <li class="md:hidden flex items-start justify-between border-b border-white/10 pb-4 mb-4">
                                <a href="{{ route('home') }}" class="hover:text-[#45b86f] transition-colors">About SmartLookBD</a>
                                <div class="flex flex-col items-end">
                                    <a href="{{ route('pages.track-order') }}" class="text-[11px] font-black uppercase text-[#ffde59] border-b border-[#ffde59]/30 pb-0.5">Contact Us</a>
                                    <a href="tel:{{ $siteSettings->contact_phone ?? '8801700000000' }}" class="text-[12px] font-bold text-white mt-0.5">
                                        {{ $siteSettings->contact_phone ?? '+880 1700-000000' }}
                                    </a>
                                    <p class="text-[9px] text-[#45b86f] font-black uppercase tracking-[0.2em] mt-1">24/7 Support</p>
                                </div>
                            </li>

                            <!-- PC Version -->
                            <li class="hidden md:block">
                                <a href="{{ route('home') }}" class="hover:text-[#45b86f] transition-colors">About SmartLookBD</a>
                            </li>
                            <!-- Terms & Conditions -->
                            <li class="md:hidden flex items-center justify-between border-b border-white/5 pb-3">
                                <a href="{{ route('pages.return-policy') }}" class="hover:text-[#45b86f] transition-colors">Terms & Conditions</a>
                                <span class="text-[9px] font-black uppercase tracking-widest text-gray-500">Legal Info</span>
                            </li>
                            <li class="hidden md:block">
                                <a href="{{ route('pages.return-policy') }}" class="hover:text-[#45b86f] transition-colors">Terms & Conditions</a>
                            </li>

                            <!-- Privacy Policy -->
                            <li class="md:hidden flex items-center justify-between border-b border-white/5 pb-3 py-2">
                                <a href="{{ route('pages.privacy-policy') }}" class="hover:text-[#45b86f] transition-colors">Privacy Policy</a>
                                <span class="text-[9px] font-black uppercase tracking-widest text-gray-500">Data Secure</span>
                            </li>
                            <li class="hidden md:block">
                                <a href="{{ route('pages.privacy-policy') }}" class="hover:text-[#45b86f] transition-colors">Privacy Policy</a>
                            </li>

                            <!-- Cancellation & Return Policy -->
                            <li class="md:hidden flex items-center justify-between border-b border-white/5 pb-3 py-2">
                                <a href="{{ route('pages.return-policy') }}" class="hover:text-[#45b86f] transition-colors">Cancellation & Return Policy</a>
                                <span class="text-[9px] font-black uppercase tracking-widest text-gray-500">Easy Returns</span>
                            </li>
                            <li class="hidden md:block">
                                <a href="{{ route('pages.return-policy') }}" class="hover:text-[#45b86f] transition-colors">Cancellation & Return Policy</a>
                            </li>

                            <!-- FAQs -->
                            <li class="md:hidden flex items-center justify-between border-b border-white/5 pb-3 py-2">
                                <a href="{{ route('pages.faq') }}" class="hover:text-[#45b86f] transition-colors">FAQs</a>
                                <span class="text-[9px] font-black uppercase tracking-widest text-gray-500">Support Center</span>
                            </li>
                            <li class="hidden md:block">
                                <a href="{{ route('pages.faq') }}" class="hover:text-[#45b86f] transition-colors">FAQs</a>
                            </li>
                            <li class="hidden md:block"><a href="{{ route('pages.track-order') }}" class="hover:text-[#45b86f] transition-colors">Contact Us</a></li>
                        </ul>
                    </nav>
                </div>

                <!-- Middle Column: Newsletter & Support -->
                <div class="hidden md:block col-span-1 lg:-ml-12">
                    <!-- Call Center -->
                    <div>
                        <h4 class="text-[11px] font-black uppercase tracking-[0.15em] text-[#ffde59] mb-5 hidden md:flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            For any help you may call us at
                        </h4>
                        <div class="space-y-1">
                            <a href="tel:{{ $siteSettings->contact_phone ?? '8801700000000' }}" 
                               class="text-[20px] font-black text-white hover:text-[#45b86f] transition-colors flex items-center gap-2">
                                {{ $siteSettings->contact_phone ?? '+880 1700-000000' }}
                            </a>
                            <p class="text-[12px] text-gray-400 font-medium hidden md:block">Customer Service</p>
                            <p class="text-[12px] text-gray-500 leading-tight max-w-[280px] hidden md:block">Track your order or get help returning an order on SmartLookBD</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Social & Widget -->
                <div class="col-span-1 lg:ml-auto">
                    @php
                        $socials = [
                            ['link' => $siteSettings->facebook_page_link ?? '#', 'icon' => 'M24 12c0-6.627-5.373-12-12-12S0 5.373 0 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12z', 'color' => '#0866FF', 'name' => 'facebook'],
                            ['link' => $siteSettings->instagram_link ?? '#', 'icon' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4s1.791-4 4-4 4 1.791 4 4-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z', 'color' => '#E4405F', 'name' => 'instagram'],
                            ['link' => $siteSettings->twitter_link ?? '#', 'icon' => 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z', 'color' => '#000000', 'name' => 'twitter'],
                            ['link' => $siteSettings->tiktok_link ?? '#', 'icon' => 'M12.53.02C13.84 0 15.14.01 16.44 0c.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.06-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-3.49 3.03-6.41 6.51-6.58 1.15-.04 2.3.14 3.3.69v4.03c-.61-.31-1.28-.48-1.97-.48-1.79.13-3.08 1.83-2.82 3.59.21 1.34 1.41 2.33 2.7 2.29 1.5-.06 2.44-1.42 2.49-2.88.04-3.75.01-7.49.01-11.24z', 'color' => '#000000', 'name' => 'tiktok']
                        ];
                    @endphp

                    <!-- Mobile Social Icons -->
                    <div class="md:hidden">
                        <div class="flex flex-wrap justify-center gap-5 mb-8">
                            @foreach($socials as $soc)
                                <a href="{{ $soc['link'] }}" target="_blank" 
                                   class="w-10 h-10 rounded-full flex items-center justify-center transition-all hover:scale-110 active:scale-95 shadow-md"
                                   style="{{ $soc['name'] === 'instagram' ? 'background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);' : 'background-color: ' . $soc['color'] . ';' }} color: white; border: none;">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="{{ $soc['icon'] }}"/>
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- PC Follow Us -->
                    <div class="hidden md:block">
                        <h4 class="text-[11px] font-black uppercase tracking-[0.15em] text-[#ffde59] mb-6 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-[#45b86f] rounded-full"></span>
                            Follow Us
                        </h4>
                        <div class="flex flex-wrap gap-5 mb-8">
                            @foreach($socials as $soc)
                                <a href="{{ $soc['link'] }}" target="_blank" 
                                   class="w-10 h-10 rounded-full flex items-center justify-center transition-all hover:scale-110 hover:shadow-lg shadow-sm"
                                   style="{{ $soc['name'] === 'instagram' ? 'background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);' : 'background-color: ' . $soc['color'] . ';' }} color: white; border: none;">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="{{ $soc['icon'] }}"/>
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Facebook Floating Mini Widget -->
                    <div class="mb-8 overflow-hidden rounded-xl border border-white/5 bg-white/5 p-4 shadow-xl backdrop-blur-sm group">
                        <div class="flex items-center gap-4">
                            <div class="relative flex-shrink-0" style="width: 38px; height: 38px;">
                                <div style="width: 38px; height: 38px; background-color: white; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                                     <svg viewBox="0 0 24 24" style="width: 38px; height: 38px; color: #0866FF;" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                         <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                     </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-0.5">
                                    <span class="text-[13px] font-black uppercase tracking-tight">{{ $fbPageName ?? 'SmartLookBD' }}</span>
                                    <a href="{{ $siteSettings->facebook_page_link ?? '#' }}" target="_blank" class="text-[10px] text-white px-3 py-1.5 rounded-[4px] font-black uppercase transition-all shadow-md hover:opacity-90" style="background-color: #1877F2 !important;">Follow</a>
                                </div>
                                <p class="text-[10px] text-gray-400 font-bold">11K Followers • 1 Following</p>
                            </div>
                        </div>
                    </div>

                    <!-- App Badges -->
                    <div class="flex justify-center gap-3 md:hidden text-center">
                        <a href="#" class="block hover:scale-105 transition-transform opacity-90 hover:opacity-100">
                             <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Play Store" class="h-9">
                        </a>
                        <a href="#" class="block hover:scale-105 transition-transform opacity-90 hover:opacity-100">
                             <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" alt="App Store" class="h-9">
                        </a>
                    </div>
                    
                    <div class="hidden md:flex gap-3">
                        <a href="#" class="block hover:scale-105 transition-transform opacity-90 hover:opacity-100">
                             <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Play Store" class="h-9">
                        </a>
                        <a href="#" class="block hover:scale-105 transition-transform opacity-90 hover:opacity-100">
                             <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" alt="App Store" class="h-9">
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom Bar -->
        <div class="border-t border-white/5 bg-black/20 py-8">
            <div class="max-w-[1440px] mx-auto px-6 text-center space-y-2">
                <p class="text-[11px] text-gray-500 font-bold tracking-[0.1em] uppercase">
                    Your order is handled daily with a lot of ❤️ and delivered worldwide!
                </p>
                <p class="text-[11px] text-gray-600 font-medium">
                    Copyright © {{ date('Y') }} SmartLookBD Limited. All Right Reserved
                </p>
            </div>
        </div>
    </footer>
@endif

@include('partials.side-cart')

<script>
// Function to update cart badges (Using class for multi-device support)
window.refreshCartBadges = function(count) {
    console.log('UPDATING ALL BADGES TO:', count);
    if (window.Alpine && Alpine.store('cart')) {
        Alpine.store('cart').update(count);
    }
};

// Map old function name to new one just in case
window.updateCartBadge = window.refreshCartBadges;

// Inline mobileNav for drawer and other UI state
document.addEventListener('alpine:init', () => {
    // Global Cart Store
    Alpine.store('cart', {
        count: {{ collect(session('cart', []))->sum(fn($i) => is_array($i) ? ($i['quantity'] ?? 0) : $i) }},
        update(val) {
            this.count = val;
            console.log('Cart Store Updated:', this.count);
        }
    });

    Alpine.data('mobileNav', () => ({
        open: false,
        toggle() { this.open = !this.open; },
        close() { this.open = false; }
    }));
});

// Optimized Add to Cart Function to reduce loading time
window.smartAddToCart = function(productId, quantity = 1, color = '', size = '') {
    // Explicitly cast to string and handle undefined/null
    const qty = parseInt(quantity) || 1;
    const clr = String(color || '');
    const sz = String(size || '');
    
    const btn = document.querySelector(`#cart-btn-${productId}`);
    if (btn) {
        btn.innerHTML = '<span class="loader inline-block"></span>';
        btn.disabled = true;
    }

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ 
            product_id: parseInt(productId), 
            quantity: qty,
            color: clr,
            size: sz
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Update cart count badge
            if (typeof refreshCartBadges === 'function') refreshCartBadges(data.cart_count);
            if (window.Alpine) Alpine.store('cart').update(data.cart_count);
            
            // OPTIMIZATION: Update side cart state immediately using the unified method
            if (data.cart_details && window.sideCart) {
                window.sideCart.updateFromData(data.cart_details);
            }
            
            // Meta Pixel Tracking for AddToCart
            if (typeof fbq === 'function') {
                fbq('track', 'AddToCart', {
                    content_ids: [productId],
                    content_type: 'product',
                    value: data.item_price || 0,
                    currency: 'BDT'
                });
            }

            // TikTok Pixel Tracking for AddToCart
            if (typeof ttq === 'object') {
                ttq.track('AddToCart', {
                    content_id: productId,
                    content_type: 'product',
                    value: data.item_price || 0,
                    currency: 'BDT'
                });
            }
            
            // Open side cart
            if (typeof window.toggleSideCart === 'function') {
                window.toggleSideCart(true);
            }
        } else {
            if (typeof showToast === 'function') showToast(data.message || 'Error adding to cart', 'error');
        }
        if (btn) {
            btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-10H5.4"/></svg> Add to Cart';
            btn.disabled = false;
        }
    })
    .catch(() => {
        if (typeof showToast === 'function') showToast('Failed to add to cart', 'error');
        if (btn) {
            btn.innerHTML = 'Add to Cart';
            btn.disabled = false;
        }
    });
};

// Optimized Buy Now Function
window.smartBuyNow = function(productId, quantity = 1, color = '', size = '') {
    const btn = document.querySelector(`#buy-now-btn-${productId}`);
    if (btn) {
        btn.innerHTML = '<span class="loader inline-block"></span>';
        btn.disabled = true;
    }

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            product_id: productId, 
            quantity: quantity,
            color: color,
            size: size
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Meta Pixel Tracking for BuyNow (which is an AddToCart + Redirect)
            if (typeof fbq === 'function') {
                fbq('track', 'AddToCart', {
                    content_ids: [productId],
                    content_type: 'product',
                    value: data.item_price || 0,
                    currency: 'BDT'
                });
            }

            // TikTok Pixel Tracking for BuyNow
            if (typeof ttq === 'object') {
                ttq.track('AddToCart', {
                    content_id: productId,
                    content_type: 'product',
                    value: data.item_price || 0,
                    currency: 'BDT'
                });
            }
            window.location.href = '/checkout';
        } else {
            if (typeof showToast === 'function') showToast(data.message || 'Error processing request', 'error');
            if (btn) {
                        btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> Buy Now';
                        btn.disabled = false;
                    }
                }
            })
            .catch(() => {
                if (typeof showToast === 'function') showToast('Failed to process request', 'error');
                if (btn) {
                    btn.innerHTML = 'Buy Now';
                    btn.disabled = false;
                }
            });
        };

        // Reset buttons on back navigation (BFCache fix)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                document.querySelectorAll('[id^="buy-now-btn-"]').forEach(btn => {
                    btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> Buy Now';
                    btn.disabled = false;
                });
                document.querySelectorAll('[id^="cart-btn-"]').forEach(btn => {
                    btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-10H5.4"/></svg> Add to Cart';
                    btn.disabled = false;
                });
                // Also reset product detail page buttons if they exist
                const detailBuyBtn = document.querySelector('button[onclick*="smartBuyNow"]');
                if (detailBuyBtn && detailBuyBtn.disabled) {
                    detailBuyBtn.disabled = false;
                    if (detailBuyBtn.innerText.includes('Buy Now')) {
                       detailBuyBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> Buy Now';
                    }
                }
            }
        });
        </script>


    <!-- Third Party Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                offset: 100,
                once: true,
                easing: 'ease-out-quad',
                disable: 'mobile', // মোবাইলে AOS বন্ধ করা হলো
            });
        });
    </script>

    @stack('scripts')
    @if(request()->routeIs('home'))
        <script>
            // Preloader script: Force hide in 0.25 seconds max
            const hidePreloader = () => {
                const preloader = document.getElementById('preloader');
                if (preloader && preloader.style.display !== 'none') {
                    preloader.style.transition = 'opacity 0.2s ease-out';
                    preloader.style.opacity = '0';
                    setTimeout(() => {
                        preloader.style.display = 'none';
                        document.body.classList.add('loaded');
                    }, 200);
                }
            };
            // Trigger as soon as window loads OR after 250ms maximum
            window.addEventListener('load', hidePreloader);
            setTimeout(hidePreloader, 250);
        </script>
    @endif

    <!-- Mobile Nav Drawer Overlay -->
    <div x-show="open" @click="close()" style="display: none;" class="fixed inset-0 bg-black/40 backdrop-blur-[2px] z-[9997] md:hidden cursor-pointer" x-transition.opacity></div>

    <!-- Mobile Nav Drawer -->
    <div x-show="open" x-cloak
         class="fixed left-0 top-0 h-full w-[310px] md:hidden overflow-y-auto flex flex-col shadow-[0_0_50px_rgba(0,0,0,0.3)] transition-all duration-500 border-r border-gray-100"
         style="display: none; background-color: #FFFFFF !important; z-index: 99999998 !important;"
         x-transition:enter="transition ease-out duration-500 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full">

        <!-- Drawer Header -->
        <div class="flex items-center justify-between px-6 pb-10 flex-shrink-0 relative overflow-hidden" style="background-color: #FFFFFF !important; padding-top: 40px !important;">
            <div class="absolute top-0 left-0 w-full h-[3px] bg-gradient-to-r from-[#45b86f] to-[#00f2fe]"></div>
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="{{ asset('final logo.jpeg') }}" class="h-8 w-auto object-contain" alt="Logo">
                <span class="brand-logo-font text-xl font-black tracking-tight text-[#1a1a1a]">SmartLook<span class="text-[#45b86f]">BD</span></span>
            </a>
            <button type="button" @click="toggle()" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all active:scale-90">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Nav Items -->
        <nav class="flex-1 py-4 overflow-y-auto" style="background-color: #FFFFFF !important; padding-bottom: 80px !important;">
            <a href="{{ route('home') }}" class="premium-menu-item {{ request()->routeIs('home') ? 'active-link' : '' }}">
                <div class="icon-wrapper bg-gradient-home text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <span class="menu-label">Home</span>
                <svg class="arrow-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
            
            <a href="{{ route('products.index') }}" class="premium-menu-item {{ request()->routeIs('products.index') && !request()->hasAny(['gender', 'category']) ? 'active-link' : '' }}">
                <div class="icon-wrapper bg-gradient-all text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <span class="menu-label">All Products</span>
                <svg class="arrow-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>

            <a href="{{ route('products.index', ['gender' => 'Men']) }}" class="premium-menu-item {{ request('gender') === 'Men' ? 'active-link' : '' }}">
                <div class="icon-wrapper bg-gradient-men text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <span class="menu-label">Men</span>
                <svg class="arrow-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>

            <a href="{{ route('products.index', ['gender' => 'Women']) }}" class="premium-menu-item {{ request('gender') === 'Women' ? 'active-link' : '' }}">
                <div class="icon-wrapper bg-gradient-women text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <span class="menu-label">Women</span>
                <svg class="arrow-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>

            <a href="{{ route('products.index', ['gender' => 'Kids']) }}" class="premium-menu-item {{ request('gender') === 'Kids' ? 'active-link' : '' }}">
                <div class="icon-wrapper bg-gradient-kids text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="menu-label">Kids</span>
                <svg class="arrow-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>

            <a href="{{ route('pages.track-order') }}" class="premium-menu-item {{ request()->routeIs('pages.track-order') ? 'active-link' : '' }}">
                <div class="icon-wrapper bg-gradient-track text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                </div>
                <span class="menu-label">Track Order</span>
                <svg class="arrow-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>

            <div class="px-6 pt-6 pb-2">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Categories</p>
            </div>

            @php
                $allCategories = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->get();
            @endphp
            @foreach($allCategories as $cat)
                <a href="{{ route('category.show', $cat->slug) }}" class="premium-menu-item {{ request()->is('category/' . $cat->slug) ? 'active-link' : '' }} py-4">
                    <span class="menu-label">{{ $cat->name }}</span>
                    <svg class="arrow-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            @endforeach

            <div class="mx-6 my-4 border-t border-gray-100"></div>

            @auth
                <a href="{{ route('account.dashboard') }}" class="premium-menu-item {{ request()->routeIs('account.*') ? 'active-link' : '' }}">
                    <div class="icon-wrapper bg-gradient-acc text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <span class="menu-label">My Account</span>
                    <svg class="arrow-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('orders.index') }}" class="premium-menu-item {{ request()->routeIs('orders.*') ? 'active-link' : '' }}">
                    <div class="icon-wrapper bg-gradient-acc text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <span class="menu-label">My Orders</span>
                    <svg class="arrow-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <a href="{{ route('login') }}" class="premium-menu-item {{ request()->routeIs('login') ? 'active-link' : '' }}">
                    <div class="icon-wrapper bg-gradient-login text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    </div>
                    <span class="menu-label">Login / Register</span>
                    <svg class="arrow-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            @endauth


            @php 
                $waPhone2 = preg_replace('/[^0-9]/', '', $siteSettings->contact_phone ?? '8801700000000');
                if (str_starts_with($waPhone2, '0')) { $waPhone2 = '88' . $waPhone2; }
            @endphp
            <a href="https://wa.me/{{ $waPhone2 }}" target="_blank" class="premium-menu-item">
                <div class="icon-wrapper bg-gradient-wa text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                </div>
                <span class="menu-label">WhatsApp Support</span>
                <svg class="arrow-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        </nav>
    </div>
    
    <!-- Global Image Viewer / Gallery Modal -->
    <div x-data="{ 
        imageViewerOpen: false, 
        images: [],
        currentIndex: 0,
        openModal(images, index = 0) {
            this.images = Array.isArray(images) ? images : [images];
            this.currentIndex = index;
            this.imageViewerOpen = true;
            document.body.style.overflow = 'hidden';
        },
        closeModal() {
            this.imageViewerOpen = false;
            document.body.style.overflow = 'auto';
        },
        next() {
            this.currentIndex = (this.currentIndex + 1) % this.images.length;
        },
        prev() {
            this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
        }
    }"
    @open-image-modal.window="openModal($event.detail.images, $event.detail.index || 0)"
    @keydown.escape.window="closeModal()"
    @keydown.left.window="if(imageViewerOpen && images.length > 1) prev()"
    @keydown.right.window="if(imageViewerOpen && images.length > 1) next()"
    x-show="imageViewerOpen"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 flex items-center justify-center p-4"
    style="display: none; background-color: rgba(0,0,0,0.95); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); z-index: 999999 !important;"
    x-cloak>
        
        <!-- Backdrop Close Area -->
        <div class="absolute inset-0 cursor-pointer" @click="closeModal()"></div>

        <div class="relative w-full max-w-5xl pc-modal-container h-full flex flex-col items-center justify-center pointer-events-none">
            
            <!-- Close Button -->
            <button @click="closeModal()" class="absolute top-4 right-4 text-white/50 hover:text-red-500 transition-all duration-300 hover:rotate-90 pointer-events-auto z-50">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <!-- Navigation Buttons -->
            <template x-if="images.length > 1">
                <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 flex justify-between px-4 sm:px-10 w-full pointer-events-none">
                    <button @click="prev()" class="w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center rounded-full bg-white/5 hover:bg-white/20 text-white transition-all pointer-events-auto shadow-2xl backdrop-blur-md">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="next()" class="w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center rounded-full bg-white/5 hover:bg-white/20 text-white transition-all pointer-events-auto shadow-2xl backdrop-blur-md">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </template>

            <!-- Image Main Wrapper -->
            <div class="relative max-h-[80vh] flex items-center justify-center pointer-events-auto group">
                <img :src="images[currentIndex]" 
                     class="pc-modal-img max-w-full max-h-[80vh] object-contain rounded-lg shadow-[0_0_80px_rgba(0,0,0,0.8)] border border-white/10 transition-all duration-500"
                     :key="currentIndex"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-x-10"
                     x-transition:enter-end="opacity-100 translate-x-0">
            </div>

        </div>

        </div>

        <!-- Image Counter Removed Temporarily -->
    </div>
    <script>
        // Ensure Alpine Store is initialized even if app.js is cached
        document.addEventListener('alpine:init', () => {
            if (!Alpine.store('auth')) {
                Alpine.store('auth', { open: false });
            }
        });

        // Override the compiled app.js function to use our inline one
        window.smartToggleWishlist = function(productId) {
            fetch(`/wishlist/toggle/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                // If the response says login is required, open the modal immediately
                if (data.requires_login || data.guest) {
                    if (window.Alpine) {
                        Alpine.store('auth').open = true;
                    } else {
                        window.dispatchEvent(new CustomEvent('open-auth-modal'));
                    }
                    return;
                }
                
                if (data.success === false) {
                    showToast(data.message || 'Error', 'error');
                    return;
                }

                showToast(data.message, data.in_wishlist ? 'success' : 'info');
                const hearts = document.querySelectorAll(`#wishlist-btn-${productId}`);
                hearts.forEach(btn => {
                    btn.classList.toggle('text-red-500', data.in_wishlist);
                });
            })
            .catch(e => console.error('Wishlist error:', e));
        };
    </script>
    <!-- Floating WhatsApp Button (PC Only) -->
    <style>
        .whatsapp-floating-btn {
            display: none;
        }
        @media (min-width: 768px) {
            .whatsapp-floating-btn {
                display: flex !important;
                position: fixed !important;
                bottom: 25px !important;
                right: 35px !important;
                width: 62px !important;
                height: 62px !important;
                background-color: #25D366 !important;
                color: white !important;
                border-radius: 50% !important;
                justify-content: center !important;
                align-items: center !important;
                z-index: 9999999 !important;
                box-shadow: 0 12px 35px rgba(37, 211, 102, 0.3) !important;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
                text-decoration: none !important;
            }
            .whatsapp-floating-btn:hover {
                transform: scale(1.1) translateY(-5px) !important;
                box-shadow: 0 18px 45px rgba(37, 211, 102, 0.4) !important;
            }
            .whatsapp-ping {
                position: absolute !important;
                inset: -2px !important;
                border-radius: 50% !important;
                background-color: #25D366 !important;
                animation: smooth-ping 3s ease-out infinite !important;
                opacity: 0.3 !important;
                z-index: -1 !important;
            }
            @keyframes smooth-ping {
                0% {
                    transform: scale(0.9);
                    opacity: 0.6;
                }
                50% {
                    opacity: 0.2;
                }
                100% {
                    transform: scale(1.6);
                    opacity: 0;
                }
            }
        }
    </style>
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings->whatsapp_number ?? ($siteSettings->contact_phone ?? '8801700000000')) }}" 
       target="_blank"
       class="whatsapp-floating-btn">
        <span class="whatsapp-ping"></span>
        <svg style="width: 38px; height: 38px;" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
        </svg>
    </a>

@include('partials.quick-add-modal')
</body>
</html>
