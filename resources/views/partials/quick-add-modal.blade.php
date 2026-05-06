<div x-data="{ 
    open: false, 
    product: {},
    selectedSize: '',
    selectedColor: '',
    quantity: 1,
    loading: false,
    hasSizes: false,
    hasColors: false,
    sizeError: false,
    colorError: false,

    async openQuickAdd(productId) {
        document.body.style.overflow = 'hidden';
        this.open = true;
        this.loading = true;
        this.product = {};
        this.selectedSize = '';
        this.selectedColor = '';
        this.hasSizes = false;
        this.hasColors = false;
        this.sizeError = false;
        this.colorError = false;

        try {
            const response = await fetch(`/api/products/${productId}`);
            if (!response.ok) throw new Error('Product not found');
            const data = await response.json();
            this.product = data;
            
            this.hasSizes = this.product.sizes && this.product.sizes.length > 0;
            this.hasColors = this.product.colors && this.product.colors.length > 0;

            if (this.hasSizes && this.product.sizes.length === 1) this.selectedSize = this.product.sizes[0];
            if (this.hasColors && this.product.colors.length === 1) this.selectedColor = this.product.colors[0];

        } catch (error) {
            console.error('Quick Add Error:', error);
            if (window.showToast) showToast('Unable to load product details', 'error');
            this.closeModal();
        } finally {
            this.loading = false;
        }
    },

    closeModal() {
        this.open = false;
        document.body.style.overflow = 'auto';
        setTimeout(() => { this.product = {}; }, 400);
    },

    async handleAction(actionType) {
        let hasError = false;
        this.sizeError = false;
        this.colorError = false;

        if (this.hasSizes && !this.selectedSize) {
            this.sizeError = true;
            hasError = true;
        }
        if (this.hasColors && !this.selectedColor) {
            this.colorError = true;
            hasError = true;
        }

        if (hasError) {
            // Auto clear error after 2.5 seconds
            setTimeout(() => { this.sizeError = false; this.colorError = false; }, 2500);
            return;
        }

        this.loading = true;
        try {
            if(actionType === 'cart') {
                await smartAddToCart(this.product.id, this.quantity, this.selectedColor, this.selectedSize);
            } else {
                await smartBuyNow(this.product.id, this.quantity, this.selectedColor, this.selectedSize);
            }
            this.closeModal();
        } catch (err) {} finally { this.loading = false; }
    }
}"
@open-quick-add.window="openQuickAdd($event.detail.id)"
x-show="open"
x-cloak
class="fixed inset-0 flex items-center justify-center p-3"
style="display: none; z-index: 9999999;">

    <div x-show="open" 
         class="absolute inset-0"
         style="background-color: rgba(0,0,0,0.8); backdrop-filter: blur(8px);"
         @click="closeModal()"></div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="qa-modal-box relative">
        
        <!-- Loading State -->
        <div x-show="loading && !product.id" style="display: flex; justify-content: center; align-items: center; min-height: 200px;">
            <div style="width: 40px; height: 40px; border: 4px solid #eee; border-top-color: #000; border-radius: 50%; animation: spin 1s linear infinite;"></div>
        </div>

        <!-- Product Content -->
        <div x-show="product.id" class="qa-modal-content">
            
            <!-- Close Button -->
            <button @click="closeModal()" class="qa-close-btn">
                <svg style="width: 22px; height: 22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <!-- Left: Image -->
            <div class="qa-img-section" style="position: relative;">
                <img :src="product.thumbnail_url" class="qa-img">
                
                <!-- Free Delivery Badge -->
                <template x-if="product.free_shipping">
                    <div class="absolute" style="bottom: 12px; left: 12px; z-index: 20; pointer-events: none;">
                        <span style="background-color: #009848; color: #fff; font-size: 10px; font-weight: 800; padding: 4px 10px; border-radius: 2px; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.2); text-transform: uppercase;">
                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 011 1v2.5a1.5 1.5 0 01-3 0V17a1 1 0 011-1h2zm7-1a1 1 0 011 1v2.5a1.5 1.5 0 01-3 0V17a1 1 0 011-1h2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9h4l3 3v4h-7V9z"/></svg>
                            Free Delivery
                        </span>
                    </div>
                </template>

                <!-- Discount Badge -->
                <template x-if="Number(product.effective_price) < Number(product.price)">
                    <div class="absolute" style="top: 12px; left: 12px; z-index: 20; pointer-events: none;">
                        <span style="background-color: #ff3f6c; color: #fff; font-size: 13px; font-weight: 900; padding: 4px 10px; border-radius: 2px; box-shadow: 0 2px 4px rgba(0,0,0,0.2); text-transform: uppercase;">
                            <span x-text="'-' + Math.round((1 - (product.effective_price / product.price)) * 100) + '%'"></span>
                        </span>
                    </div>
                </template>
            </div>

            <!-- Right: Details -->
            <div class="qa-details-section">
                <div class="flex items-center gap-2 flex-wrap mb-2">
                    <h2 class="qa-product-name" style="margin: 0;" x-text="product.name"></h2>
                    <template x-if="product.sku">
                        <span style="background: #f1f5f9; color: #64748b; font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 4px; border: 1px solid #e2e8f0; text-transform: uppercase;">
                            Product Code: <span style="color: #FF6A00;" x-text="product.sku"></span>
                        </span>
                    </template>
                </div>
                
                <div class="qa-price-row">
                    <span class="qa-price-main" x-text="'৳' + Number(product.effective_price).toLocaleString()"></span>
                    <template x-if="Number(product.effective_price) < Number(product.price)">
                        <span class="qa-price-old" x-text="'৳' + Number(product.price).toLocaleString()"></span>
                    </template>
                </div>

                <!-- Size -->
                <template x-if="hasSizes">
                    <div class="qa-option-group" :class="sizeError ? 'qa-error-shake' : ''">
                        <p class="qa-option-label">
                            Size
                            <span x-show="selectedSize" class="qa-selected-val" x-text="selectedSize"></span>
                            <span x-show="sizeError" class="qa-error-text">← Please select</span>
                        </p>
                        <div class="qa-option-pills" :class="sizeError ? 'qa-pills-error' : ''">
                            <template x-for="s in product.sizes" :key="s">
                                <button type="button" @click="selectedSize = s; sizeError = false;" 
                                        class="qa-pill" :class="{'qa-pill-active': selectedSize === s, 'qa-pill-error': sizeError}"
                                        x-text="s"></button>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Color -->
                <template x-if="hasColors">
                    <div class="qa-option-group" :class="colorError ? 'qa-error-shake' : ''">
                        <p class="qa-option-label">
                            Color
                            <span x-show="selectedColor" class="qa-selected-val" style="text-transform:capitalize;" x-text="selectedColor"></span>
                            <span x-show="colorError" class="qa-error-text">← Please select</span>
                        </p>
                        <div class="qa-option-pills" :class="colorError ? 'qa-pills-error' : ''">
                            <template x-for="c in product.colors" :key="c">
                                <button type="button" @click="selectedColor = c; colorError = false;" 
                                        class="qa-pill qa-pill-color" 
                                        :class="{'qa-pill-active-color': selectedColor === c && c.startsWith('#'), 'qa-pill-active': selectedColor === c && !c.startsWith('#'), 'qa-pill-error': colorError}"
                                        :style="c.startsWith('#') ? 'background-color: ' + c + '; width: 32px; height: 32px; border-radius: 50%; padding: 0; min-width: 32px;' : ''">
                                    
                                    <template x-if="!c.startsWith('#')">
                                        <span x-text="c"></span>
                                    </template>

                                    <template x-if="c.startsWith('#')">
                                        <div x-show="selectedColor === c" class="flex items-center justify-center w-full h-full bg-black/10">
                                            <svg style="width: 14px; height: 14px; color: #fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </template>
                                </button>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Action Buttons -->
                <div class="qa-actions">
                    <button @click="handleAction('buy')" class="qa-btn qa-btn-buy">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Buy Now
                    </button>
                    <button @click="handleAction('cart')" class="qa-btn qa-btn-cart">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin { 100% { transform: rotate(360deg); } }
    [x-cloak] { display: none !important; }

    /* Error shake animation */
    @keyframes qaShake {
        0%, 100% { transform: translateX(0); }
        15%, 45%, 75% { transform: translateX(-4px); }
        30%, 60%, 90% { transform: translateX(4px); }
    }
    .qa-error-shake { animation: qaShake 0.5s ease; }

    .qa-error-text {
        color: #e53e3e;
        font-size: 10px;
        font-weight: 700;
        margin-left: 6px;
        animation: qaShake 0.5s ease;
    }

    .qa-pill-error {
        border-color: #e53e3e !important;
        background-color: #fff5f5 !important;
    }

    .qa-modal-box {
        width: 100%;
        max-width: 780px;
        background-color: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 0 60px rgba(0,0,0,0.5);
    }

    .qa-modal-content {
        display: flex;
        flex-direction: row;
        position: relative;
    }

    .qa-close-btn {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 10;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.9);
        border: none;
        border-radius: 50%;
        cursor: pointer;
        color: #000;
        backdrop-filter: blur(4px);
        transition: all 0.2s;
    }
    .qa-close-btn:hover { background: #000; color: #fff; }

    .qa-img-section {
        width: 45%;
        flex-shrink: 0;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .qa-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .qa-details-section {
        flex: 1;
        padding: 28px 28px 24px 28px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .qa-product-name {
        font-size: 17px;
        font-weight: 800;
        color: #000;
        line-height: 1.3;
        margin: 0 0 10px 0;
        padding-right: 30px;
    }

    .qa-price-row {
        display: flex;
        align-items: baseline;
        gap: 10px;
        margin-bottom: 18px;
    }
    .qa-price-main { font-size: 22px; font-weight: 900; color: #000; }
    .qa-price-old { font-size: 13px; color: #aaa; text-decoration: line-through; font-weight: 600; }

    .qa-option-group { margin-bottom: 14px; }
    .qa-option-label {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        color: #555;
        letter-spacing: 0.8px;
        margin: 0 0 8px 0;
    }
    .qa-selected-val {
        color: #45b86f;
        font-weight: 900;
        margin-left: 4px;
    }

    .qa-option-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .qa-pill {
        min-width: 44px;
        height: 36px;
        border: 2px solid #ddd;
        background: #fff;
        color: #000;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 10px;
    }
    .qa-pill-color { border-radius: 18px; text-transform: capitalize; }
    .qa-pill:hover { border-color: #000; }
    .qa-pill-active { background: #000 !important; color: #fff !important; border-color: #000 !important; }
    .qa-pill-active-color { 
        ring: 2px solid #000;
        box-shadow: 0 0 0 2px #fff, 0 0 0 4px #000;
        transform: scale(1.1);
    }

    .qa-actions {
        display: flex;
        gap: 8px;
        margin-top: 6px;
    }
    .qa-btn {
        flex: 1;
        height: 46px;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.15s;
    }
    .qa-btn:active { transform: scale(0.97); }
    .qa-btn-buy { background: #45b86f; color: #fff; }
    .qa-btn-buy:hover { background: #3ca361; }
    .qa-btn-cart { background: #000; color: #fff; }
    .qa-btn-cart:hover { background: #222; }

    /* Mobile: Stack vertically with full image */
    @media (max-width: 640px) {
        .qa-modal-box {
            max-width: 100%;
            border-radius: 14px;
        }
        .qa-modal-content {
            flex-direction: column;
        }
        .qa-img-section {
            width: 100%;
            aspect-ratio: 1 / 1;
            min-height: auto;
        }
        .qa-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #f5f5f5;
        }
        .qa-details-section {
            padding: 14px 16px 18px 16px;
        }
        .qa-product-name { font-size: 14px; margin-bottom: 4px; padding-right: 0; }
        .qa-price-row { margin-bottom: 10px; }
        .qa-price-main { font-size: 19px; }
        .qa-option-group { margin-bottom: 10px; }
        .qa-option-label { font-size: 10px; margin-bottom: 6px; }
        .qa-pill { min-width: 38px; height: 30px; font-size: 11px; padding: 0 8px; }
        .qa-btn { height: 40px; font-size: 11px; }
        .qa-actions { margin-top: 4px; }
        .qa-close-btn { top: 8px; right: 8px; width: 32px; height: 32px; }
        .qa-close-btn svg { width: 18px; height: 18px; }
    }
</style>
