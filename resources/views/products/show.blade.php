@extends('layouts.app')
@section('meta_title', $product->meta_title ?: $product->name . ' - SmartLookBD')
@section('meta_description', $product->meta_description ?: Str::limit(strip_tags($product->description ?? ''), 160))
@if($product->meta_keywords)
@section('meta_keywords', $product->meta_keywords)
@endif
@section('og_image', $product->thumbnail_url)

@push('styles')
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@@type": "Product",
  "name": "{{ $product->name }}",
  "image": [
    "{{ $product->thumbnail_url }}"
    @foreach($product->images as $img)
    ,"{{ $img->image_url }}"
    @endforeach
  ],
  "description": "{{ $product->meta_description ?? $product->short_description ?? Str::limit(strip_tags($product->description ?? ''), 160) }}",
  "sku": "{{ $product->id }}",
  "brand": {
    "@@type": "Brand",
    "name": "{{ $product->brand ?? 'SmartLookBD' }}"
  },
  "offers": {
    "@@type": "Offer",
    "url": "{{ url()->current() }}",
    "priceCurrency": "BDT",
    "price": "{{ $product->effective_price }}",
    "availability": "https://schema.org/{{ $product->stock > 0 ? 'InStock' : 'OutOfStock' }}",
    "itemCondition": "https://schema.org/NewCondition"
  }
}
</script>
<style>
    .product-details-page {
        background-color: #fff;
        font-family: 'Inter', sans-serif;
    }
    
    .details-box-wrapper {
        border: none;
        padding: 0;
        background: transparent;
    }
    @media (min-width: 1024px) {
        .details-box-wrapper { 
            border: 1px solid #eee; 
            border-radius: 8px;
            padding: 32px; 
            background: #fff;
        }
    }
    @media (max-width: 1023px) {
        .details-box-wrapper { 
            border: 1px solid #eee; 
            border-radius: 8px;
            padding: 20px; 
            background: #fff;
            margin-top: 5px;
        }
    }

    .fab-title {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
        line-height: 1.2;
    }
    @media (min-width: 768px) {
        .fab-title { font-size: 26px; }
    }
    .fab-price {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
        display: flex;
        align-items: baseline;
        gap: 12px;
    }
    .fab-price .old {
        font-size: 14px;
        color: #aaa;
        text-decoration: line-through;
        font-weight: 400;
    }
    .fab-price .discount {
        font-size: 12px;
        color: #ff4d4d;
        font-weight: 600;
    }
    .option-label {
        font-size: 13px;
        font-weight: 700;
        color: #333;
        margin-bottom: 12px;
        display: block;
    }
    .fab-size-btn {
        min-width: 60px;
        height: 36px;
        border: 1px solid #ddd;
        border-radius: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
        color: #333;
        transition: all 0.2s;
    }
    .fab-size-btn.active {
        background-color: #000 !important;
        color: #fff !important;
        border-color: #000 !important;
    }
    
    .fab-qty-box {
        display: flex;
        align-items: center;
        border: 1px solid #999;
        border-radius: 1px;
        height: 38px;
        width: 110px;
        overflow: hidden;
        flex-shrink: 0;
    }
    .fab-qty-btn {
        width: 35px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #000;
        cursor: pointer;
        background: #fff;
        border: none !important;
    }
    .fab-qty-input {
        width: 40px;
        height: 100%;
        text-align: center;
        border-left: 1px solid #999 !important;
        border-right: 1px solid #999 !important;
        border-top: none !important;
        border-bottom: none !important;
        font-weight: 700;
        font-size: 14px;
        color: #000;
        background: #fff;
        padding: 0;
        border-radius: 0;
    }

    .fab-cart-btn {
        height: 38px;
        background: #000;
        color: #fff;
        padding: 0 25px;
        border: none;
        border-radius: 1px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        white-space: nowrap;
        flex: 1;
        cursor: pointer;
    }
    @media (min-width: 768px) {
        .fab-cart-btn { flex: none; min-width: 180px; }
    }
    .fab-cart-btn svg {
        width: 16px;
        height: 16px;
    }

    .fab-buy-btn {
        height: 38px;
        background: #45b86f;
        color: #fff;
        padding: 0 45px;
        border: none;
        border-radius: 1px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        white-space: nowrap;
        flex: 1;
        cursor: pointer;
        transition: all 0.2s;
        width: 100%;
    }
    @media (min-width: 768px) {
        .fab-buy-btn { flex: none; width: auto; }
    }
    .fab-buy-btn:hover {
        background: #369a5a;
    }
    .fab-buy-btn svg {
        width: 16px;
        height: 16px;
    }

    .fab-service-box {
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
        padding: 24px 0;
        margin: 32px 0;
    }
    .fab-service-header {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 12px;
    }
    .fab-service-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        color: #666;
        font-size: 11px;
        font-weight: 500;
    }
    .fab-service-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .dot-green { width: 6px; height: 6px; background: #22c55e; border-radius: 50%; }

    .specs-title {
        font-size: 14px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 24px;
    }
    .spec-item {
        font-size: 13px;
        line-height: 2.2;
        margin-bottom: 2px;
    }
    .spec-key { font-weight: 700; color: #333; display: inline-block; width: 140px; }
    .spec-value { color: #666; font-weight: 400; }

    .sale-tab {
        position: absolute;
        top: 20px;
        left: 20px;
        background: #FF4D4D;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        text-transform: uppercase;
        z-index: 10;
        border-radius: 1px;
    }

    /* Image Zoom Styles */
    .img-zoom-container {
        cursor: crosshair;
    }

    .zoom-lens-indicator {
        position: absolute;
        width: 150px;
        height: 150px;
        border: 2px solid rgba(0, 0, 0, 0.3);
        background: rgba(255, 255, 255, 0.15);
        pointer-events: none;
        z-index: 30;
        box-shadow: 0 0 0 9999px rgba(0,0,0,0.08);
    }

    .zoom-preview-panel {
        position: absolute;
        top: 0;
        left: calc(100% + 24px);
        width: 500px;
        height: 500px;
        border: 1px solid #eee;
        background-color: #fff;
        background-repeat: no-repeat;
        background-size: 250%;
        z-index: 1000 !important;
        box-shadow: 0 8px 40px rgba(0,0,0,0.15);
        border-radius: 4px;
        pointer-events: none;
    }

    @media (min-width: 1024px) and (max-width: 1440px) {
        .zoom-preview-panel {
            width: 380px !important;
            height: 380px !important;
            left: calc(100% + 5px) !important;
        }
        .fab-title { font-size: 22px; }
        .details-box-wrapper { padding: 24px; }
        .fab-cart-btn, .fab-buy-btn { padding: 0 15px; font-size: 10px; }
    }

    @media (max-width: 1023px) {
        .img-zoom-container { cursor: pointer; }
        .zoom-lens-indicator, .zoom-preview-panel { display: none !important; }

        /* Force hide system bars when zoom is active */
        body.mobile-zoom-active #main-mobile-navbar,
        body.mobile-zoom-active #mobile-bottom-nav,
        body.mobile-zoom-active .navbar,
        body.mobile-zoom-active #side-cart-component,
        body.mobile-zoom-active .whatsapp-floating-btn,
        body.mobile-zoom-active [class*="whatsapp"],
        body.mobile-zoom-active [class*="announcement"],
        body.mobile-zoom-active footer,
        body.mobile-zoom-active [id*="mobile-bottom-nav"] {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }
    }

    /* Mobile Zoom Modal Styles */
    .mobile-gallery-container {
        display: flex !important;
        height: 100%;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
    }
    .mobile-gallery-container::-webkit-scrollbar,
    .scrollbar-hide::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }
    .mobile-gallery-item {
        flex-shrink: 0;
        width: 100%;
        height: 100%;
        scroll-snap-align: start;
        scroll-snap-stop: always;
    }

    .desktop-gallery-container {
        display: none !important;
    }

    @media (min-width: 1024px) {
        .mobile-gallery-container {
            display: none !important;
        }
        .desktop-gallery-container {
            display: block !important;
            position: relative;
            width: 100%;
            overflow: hidden;
        }
    }

    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="product-details-page">
    <div class="max-w-[1440px] mx-auto px-4 lg:px-20 py-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-12 lg:gap-16 items-start">
            
            <!-- Gallery (Left) -->
            @php
                $allImages = array_merge([$product->thumbnail_url], $product->images->pluck('image_url')->toArray());
                $imageToColor = [];
                if (!empty($product->colors) && !empty($product->color_image_indices)) {
                    foreach ($product->colors as $idx => $colorName) {
                        $imgIdx = $product->color_image_indices[$idx] ?? null;
                        if ($imgIdx !== null && is_numeric($imgIdx)) {
                            $imageToColor[(int)$imgIdx - 1] = $colorName;
                        }
                    }
                }
            @endphp
            <div class="mx-auto w-full" style="max-width: 650px;" x-data="{ 
                activeImg: '{{ $product->thumbnail_url }}', 
                loaded: false,
                images: [
                    '{{ $product->thumbnail_url }}'
                    @foreach($product->images as $img)
                    ,'{{ $img->image_url }}'
                    @endforeach
                ],
                imageToColor: {{ json_encode($imageToColor) }},
                currentIndex: 0,
                zooming: false,
                zoomLocked: false,
                mobileZoom: false,
                mobileZoomScale: 1,
                translateX: 0,
                translateY: 0,
                isDragging: false,
                moved: false,
                startX: 0,
                startY: 0,
                startTranslateX: 0,
                startTranslateY: 0,
                zoomX: 0,
                zoomY: 0,
                lensX: 0,
                lensY: 0,
                pcHover: false,
                startDragging(e) {
                    if (this.mobileZoomScale <= 1) return;
                    this.isDragging = true;
                    this.moved = false;
                    const point = e.touches ? e.touches[0] : e;
                    this.startX = point.clientX;
                    this.startY = point.clientY;
                    this.startTranslateX = this.translateX;
                    this.startTranslateY = this.translateY;
                },
                stopDragging() {
                    this.isDragging = false;
                    setTimeout(() => { this.moved = false; }, 300);
                },
                clampTranslate(tx, ty) {
                    const container = this.$refs.mobileZoomContainer;
                    if (!container) return { x: 0, y: 0 };
                    const img = container.querySelector('img');
                    if (!img || this.mobileZoomScale <= 1) return { x: 0, y: 0 };
                    
                    // Calculate actual rendered image dimensions inside the container (object-contain)
                    const imgRatio = img.naturalWidth / img.naturalHeight;
                    const containerRatio = container.offsetWidth / container.offsetHeight;
                    
                    let renderedWidth = container.offsetWidth;
                    let renderedHeight = container.offsetHeight;
                    
                    if (img.naturalWidth && img.naturalHeight) {
                        if (imgRatio > containerRatio) {
                            renderedHeight = container.offsetWidth / imgRatio;
                        } else {
                            renderedWidth = container.offsetHeight * imgRatio;
                        }
                    }
                    
                    // Max translation is half of the overflow amount
                    const maxX = Math.max(0, (renderedWidth * this.mobileZoomScale - container.offsetWidth) / 2);
                    const maxY = Math.max(0, (renderedHeight * this.mobileZoomScale - container.offsetHeight) / 2);
                    
                    return {
                        x: Math.max(-maxX, Math.min(maxX, tx)),
                        y: Math.max(-maxY, Math.min(maxY, ty))
                    };
                },
                moveDragging(e) {
                    if (!this.isDragging || this.mobileZoomScale <= 1) return;
                    if (e.cancelable) e.preventDefault();
                    
                    const point = e.touches ? e.touches[0] : e;
                    const diffX = point.clientX - this.startX;
                    const diffY = point.clientY - this.startY;
                    
                    if (Math.abs(diffX) > 5 || Math.abs(diffY) > 5) {
                        this.moved = true;
                    }

                    if (this.moved) {
                        const clamped = this.clampTranslate(
                            this.startTranslateX + diffX,
                            this.startTranslateY + diffY
                        );
                        this.translateX = clamped.x;
                        this.translateY = clamped.y;
                    }
                },
                lastClickTime: 0,
                handleTapZoom(e) {
                    const currentTime = new Date().getTime();
                    const timeDiff = currentTime - this.lastClickTime;
                    
                    if (timeDiff < 400 && timeDiff > 0) {
                        this.lastClickTime = 0;
                        this.moved = false;
                        if (this.mobileZoomScale > 1) {
                            this.mobileZoomScale = 1;
                            this.translateX = 0;
                            this.translateY = 0;
                        } else {
                            const targetScale = 2;
                            const rect = e.currentTarget.getBoundingClientRect();
                            
                            const centerX = rect.left + rect.width / 2;
                            const centerY = rect.top + rect.height / 2;
                            
                            const clientX = e.clientX || (e.changedTouches ? e.changedTouches[0].clientX : centerX);
                            const clientY = e.clientY || (e.changedTouches ? e.changedTouches[0].clientY : centerY);
                            
                            const offsetX = clientX - centerX;
                            const offsetY = clientY - centerY;
                            
                            this.mobileZoomScale = targetScale;
                            // Move the point under the finger to the center
                            const clamped = this.clampTranslate(-offsetX * (targetScale - 1), -offsetY * (targetScale - 1));
                            this.translateX = clamped.x;
                            this.translateY = clamped.y;
                        }
                    } else {
                        // Single tap — only record time if not a drag
                        if (!this.moved) {
                            this.lastClickTime = currentTime;
                        }
                    }
                },
                toggleMobileZoom() {
                    this.mobileZoom = !this.mobileZoom;
                    this.mobileZoomScale = 1;
                    this.translateX = 0;
                    this.translateY = 0;
                    if (this.mobileZoom) {
                        document.body.classList.add('mobile-zoom-active');
                    } else {
                        document.body.classList.remove('mobile-zoom-active');
                    }
                },
                zoomIn() {
                    if (this.mobileZoomScale < 4) {
                        const oldScale = this.mobileZoomScale;
                        this.mobileZoomScale = parseFloat((this.mobileZoomScale + 0.5).toFixed(1));
                        this.translateX = this.translateX * (this.mobileZoomScale / oldScale);
                        this.translateY = this.translateY * (this.mobileZoomScale / oldScale);
                    }
                },
                zoomOut() {
                    if (this.mobileZoomScale > 1) {
                        const oldScale = this.mobileZoomScale;
                        this.mobileZoomScale = parseFloat((this.mobileZoomScale - 0.5).toFixed(1));
                        if (this.mobileZoomScale <= 1) {
                            this.translateX = 0;
                            this.translateY = 0;
                        } else {
                            let newTx = this.translateX * (this.mobileZoomScale / oldScale);
                            let newTy = this.translateY * (this.mobileZoomScale / oldScale);
                            
                            // clamp immediately based on new scale
                            const container = this.$refs.mobileZoomContainer;
                            if (container) {
                                const maxX = (this.mobileZoomScale - 1) * container.offsetWidth / 2;
                                const maxY = (this.mobileZoomScale - 1) * container.offsetHeight / 2;
                                newTx = Math.max(-maxX, Math.min(maxX, newTx));
                                newTy = Math.max(-maxY, Math.min(maxY, newTy));
                            }
                            
                            this.translateX = newTx;
                            this.translateY = newTy;
                        }
                    }
                },
                resetZoom() {
                    this.mobileZoomScale = 1;
                    this.translateX = 0;
                    this.translateY = 0;
                },
                handleDblClick() {
                    if (this.mobileZoomScale === 1) {
                        this.mobileZoomScale = 2;
                    } else {
                        this.mobileZoomScale = 1;
                        this.translateX = 0;
                        this.translateY = 0;
                    }
                },
                changeImg(img, index) {
                    this.zooming = false;
                    this.pcHover = false;
                    this.zoomLocked = true;
                    setTimeout(() => { this.zoomLocked = false; }, 500);
                    
                    if (this.activeImg !== img) {
                        this.loaded = false;
                        this.activeImg = img;
                        this.currentIndex = index;
                    }
                    if (window.innerWidth < 1024 && this.$refs.scrollContainer) {
                        const container = this.$refs.scrollContainer;
                        container.scrollTo({
                            left: container.offsetWidth * index,
                            behavior: 'smooth'
                        });
                    }
                },
                handleScroll() {
                    if (window.innerWidth >= 1024) return;
                    const container = this.$refs.scrollContainer;
                    if (!container) return;
                    const index = Math.round(container.scrollLeft / container.offsetWidth);
                    if (index !== this.currentIndex) {
                        this.currentIndex = index;
                        this.activeImg = this.images[index];
                    }
                },
                handleDesktopZoom(e) {
                    if (!this.pcHover || window.innerWidth < 1024 || this.zoomLocked) {
                        this.zooming = false;
                        return;
                    }
                    
                    const box = this.$refs.desktopImg;
                    if (!box) return;
                    const rect = box.getBoundingClientRect();
                    
                    this.zooming = true;
                    this.zoomX = Math.max(0, Math.min(100, ((e.clientX - rect.left) / rect.width) * 100));
                    this.zoomY = Math.max(0, Math.min(100, ((e.clientY - rect.top) / rect.height) * 100));
                    this.lensX = e.clientX - rect.left;
                    this.lensY = e.clientY - rect.top;
                },
                init() {
                    this.$watch('currentIndex', (val) => {
                        if (this.imageToColor && this.imageToColor[val]) {
                            window.dispatchEvent(new CustomEvent('switch-color', { detail: this.imageToColor[val] }));
                        }
                    });
                    this._zGlobalReset = (e) => {
                        if (!e.target.closest('.zoom-trigger-zone') && !e.target.closest('.thumbnail-item')) {
                            this.zooming = false;
                            this.pcHover = false;
                        }
                    };
                    window.addEventListener('mousemove', this._zGlobalReset);
                },
                destroy() {
                    if (this._zGlobalReset) window.removeEventListener('mousemove', this._zGlobalReset);
                }
            }"
            x-on:switch-image.window="changeImg(images[$event.detail], $event.detail); pcHover = false; zooming = false"
            x-init="init()">
                <div class="relative">
                    <div class="relative w-full aspect-square mb-2 md:mb-6 border border-gray-100/50 bg-[#FBFBFB] img-zoom-container overflow-hidden" 
                         x-ref="zoomBox">
                        
                        @if($product->stock <= 0)
                        <div class="absolute inset-0 z-40 flex items-center justify-center pointer-events-none">
                            <div class="absolute inset-0 bg-white/20 backdrop-blur-[2px]"></div>
                            <div class="relative z-50 text-white px-8 py-4 shadow-2xl rounded-sm transform -rotate-3" style="background-color: #ff3f6c !important;">
                                <span class="text-[14px] sm:text-[18px] font-black uppercase tracking-[0.4em] whitespace-nowrap block text-center">Out of Stock</span>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Mobile Scrollable Container -->
                        <div class="w-full h-full mobile-gallery-container"
                             x-ref="scrollContainer"
                             @scroll.debounce.50ms="handleScroll()">
                            @foreach($allImages as $index => $img)
                            <div class="mobile-gallery-item relative h-full cursor-zoom-in">
                                <div class="absolute inset-0 flex items-center justify-center" @click="toggleMobileZoom()">
                                    <img src="{{ $img }}" class="w-full h-full object-contain active:scale-95 transition-transform duration-200">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Desktop Single Image View -->
                        <template x-if="window.innerWidth >= 1024">
                            <div class="desktop-gallery-container h-full cursor-zoom-in" 
                                 @click="$dispatch('open-image-modal', { images: images, index: currentIndex })">
                                <div class="absolute inset-0 flex items-center justify-center"
                                     x-ref="desktopImg">
                                    
                                    <!-- Invisible Hover Trigger Zone -->
                                    <div class="absolute inset-0 z-30 cursor-crosshair zoom-trigger-zone"
                                         @mouseenter="pcHover = true"
                                         @mousemove="handleDesktopZoom($event)"
                                         @mouseleave="pcHover = false; zooming = false" @click="$dispatch('open-image-modal', { images: images, index: currentIndex })"></div>
                                    @if($product->discount_percent > 0)
                                    <div class="sale-tab">SALE</div>
                                    @endif

                                    <!-- Loading Spinner -->
                                    <div x-show="!loaded" 
                                         class="absolute inset-0 flex items-center justify-center bg-white z-20">
                                        <div class="w-10 h-10 border-4 border-gray-100 border-t-black rounded-full animate-spin"></div>
                                    </div>

                                    <img :src="activeImg" 
                                         @load="loaded = true"
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-contain transition-opacity duration-300"
                                         :class="loaded ? 'opacity-100' : 'opacity-0'"
                                         style="pointer-events: none;">
                                </div>
                            </div>
                        </template>

                        <!-- Common Overlays (Mobile Only) -->
                        <div class="md:hidden">
                            @if($product->discount_percent > 0)
                            <div class="sale-tab">SALE</div>
                            @endif
    
                            {{-- Page Indicator --}}
                            <div class="absolute bottom-2 left-2 bg-black/40 backdrop-blur-md text-white px-2.5 py-1 rounded-full text-[11px] font-bold z-40 shadow-lg border border-white/5 pointer-events-none">
                                <span x-text="currentIndex + 1"></span>
                                <span class="opacity-40">/</span>
                                <span>{{ count($allImages) }}</span>
                            </div>
    
                            {{-- Zoom Pill (Mobile Only) --}}
                            <div x-show="!mobileZoom" class="absolute bottom-2 right-2 bg-black/60 backdrop-blur-sm text-white px-3 py-1.5 rounded-full flex items-center gap-2 z-40 shadow-md border border-white/10 pointer-events-none lg:hidden">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                </svg>
                                <span class="text-[10px] font-bold tracking-tight">Click to zoom</span>
                            </div>
                        </div>

                        <!-- Lens indicator on the image (Desktop only) -->
                        <template x-if="zooming && pcHover">
                            <div class="zoom-lens-indicator hidden lg:block"
                                 :style="'left: ' + (lensX - 75) + 'px; top: ' + (lensY - 75) + 'px;'"
                                 x-cloak></div>
                        </template>
                    </div>

                    <!-- Zoom Preview Panel (appears to the right) -->
                    <template x-if="zooming && pcHover">
                        <div x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="zoom-preview-panel"
                             :style="'background-image: url(' + activeImg + '); background-position: ' + zoomX + '% ' + zoomY + '%; z-index: 1000 !important;'"
                             x-cloak>
                        </div>
                    </template>

                    <!-- Mobile Zoom Modal Overlay -->
                    <template x-teleport="body">
                         <div x-show="mobileZoom" 
                             class="fixed inset-0"
                             style="z-index: 2147483647 !important; background-color: rgba(10, 10, 10, 0.93) !important; backdrop-filter: blur(4px) !important; -webkit-backdrop-filter: blur(4px) !important;"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             @keydown.window.escape="toggleMobileZoom()"
                             x-cloak>
                            
                            <template x-if="mobileZoom">
                                <div class="contents">
                                    {{-- Clickable background to close (behind image) --}}
                                    <div class="absolute inset-0" @click.self="toggleMobileZoom()"></div>

                                    {{-- Image Area - centered on full screen --}}
                                    <div class="absolute inset-0 flex items-center justify-center p-4 pt-12 pb-[58px] pointer-events-none z-10">
                                    <div class="relative bg-transparent overflow-hidden pointer-events-auto"
                                         :style="window.innerWidth >= 1024 ? 'width: 80vw; height: 80vh;' : (window.innerWidth >= 768 ? 'width: 85vw; height: 65vh;' : 'width: 96vw; height: 45vh;')"
                                         style="touch-action: none; transform: translateY(-35px) !important;"
                                             x-ref="mobileZoomContainer"
                                             @mousedown="startDragging($event)"
                                             @mousemove="moveDragging($event)"
                                             @mouseup="stopDragging()"
                                             @mouseleave="stopDragging()"
                                             @touchstart.passive="startDragging($event)"
                                             @touchmove.prevent="moveDragging($event)"
                                             @touchend="stopDragging()">
                                             
                                            <div class="w-full h-full flex items-center justify-center cursor-move"
                                                 @click="handleTapZoom($event)">
                                                <img :src="activeImg" 
                                                     :class="isDragging ? '' : 'transition-transform duration-300 ease-out'"
                                                     :style="`transform: translate(${translateX}px, ${translateY}px) scale(${mobileZoomScale});`"
                                                     class="max-w-full max-h-full object-contain pointer-events-none select-none">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            {{-- Bottom Controls - always at the very bottom --}}
                            <div class="absolute bottom-0 left-0 right-0 flex flex-col items-center gap-3 pb-4 md:pb-20 pt-3 z-20 pointer-events-auto">
                                {{-- Percentage --}}
                                <div class="bg-black/60 border border-white/10 text-white px-2.5 py-0.5 rounded text-[10px] font-bold tracking-widest">
                                    <span x-text="Math.round(mobileZoomScale * 100) + '%'"></span>
                                </div>

                                <div class="flex items-center gap-4">
                                    {{-- Minus Button --}}
                                    <button @click.stop="zoomOut()" 
                                            class="w-14 h-14 rounded-full flex items-center justify-center shadow-xl active:scale-90 transition-transform disabled:opacity-40"
                                            style="background-color: #6b7280 !important;"
                                            :disabled="mobileZoomScale <= 1">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                                        </svg>
                                    </button>

                                    {{-- Reset Button --}}
                                    <button @click.stop="resetZoom()" 
                                            class="w-14 h-14 bg-[#1a1a1a] border border-white/20 rounded-full flex items-center justify-center shadow-xl active:scale-90 transition-transform">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>

                                    {{-- Plus Button --}}
                                    <button @click.stop="zoomIn()" 
                                            class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-xl active:scale-90 transition-transform disabled:opacity-40" :disabled="mobileZoomScale >= 4">
                                        <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <style>
                    .thumbnail-container-custom {
                        display: flex !important;
                        gap: 0.75rem !important; /* gap-3 */
                    }
                    @media (max-width: 767px) {
                        .thumbnail-container-custom {
                            flex-wrap: nowrap !important;
                            overflow-x: auto !important;
                            justify-content: flex-start !important;
                        }
                    }
                    @media (min-width: 768px) {
                        .thumbnail-container-custom {
                            flex-wrap: wrap !important;
                            overflow-x: visible !important;
                            justify-content: center !important;
                        }
                    }
                </style>
                <div class="thumbnail-container-custom relative z-50 pb-1 scrollbar-hide min-h-[60px] md:min-h-[70px] w-full"
                     @mouseover="zooming = false; pcHover = false">
                    @foreach($allImages as $index => $img)
                        <div @click="changeImg('{{ $img }}', {{ $index }})" 
                             class="thumbnail-item w-14 h-14 sm:w-16 sm:h-16 border-2 cursor-pointer bg-white flex-shrink-0 transition-all duration-200 rounded-sm"
                             :class="currentIndex === {{ $index }} ? 'border-black' : 'border-gray-200'">
                            <img src="{{ $img }}" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="right-column-container">
                <div class="details-box-wrapper" 
                     @mouseover="zooming = false; pcHover = false"
                     x-on:switch-color.window="color = $event.detail"
                     x-data="{ 
                    qty: 1, 
                    max: {{ $product->stock }},
                    color: '{{ count($product->colors ?? []) === 1 ? ($product->colors)[0] : "" }}',
                    size: '{{ count($product->sizes ?? []) === 1 ? ($product->sizes)[0] : "" }}',
                    validate() {
                        if (this.max > 0) {
                            @if(!empty($product->colors) && is_array($product->colors) && count($product->colors) > 0) 
                                if(!this.color) { showToast('Please select a color', 'error'); return false; } 
                            @endif
                            @if(!empty($product->sizes) && is_array($product->sizes) && count($product->sizes) > 0) 
                                if(!this.size) { showToast('Please select a size', 'error'); return false; } 
                            @endif
                        }
                        return true;
                    }
                }">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="fab-title mb-1">{{ $product->name }}</h1>
                        @if($product->sku)
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest flex items-center gap-2">
                            Product Code: <span class="text-gray-600 font-black">{{ $product->sku }}</span>
                        </p>
                        @endif
                    </div>
                    @php
                        $inWishlist = auth()->check() && auth()->user()->wishlist && auth()->user()->wishlist->contains('product_id', $product->id);
                    @endphp
                    <button onclick="smartToggleWishlist({{ $product->id }})" 
                            id="wishlist-btn-{{ $product->id }}"
                            class="p-2.5 border border-gray-100 rounded-full hover:bg-gray-50 {{ $inWishlist ? 'text-red-500' : 'text-gray-400' }} transition-all duration-300">
                        <svg class="w-6 h-6" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                </div>

                <div class="flex items-center gap-3 mb-8">
                    <div class="fab-price">
                        <span>৳ {{ number_format($product->effective_price) }}</span>
                        @if($product->effective_price < $product->price)
                            <span class="old">৳ {{ number_format($product->price) }}</span>
                            <span class="discount">{{ $product->discount_percent }}% Off</span>
                        @endif
                    </div>
                    @if($product->stock <= 0)
                        <span class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest rounded-sm border border-red-100">Stock Out</span>
                    @else
                        <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-widest rounded-sm border border-green-100">In Stock</span>
                    @endif
                </div>

                @if(!empty($product->colors) && is_array($product->colors) && count(array_filter($product->colors)) > 0)
                @php 
                    $swatches = $product->color_swatches ?? []; 
                    $indices = $product->color_image_indices ?? [];
                @endphp
                <div class="mb-8">
                    <p class="option-label">Select Color: <span class="text-neutral-400 font-normal ml-1" x-text="color"></span></p>
                    <div class="flex flex-wrap gap-3">
                        @foreach($product->colors as $index => $c)
                        @if(!empty($c))
                            @php
                                $isColorCode = preg_match('/^(#|rgb|hsl)/', $c);
                                $hasSwatch = isset($swatches[$index]) && !empty($swatches[$index]);
                                $imgIdx = isset($indices[$index]) && is_numeric($indices[$index]) ? (int)$indices[$index] : null;
                            @endphp
                            <button type="button" @click="color = '{{ $c }}'; if({{ $imgIdx !== null ? $imgIdx : 'null' }} !== null) { $dispatch('switch-image', {{ $imgIdx - 1 }}) }" 
                                    :class="color === '{{ $c }}' ? 'active-color ring-2 ring-black ring-offset-2' : 'border border-gray-400 hover:border-black'" 
                                    class="relative transition-all duration-300 {{ ($hasSwatch || $isColorCode) ? 'w-9 h-9 rounded-full overflow-hidden' : 'fab-size-btn px-4 capitalize' }}"
                                    style="{{ $isColorCode && !$hasSwatch ? 'background-color: '.$c.';' : '' }}">
                                
                                @if($hasSwatch)
                                    <img src="{{ asset('storage/' . $swatches[$index]) }}" class="w-full h-full object-cover">
                                @elseif(!$isColorCode)
                                    {{ $c }}
                                @endif

                                @if($hasSwatch || $isColorCode)
                                    <div x-show="color === '{{ $c }}'" class="absolute inset-0 flex items-center justify-center bg-black/10">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                @endif
                            </button>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!empty($product->sizes) && is_array($product->sizes) && count(array_filter($product->sizes)) > 0)
                <div class="mb-8">
                    <p class="option-label">Select Size:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->sizes as $s)
                        @if(!empty($s))
                        <button type="button" @click="size = '{{ $s }}'" :class="size === '{{ $s }}' ? 'active' : ''" class="fab-size-btn">
                            {{ $s }}
                        </button>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="flex flex-wrap items-center gap-3 mb-10 pt-4">
                    @if($product->stock > 0)
                        <div class="fab-qty-box">
                            <div @click="if(qty > 1) qty--" class="fab-qty-btn">−</div>
                            <input type="text" x-model="qty" class="fab-qty-input" readonly>
                            <div @click="if(qty < max) qty++" class="fab-qty-btn">+</div>
                        </div>
                        <button @click="if(validate()) smartAddToCart({{ $product->id }}, qty, color, size)" class="fab-cart-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            Add To Cart
                        </button>
                        <button @click="if(validate()) smartBuyNow({{ $product->id }}, qty, color, size)" id="buy-now-btn-{{ $product->id }}" class="fab-buy-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Buy Now
                        </button>
                    @else
                        <div class="w-full py-4 px-6 bg-gray-50 border-2 border-dashed border-gray-200 rounded-sm flex items-center justify-center gap-3 text-gray-400">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                             <span class="text-[12px] font-black uppercase tracking-[0.2em]">Currently Out of Stock</span>
                        </div>
                    @endif
                </div>

                <div class="fab-service-box">
                    <div class="fab-service-header">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Easy Returns & Exchange
                    </div>
                    <div class="fab-service-list">
                        <div class="fab-service-item"><div class="dot-green"></div> Tell us within 7 days</div>
                        <div class="fab-service-item"><div class="dot-green"></div> Free return shipping*</div>
                        <div class="fab-service-item"><div class="dot-green"></div> Instant refund on receipt</div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="specs-title">Specifications</h3>
                    <div class="space-y-1">
                        @if($product->description)
                        <style>
                            .product-description-content h1 { font-size: 24px; font-weight: 800; margin-bottom: 12px; color: #111; }
                            .product-description-content h2 { font-size: 20px; font-weight: 700; margin-bottom: 10px; color: #222; }
                            .product-description-content h3 { font-size: 18px; font-weight: 700; margin-bottom: 8px; color: #333; }
                            .product-description-content p { margin-bottom: 10px; }
                            .product-description-content ul { list-style-type: disc; margin-left: 20px; margin-bottom: 15px; }
                            .product-description-content ol { list-style-type: decimal; margin-left: 20px; margin-bottom: 15px; }
                            .product-description-content strong { font-weight: 700; }
                        </style>
                        <div class="product-description-content text-[13px] text-gray-600 leading-relaxed mb-6">{!! $product->description !!}</div>
                        @endif

                        @if($product->color)
                        <div class="spec-item"><span class="spec-key">Color:</span> <span class="spec-value">{{ $product->color }}</span></div>
                        @endif
                        <div class="spec-item"><span class="spec-key">Category:</span> <span class="spec-value">{{ $product->category->name ?? 'N/A' }}</span></div>
                    </div>
                </div>

                @if(($siteSettings->is_size_chart_active ?? true) && ($product->is_size_chart_active ?? true) && !empty($product->size_chart) && !empty($product->size_chart['columns']))
                <div class="mt-12 border-t border-gray-100 pt-8" x-data="{ 
                    unit: 'inch',
                    rows: {{ json_encode($product->size_chart['rows']) }},
                    convert(val) {
                        if (!val || isNaN(val)) return val;
                        return this.unit === 'cm' ? (parseFloat(val) * 2.54).toFixed(1) : val;
                    }
                }">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-[14px] font-bold text-gray-900 uppercase" x-text="unit === 'inch' ? 'Size chart - In inches' : 'Size chart - In Centimeters'">Size chart - In inches</h4>
                        <div class="flex gap-2">
                            <button @click="unit = 'inch'" 
                                    :class="unit === 'inch' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-400 border-gray-200'"
                                    class="px-4 py-1.5 border text-[11px] font-bold rounded-sm transition-all">INCH</button>
                            <button @click="unit = 'cm'" 
                                    :class="unit === 'cm' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-400 border-gray-200'"
                                    class="px-4 py-1.5 border text-[11px] font-bold rounded-sm transition-all">CM</button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-center border-collapse text-[13px]">
                            <thead>
                                <tr class="bg-gray-50">
                                    @foreach($product->size_chart['columns'] as $column)
                                    <th class="border border-gray-200 p-3 font-bold text-gray-900 uppercase tracking-tight">{{ $column }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(row, rowIndex) in rows" :key="rowIndex">
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <template x-for="(cell, cellIndex) in row" :key="cellIndex">
                                            <td class="border border-gray-200 p-3 text-gray-600" x-text="cellIndex > 0 ? convert(cell) : cell"></td>
                                        </template>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                </div> {{-- End details-box-wrapper --}}
            </div> {{-- End right-column-container --}}
        </div> {{-- End grid --}}

        @if($relatedProducts->count() > 0)
        <div class="mt-24">
            <h2 class="text-lg font-bold text-gray-900 mb-10 border-b border-gray-100 pb-4 uppercase tracking-tight">You may also like</h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6 lg:gap-8">
                @foreach($relatedProducts as $related)
                @include('partials.product-card', ['product' => $related])
                @endforeach
            </div>
        </div>
        @endif
    </div> {{-- max-w-[1440px] --}}
</div> {{-- product-details-page --}}
@push('scripts')
<script>
    // Facebook ViewContent Tracking
    @if(isset($siteSettings) && $siteSettings->facebook_pixel_id)
        fbq('track', 'ViewContent', {
            content_name: '{{ $product->name }}',
            content_category: '{{ $product->category->name ?? "N/A" }}',
            content_ids: ['{{ $product->id }}'],
            content_type: 'product',
            value: {{ $product->effective_price }},
            currency: 'BDT'
        });
    @endif

    // TikTok ViewContent Tracking
    @if(isset($siteSettings) && $siteSettings->tiktok_pixel_id)
        ttq.track('ViewContent', {
            content_id: '{{ $product->id }}',
            content_type: 'product',
            content_name: '{{ $product->name }}',
            value: {{ $product->effective_price }},
            currency: 'BDT'
        });
    @endif
</script>
@endpush
@endsection
