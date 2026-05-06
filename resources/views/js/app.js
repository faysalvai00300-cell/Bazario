import './bootstrap';
import Alpine from 'alpinejs';
import Swiper from 'swiper/bundle';
import AOS from 'aos';

// Import Swiper styles
import 'swiper/css/bundle';

// Initialize Alpine.js
window.Alpine = Alpine;

// Initialize AOS (Animate On Scroll)
document.addEventListener('DOMContentLoaded', function() {
    AOS.init({
        duration: 700,
        easing: 'ease-in-out',
        once: true,
        offset: 80,
    });
});

// Make Swiper globally available
window.Swiper = Swiper;

// Cart count update from session
window.updateCartBadge = function(count) {
    const badges = ['cart-count-badge', 'mobile-cart-count'];
    badges.forEach(id => {
        const badge = document.getElementById(id);
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'flex' : 'none';
            // Also handle tailwind 'hidden' class if present
            if (count > 0) badge.classList.remove('hidden');
            else badge.classList.add('hidden');
        }
    });
};

// Wishlist toggle
window.toggleWishlist = function(productId) {
    fetch(`/wishlist/toggle/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(async r => {
        if (r.status === 401) {
            window.dispatchEvent(new CustomEvent('open-auth-modal'));
            return null;
        }
        const data = await r.json();
        return data;
    })
    .then(data => {
        if (!data) return;
        showToast(data.message, data.in_wishlist ? 'success' : 'info');
        const heart = document.querySelectorAll(`#wishlist-btn-${productId}`);
        heart.forEach(btn => {
            btn.classList.toggle('text-red-500', data.in_wishlist);
            
            const svg = btn.querySelector('svg');
            if (svg) {
                // If it's the detail page button (uses stroke/fill)
                if (btn.classList.contains('p-2.5')) {
                    svg.setAttribute('fill', data.in_wishlist ? 'currentColor' : 'none');
                }
            }
        });
    });
};

// Add to cart AJAX
if (!window.addToCart) {
    window.addToCart = function(productId, quantity = 1, color = '', size = '') {
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
                if (typeof window.refreshCartBadges === 'function') window.refreshCartBadges(data.cart_count);
                if (window.Alpine && Alpine.store('cart')) Alpine.store('cart').update(data.cart_count);
                
                // Update side cart state immediately
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
                
                // Open side cart
                if (typeof window.toggleSideCart === 'function') {
                    window.toggleSideCart(true);
                }
            } else {
                if (typeof window.showToast === 'function') window.showToast(data.message || 'Error adding to cart', 'error');
            }
            if (btn) {
                btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-10H5.4"/></svg> Add to Cart';
                btn.disabled = false;
            }
        })
        .catch(() => {
            if (typeof window.showToast === 'function') window.showToast('Failed to add to cart', 'error');
            if (btn) {
                btn.innerHTML = 'Add to Cart';
                btn.disabled = false;
            }
        });
    };
}

// Buy Now AJAX
if (!window.buyNow) {
    window.buyNow = function(productId, quantity = 1, color = '', size = '') {
        const qty = parseInt(quantity) || 1;
        const clr = String(color || '');
        const sz = String(size || '');
        
        const btn = document.querySelector(`#buy-now-btn-${productId}`);
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
                // Meta Pixel Tracking for BuyNow (which is an AddToCart + Redirect)
                if (typeof fbq === 'function') {
                    fbq('track', 'AddToCart', {
                        content_ids: [productId],
                        content_type: 'product',
                        value: data.item_price || 0,
                        currency: 'BDT'
                    });
                }
                window.location.href = '/checkout';
            } else {
                if (typeof window.showToast === 'function') window.showToast(data.message || 'Error processing request', 'error');
                if (btn) {
                    btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>Buy Now';
                    btn.disabled = false;
                }
            }
        })
        .catch(() => {
            if (typeof window.showToast === 'function') window.showToast('Failed to process request', 'error');
            if (btn) {
                btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>Buy Now';
                btn.disabled = false;
            }
        });
    };
}

// Toast notification system
window.showToast = function(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500',
        warning: 'bg-yellow-500'
    };

    const toast = document.createElement('div');
    toast.className = `toast flex items-center gap-3 px-5 py-3 rounded-lg shadow-xl text-white text-sm font-medium mb-2 ${colors[type] || colors.success}`;
    toast.innerHTML = `<span>${message}</span><button onclick="this.parentElement.remove()" class="ml-auto opacity-70 hover:opacity-100">✕</button>`;
    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
};

// Countdown Timer Alpine component
document.addEventListener('alpine:init', () => {
    Alpine.store('auth', {
        open: false
    });

    Alpine.data('countdown', (endTime) => ({
        days: '00',
        hours: '00',
        minutes: '00',
        seconds: '00',
        init() {
            this.update();
            setInterval(() => this.update(), 1000);
        },
        update() {
            const now = new Date().getTime();
            const end = new Date(endTime).getTime();
            const diff = end - now;

            if (diff <= 0) {
                this.days = this.hours = this.minutes = this.seconds = '00';
                return;
            }

            this.days = String(Math.floor(diff / (1000 * 60 * 60 * 24))).padStart(2, '0');
            this.hours = String(Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
            this.minutes = String(Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
            this.seconds = String(Math.floor((diff % (1000 * 60)) / 1000)).padStart(2, '0');
        }
    }));

    Alpine.data('searchbar', () => ({
        query: '',
        results: [],
        loading: false,
        showResults: false,
        search() {
            if (this.query.length < 2) {
                this.results = [];
                this.showResults = false;
                return;
            }
            this.loading = true;
            fetch(`/search/live?q=${encodeURIComponent(this.query)}`)
                .then(r => r.json())
                .then(data => {
                    this.results = data;
                    this.showResults = true;
                    this.loading = false;
                })
                .catch(() => { this.loading = false; });
        }
    }));

    Alpine.data('mobileNav', () => ({
        open: false,
        showHeader: true,
        lastScroll: 0,
        handleScroll() {
            let currentScroll = window.pageYOffset;
            if (window.innerWidth < 768) {
                // If scrolled past 60px, hide it. Only show when back at the very top.
                if (currentScroll > 60) {
                    this.showHeader = false;
                } else {
                    this.showHeader = true;
                }
            } else {
                this.showHeader = true;
            }
            this.lastScroll = currentScroll <= 0 ? 0 : currentScroll;
        },
        toggle() { this.open = !this.open; },
        close() { this.open = false; }
    }));

    Alpine.data('cartQuantity', (initial = 1, max = 100) => ({
        qty: initial,
        max: max,
        increment() { if (this.qty < this.max) this.qty++; },
        decrement() { if (this.qty > 1) this.qty--; }
    }));
});

Alpine.start();
