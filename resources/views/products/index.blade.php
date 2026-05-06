@extends('layouts.app')

@php
    $catName = isset($activeCategory) ? $activeCategory->name : (isset($pageTitle) ? $pageTitle : 'All Products');
    $catDesc = isset($activeCategory) && $activeCategory->description
        ? \Illuminate\Support\Str::limit(strip_tags($activeCategory->description), 160)
        : 'Explore our premium collection of ' . strtolower($catName) . ' with guaranteed authenticity, fast delivery, and exclusive pricing at SmartLookBD.';
    $catKeywords = 'buy ' . strtolower($catName) . ' bd, ' . strtolower($catName) . ' online, authentic ' . strtolower($catName) . ' bangladesh, SmartLookBD';
@endphp

@section('title', $catName . ' - SmartLookBD')
@section('meta_description', $catDesc)
@section('meta_keywords', $catKeywords)

@section('body_bg', '#FFFFFF')

@section('content')
    <style>
        /* Force override global input borders only for this page */
        #main-products-view input[type="text"] {
            border: none !important;
            background-color: transparent !important;
            box-shadow: none !important;
            height: 100% !important;
            width: 100% !important;
            padding-left: 10px !important;
            font-size: 14px !important;
        }

        #main-products-view input[type="text"]:focus {
            outline: none !important;
            border: none !important;
            box-shadow: none !important;
        }

        #search-wrapper {
            background-color: #FFFFFF !important;
            height: 48px !important;
            border-radius: 4px !important;
            display: flex !important;
            align-items: center !important;
            padding-left: 18px !important;
            width: 100% !important;
            border: 1.5px solid #E5E7EB !important;
            transition: border-color 0.2s ease !important;
        }

        #search-wrapper:focus-within {
            border-color: #000000 !important;
        }

        @media (max-width: 767px) {
            #main-products-view {
                padding-top: 0px !important;
                margin-top: 0px !important;
            }

            .mobile-sticky-search {
                position: -webkit-sticky !important;
                position: sticky !important;
                top: 52px !important;
                /* Force tight alignment to header */
                z-index: 40 !important;
                transform: translateZ(0) !important;
                -webkit-transform: translate3d(0, 0, 0) !important;
                background-color: #F5F5F6 !important;
                border-bottom: 1px solid #E5E7EB !important;
                margin-top: -1px !important;
                margin-left: -16px !important;
                margin-right: -16px !important;
                padding-left: 10px !important;
                padding-right: 10px !important;
                padding-top: 10px !important;
                padding-bottom: 10px !important;
                width: auto !important;
            }

            #search-wrapper {
                background-color: #FFFFFF !important;
                border: 1px solid #D1D5DB !important;
                border-radius: 4px !important;
                height: 42px !important;
            }

            .mobile-filters-btn {
                display: none !important;
            }
        }

        @media (min-width: 1024px) {
            .mobile-only-pill {
                display: none !important;
            }
        }
    </style>

    <div id="main-products-view" class="mx-auto px-4 lg:px-8 py-6" style="max-width: 1600px !important; padding-top: 24px;">
        <div x-data="{ mobileFiltersOpen: false }" class="flex flex-col lg:flex-row gap-8 lg:gap-14">

            <!-- Sidebar Filters -->
            <aside class="hidden lg:block w-64 flex-shrink-0 pr-8" style="border-right: 2px solid #EEEEEE;">
                <div class="sticky top-24 overflow-y-auto custom-scrollbar pr-10 space-y-8"
                    style="height: calc(100vh - 120px); overscroll-behavior: none; padding-bottom: 2rem;">

                    {{-- Special Offers Section --}}
                    <div>
                        <h3 class="text-white text-[11px] font-black uppercase tracking-[0.12em] mb-4 flex items-center gap-2 px-4 py-3 shadow-sm"
                            style="background-color: #ff3f6c !important; color: white !important; border-radius: 0px !important;">
                            Special Offers
                        </h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('products.index', ['featured' => 1]) }}"
                                    class="text-[13px] {{ request('featured') ? 'text-black font-black border-[#45b86f]' : 'text-[#666666] font-bold border-gray-100' }} hover:border-[#45b86f] hover:bg-gray-50 transition-all flex items-center gap-2 px-4 py-2.5 rounded-[4px] border border-solid"
                                    style="{{ request('featured') ? 'background-color: #E6FFFA !important;' : '' }}">
                                    <svg class="w-4 h-4 text-orange-400" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                                    Top Selling
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('products.index', ['new' => 1]) }}"
                                    class="text-[13px] {{ request('new') || (isset($pageTitle) && $pageTitle === 'ðŸ”¥ Flash Sale') ? 'text-black font-black border-[#45b86f]' : 'text-[#666666] font-bold border-gray-100' }} hover:border-[#45b86f] hover:bg-gray-50 transition-all flex items-center gap-2 px-4 py-2.5 rounded-[4px] border border-solid"
                                    style="{{ request('new') || (isset($pageTitle) && $pageTitle === 'ðŸ”¥ Flash Sale') ? 'background-color: #E6FFFA !important;' : '' }}">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.99 7.99 0 0120 13a7.98 7.98 0 01-2.343 5.657z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 16.121A3 3 0 1012.015 11L11 14l.879 2.121z"/></svg>
                                    New Arrival
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('products.index', ['sort' => 'newest']) }}"
                                    class="text-[13px] {{ request('sort') === 'newest' ? 'text-black font-black border-[#45b86f]' : 'text-[#666666] font-bold border-gray-100' }} hover:border-[#45b86f] hover:bg-gray-50 transition-all flex items-center gap-2 px-4 py-2.5 rounded-[4px] border border-solid"
                                    style="{{ request('sort') === 'newest' ? 'background-color: #E6FFFA !important;' : '' }}">
                                    <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                    Top Selling
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('products.index', ['free_shipping' => 1]) }}"
                                    class="text-[13px] {{ request('free_shipping') ? 'text-black font-black border-[#45b86f]' : 'text-[#666666] font-bold border-gray-100' }} hover:border-[#45b86f] hover:bg-gray-50 transition-all flex items-center gap-2 px-4 py-2.5 rounded-[4px] border border-solid"
                                    style="{{ request('free_shipping') ? 'background-color: #E6FFFA !important;' : '' }}">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                    Free Delivery
                                </a>
                            </li>
                        </ul>
                        <div x-data="{ 
                                                    min: {{ request('min_price', $minPrice ?? 0) }}, 
                                                    max: {{ request('max_price', $maxPrice ?? 30000) }},
                                                    minTotal: {{ $minPrice ?? 0 }},
                                                    maxTotal: {{ $maxPrice ?? 30000 }},
                                                    get minP() { return ((this.min - this.minTotal) / (this.maxTotal - this.minTotal)) * 100 },
                                                    get maxP() { return ((this.max - this.minTotal) / (this.maxTotal - this.minTotal)) * 100 }
                                                }" class="mb-8 lg:pr-4"
                            style="height: 140px !important; overflow: hidden !important;">
                            <div class="flex items-center justify-between px-4 py-2.5 mb-8 shadow-sm"
                                style="background-color: #282c3f !important; border-radius: 0px !important;">
                                <h3 class="text-white text-[11px] font-black uppercase tracking-[0.12em]"
                                    style="color: white !important;">Price Range</h3>
                                <a href="{{ route('products.index', request()->except(['min_price', 'max_price', 'page'])) }}"
                                    class="text-[9px] px-2.5 py-1 font-black hover:bg-white/30 transition-colors uppercase"
                                    style="background-color: #FF0000 !important; color: white !important; border-radius: 0px !important;">Clear</a>
                            </div>

                            <div class="px-4">
                                <form method="GET" action="{{ route('products.index') }}" x-ref="priceForm">
                                    @foreach(request()->except(['min_price', 'max_price', 'page']) as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach

                                    <div class="flex justify-between items-center mb-6">
                                        <div class="flex flex-col">
                                            <span class="text-[8px] uppercase font-bold text-gray-400 mb-0.5">Min</span>
                                            <div class="flex items-center gap-0.5">
                                                <span class="text-[11px] font-black text-black">৳</span>
                                                <input type="number" x-model="min"
                                                    class="w-12 text-[11px] font-black border-none p-0 focus:ring-0 bg-transparent"
                                                    @input="if(min > max - 500) min = max - 500; if(min < minTotal) min = minTotal"
                                                    @change="$refs.priceForm.submit()"
                                                    @keydown.enter.prevent="$refs.priceForm.submit()">
                                            </div>
                                        </div>
                                        <div class="h-px bg-gray-200 w-8"></div>
                                        <div class="flex flex-col text-right">
                                            <span class="text-[8px] uppercase font-bold text-gray-400 mb-0.5">Max</span>
                                            <div class="flex items-center justify-end gap-0.5">
                                                <span class="text-[11px] font-black text-black">৳</span>
                                                <input type="number" x-model="max"
                                                    class="w-16 text-[11px] font-black border-none p-0 focus:ring-0 bg-transparent text-right"
                                                    @input="if(max < min + 500) max = min + 500; if(max > maxTotal) max = maxTotal"
                                                    @change="$refs.priceForm.submit()"
                                                    @keydown.enter.prevent="$refs.priceForm.submit()">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="relative h-1 w-full bg-gray-100 rounded-full mb-4">
                                        <div class="absolute h-full bg-black rounded-full pointer-events-none"
                                            :style="'left: ' + minP + '%; right: ' + (100 - maxP) + '%'"></div>

                                        <input type="range" name="min_price" x-model.number="min" :min="minTotal"
                                            :max="maxTotal" step="500" @input="if(min > max - 500) min = max - 500"
                                            @change="$refs.priceForm.submit()"
                                            class="dual-handle-slider absolute pointer-events-none appearance-none w-full h-1 bg-transparent outline-none">

                                        <input type="range" name="max_price" x-model.number="max" :min="minTotal"
                                            :max="maxTotal" step="500" @input="if(max < min + 500) max = min + 500"
                                            @change="$refs.priceForm.submit()"
                                            class="dual-handle-slider absolute pointer-events-none appearance-none w-full h-1 bg-transparent outline-none">
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Categories Section --}}
                        <div>
                            <div class="flex items-center justify-between mb-4 px-4 py-2.5 shadow-sm"
                                style="background-color: #282c3f !important; border-radius: 0px !important;">
                                <h3 class="text-white text-[11px] font-black uppercase tracking-[0.12em]"
                                    style="color: white !important;">Categories</h3>
                                <a href="{{ route('products.index') }}"
                                    class="text-[9px] px-2.5 py-1 font-black hover:bg-white/30 transition-colors uppercase"
                                    style="background-color: #FF0000 !important; color: white !important; border-radius: 0px !important;">Clear</a>
                            </div>
                            <ul class="space-y-2">
                                @foreach($categories as $cat)
                                    @php
                                        $isCatActive = (request('category') === $cat->slug) || (isset($activeCategory) && $activeCategory->id === $cat->id);
                                    @endphp
                                    <li>
                                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                                            class="group flex items-center justify-between py-2.5 px-4 rounded-[4px] border border-solid transition-all {{ $isCatActive ? 'border-[#45b86f] text-black font-black' : 'border-gray-100 text-[#666666] font-bold hover:border-[#45b86f] hover:bg-gray-50 hover:text-black' }}"
                                            style="{{ $isCatActive ? 'background-color: #E6FFFA !important;' : '' }}">
                                            <span class="text-[13px]">{{ $cat->name }}</span>
                                            <span
                                                class="text-[11px] {{ $isCatActive ? 'text-black' : 'text-gray-400' }} font-black group-hover:text-black">{{ $cat->products_count }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1">

                {{-- Search & Mobile Toggle --}}
                <div
                    class="mobile-sticky-search flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4 mt-0 sm:mt-10 mb-3 sm:mb-6 w-full z-40 bg-white py-2 sm:py-0">
                    <div class="flex-1 w-full">
                        <form action="{{ route('products.index') }}" method="GET" class="w-full">
                            @foreach(request()->except(['q', 'page']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <div id="search-wrapper">
                                <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search a product"
                                    autocomplete="off" style="padding-left: 10px !important; width: 100% !important;">
                            </div>
                        </form>
                    </div>

                    <button @click="mobileFiltersOpen = true"
                        class="mobile-filters-btn lg:hidden w-full sm:w-auto h-[45px] px-6 bg-black text-white rounded-[4px] text-[13px] font-bold flex items-center justify-center gap-2 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        Filters
                    </button>
                </div>

                {{-- Gender Selection Pills --}}
                <div class="flex overflow-x-auto hide-scrollbar gap-3 sm:gap-4 mb-4 items-center">
                    @php
                        $activeCatSlug = request('category');
                    @endphp


                    @once
                        <style>
                            .cat-pill {
                                flex-shrink: 0;
                                display: flex;
                                align-items: center;
                                gap: 4px;
                                padding: 5px 12px;
                                border-radius: 9999px;
                                font-size: 10px;
                                font-weight: 900;
                                text-decoration: none;
                                transition: all 0.2s;
                            }

                            .cat-pill svg {
                                width: 12px;
                                height: 12px;
                            }

                            .cat-pill-dot {
                                width: 5px;
                                height: 5px;
                                border-radius: 50%;
                            }

                            @media (min-width: 640px) {
                                .cat-pill {
                                    gap: 8px;
                                    padding: 8px 24px;
                                    font-size: 13px;
                                }

                                .cat-pill svg {
                                    width: 14px;
                                    height: 14px;
                                }
                        </style>
                    @endonce

                    {{-- Men Pill --}}
                    @php $isMenActive = request('gender') === 'Men' || request('category') === 'men'; @endphp
                    <a href="{{ route('products.index', ['gender' => 'Men']) }}" class="cat-pill"
                        style="{{ $isMenActive ? 'background-color: #0066FF !important; color: white !important;' : 'background-color: #F8FAFC !important; color: #475569 !important; border: 1px solid #E2E8F0 !important;' }}">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" />
                        </svg>
                        Men
                    </a>

                    {{-- Women Pill --}}
                    @php $isWomenActive = request('gender') === 'Women' || request('category') === 'women'; @endphp
                    <a href="{{ route('products.index', ['gender' => 'Women']) }}" class="cat-pill"
                        style="{{ $isWomenActive ? 'background-color: #db2777 !important; color: white !important;' : 'background-color: #FDF2F8 !important; color: #db2777 !important; border: 1px solid #FBCFE8 !important;' }}">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 4c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 8c0-1.1-.9-2-2-2h-2.4c-.4-1.2-1.5-2-2.6-2h-4c-1.1 0-2.2.8-2.6 2H5c-1.1 0-2 .9-2 2v6h2v6h2v-6h2v6h2v-6h2v6h2v-6h2v-6z" />
                        </svg>
                        Women
                    </a>

                    {{-- Kids Pill --}}
                    @php $isKidsActive = request('gender') === 'Kids' || request('category') === 'kids'; @endphp
                    <a href="{{ route('products.index', ['gender' => 'Kids']) }}" class="cat-pill"
                        style="{{ $isKidsActive ? 'background-color: #059669 !important; color: white !important;' : 'background-color: #ECFDF5 !important; color: #059669 !important; border: 1px solid #D1FAE5 !important;' }}">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                        Kids
                    </a>

                    {{-- New Arrival Pill (Mobile Only) --}}
                    @php $isNewActive = request()->boolean('new'); @endphp
                    <a href="{{ route('products.index', ['new' => 1]) }}" class="cat-pill mobile-only-pill"
                        style="{{ $isNewActive ? 'background-color: #FBBF24 !important; color: white !important;' : 'background-color: #FFFBEB !important; color: #D97706 !important; border: 1px solid #FEF3C7 !important;' }}">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7 2v11h3v9l7-12h-4l4-8H7z" />
                        </svg>
                        New Arrival
                    </a>
                </div>

                <div class="border-t border-dashed border-gray-100 mb-4"></div>

                {{-- Sub-category Selection (Mobile Only) --}}
                @php
                    $currentSubSlug = request('subcategory');
                    $activeG = request('gender');

                    $displayPills = collect();

                    if ($activeG) {
                        // Find categories that target this gender
                        $displayPills = $categories->filter(function ($c) use ($activeG) {
                            return is_array($c->target_gender) && in_array($activeG, $c->target_gender);
                        });
                    } elseif ($activeCategory) {
                        // If a category is selected, show its subcategories
                        if ($activeCategory->subcategories->count() > 0) {
                            $displayPills = $activeCategory->subcategories;
                        }
                    }
                @endphp

                @if($displayPills->count() > 0)
                    <div class="lg:hidden mb-4 px-1">
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($displayPills as $pill)
                                @if(trim($pill->name))
                                    @php
                                        $isSub = $pill instanceof \App\Models\Subcategory;
                                        $pillSlug = $pill->slug;
                                        $isPillActive = $isSub
                                            ? ($currentSubSlug === $pillSlug)
                                            : (request('category') === $pillSlug);

                                        $url = $isSub
                                            ? route('products.index', array_merge(request()->all(), ['category' => $activeCategory->slug, 'subcategory' => $pillSlug]))
                                            : route('products.index', array_merge(request()->all(), ['category' => $pillSlug]));
                                    @endphp
                                    <a href="{{ $url }}"
                                        class="px-3 py-1.5 rounded-full text-[10px] font-black border transition-all flex items-center gap-1.5"
                                        style="{{ $isPillActive ? 'background-color: #FCD34D !important; border-color: #FCD34D !important; color: #000000 !important;' : 'background-color: #ffffff; border-color: #E5E7EB; color: #4B5563;' }}">
                                        {{ $pill->name }}
                                        @if($pill->products_count > 0)
                                            <span
                                                class="text-[9px] {{ $isPillActive ? 'text-black/70' : 'text-[#9CA3AF]' }} font-medium">{{ $pill->products_count }}</span>
                                        @endif
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Active Filters (Clean Capsule Style) --}}
                @php
                    $hasAnyFilter = request()->anyFilled(['category', 'subcategory', 'q', 'gender', 'min_price', 'max_price', 'featured', 'new', 'free_shipping']);
                @endphp

                @if($hasAnyFilter)
                    <div class="border-t border-dashed border-gray-100 mb-4 lg:hidden"></div>
                    <div class="lg:hidden mb-6 mt-2 px-1">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex flex-wrap gap-2 flex-1">
                                {{-- Gender Pill --}}
                                @if(request('gender'))
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border bg-white text-black text-[11px] font-bold"
                                        style="border-color: #FCD34D !important;">
                                        <span class="capitalize">{{ request('gender') }}</span>
                                        <a href="{{ route('products.index', request()->except(['gender', 'category', 'subcategory'])) }}"
                                            class="hover:opacity-70">
                                            <svg class="w-4 h-4 text-[#FCD34D]" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </div>

                                    @if(request('category'))
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    @endif
                                @endif

                                {{-- Category Pill --}}
                                @if(request('category'))
                                    @php
                                        $catSlug = request('category');
                                        $catName = str_replace('-', ' ', $catSlug);
                                        $catObj = $categories->where('slug', $catSlug)->first();
                                        if ($catObj)
                                            $catName = $catObj->name;
                                    @endphp
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border bg-white text-black text-[11px] font-bold"
                                        style="border-color: #FCD34D !important;">
                                        <span class="capitalize">{{ $catName }}</span>
                                        <a href="{{ route('products.index', request()->except(['category', 'subcategory'])) }}"
                                            class="hover:opacity-70">
                                            <svg class="w-4 h-4 text-[#FCD34D]" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </div>
                                @endif

                                {{-- Subcategory Pill --}}
                                @if(request('subcategory'))
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border bg-white text-black text-[11px] font-bold"
                                        style="border-color: #FCD34D !important;">
                                        <span class="capitalize">{{ str_replace('-', ' ', request('subcategory')) }}</span>
                                        <a href="{{ route('products.index', request()->except('subcategory')) }}"
                                            class="hover:opacity-70">
                                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"
                                                style="color: #FCD34D !important;">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </div>
                                @endif

                                {{-- Search Pill --}}
                                @if(request('q'))
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border bg-white text-black text-[11px] font-bold"
                                        style="border-color: #FCD34D !important;">
                                        <span>"{{ request('q') }}"</span>
                                        <a href="{{ route('products.index', request()->except('q')) }}" class="hover:opacity-70">
                                            <svg class="w-4 h-4 text-[#FCD34D]" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </div>
                                @endif

                                {{-- New Arrival Filter Pill --}}
                                @if(request()->boolean('new'))
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border bg-white text-black text-[11px] font-bold"
                                        style="border-color: #FCD34D !important;">
                                        <span>New Arrival</span>
                                        <a href="{{ route('products.index', request()->except('new')) }}" class="hover:opacity-70">
                                            <svg class="w-4 h-4 text-[#FCD34D]" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <a href="{{ route('products.index') }}" class="text-[12px] font-bold whitespace-nowrap"
                                style="color: #FF0000 !important; text-decoration: underline !important;">Clear</a>
                        </div>
                    </div>
                @endif

                {{-- Product Grid --}}
                @if($products->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
                        @foreach($products as $product)
                            @include('partials.product-card', ['product' => $product])
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-20 px-4 bg-gray-50/50 border border-dashed border-gray-200"
                        style="border-radius: 0px !important;">
                        <div class="bg-white p-6 shadow-sm mb-6" style="border-radius: 0px !important;">
                            <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 mb-2 uppercase tracking-tight">No Products Found</h3>
                        <p class="text-gray-500 text-sm mb-8 text-center max-w-md">Sorry, we couldn't find any products matching
                            your current selection. Try clearing filters or exploring other categories.</p>
                        <a href="{{ route('products.index') }}"
                            class="inline-flex items-center px-8 py-3 font-black text-sm text-white transition-all hover:bg-black uppercase tracking-widest"
                            style="background-color: #ff3f6c !important;">
                            Explore All Products
                        </a>
                    </div>
                @endif

                @if($products->count() > 0)
                    <div class="mt-12 py-8 flex justify-center border-t border-gray-100 italic text-gray-400 text-xs">
                        Showing all {{ $products->count() }} results
                    </div>
                @endif
            </div>
        </div>
    </div>

    @php
        $filterCount = 0;
        if (request()->has('category'))
            $filterCount++;
        if (request()->has('max_price'))
            $filterCount++;
        if (request()->has('featured'))
            $filterCount++;
        if (request()->has('new'))
            $filterCount++;
        if (request()->has('free_shipping'))
            $filterCount++;
    @endphp



    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #CCCCCC;
            border-radius: 10px;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Dual Range Slider Styles */
        .dual-handle-slider::-webkit-slider-thumb {
            pointer-events: auto;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background-color: #000;
            border: 2px solid #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            appearance: none;
            z-index: 50;
        }

        .dual-handle-slider::-moz-range-thumb {
            pointer-events: auto;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background-color: #000;
            border: 2px solid #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            z-index: 50;
        }
    </style>
    <script>
        // Clean URL after filtering on mobile to allow 1-reload reset
        document.addEventListener('DOMContentLoaded', function () {
            if (window.innerWidth < 1024) { // Only for mobile/tablet
                if (window.location.search.length > 0) {
                    // This clears the address bar but keeps the products filtered
                    // So the next browser refresh will hit the base URL directly
                    history.replaceState(null, '', window.location.pathname);
                }
            }
        });
    </script>
@endsection