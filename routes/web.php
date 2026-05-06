<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// ============================
// PUBLIC ROUTES
// ============================

Route::get('/set-locale/{lang}', [\App\Http\Controllers\LocaleController::class, 'setLocale'])->name('set-locale');

Route::get('/', [HomeController::class, 'index'])->name('home');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/api/products/{id}', [ProductController::class, 'getDetails']);
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories', [ProductController::class, 'categories'])->name('categories.index');
Route::post('/wishlist/toggle/{productId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::get('/category/{category:slug}', [ProductController::class, 'category'])->name('category.show');
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::get('/flash-sale', [ProductController::class, 'flashSales'])->name('products.flash-sales');

// Search
Route::get('/search/live', [SearchController::class, 'live'])->name('search.live');

// Cart (session-based, no auth required)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/promo', [CartController::class, 'applyPromo'])->name('cart.promo');
// AJAX
Route::post('/apply-coupon', [CartController::class, 'applyCouponAjax'])->name('coupon.apply');
Route::post('/remove-coupon', [CartController::class, 'removeCouponAjax'])->name('coupon.remove');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::get('/cart/details', [CartController::class, 'details'])->name('cart.details');

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Static Pages
Route::get('/track-order', [PagesController::class, 'trackOrder'])->name('pages.track-order');
Route::get('/return-policy', [PagesController::class, 'returnPolicy'])->name('pages.return-policy');
Route::get('/privacy-policy', [PagesController::class, 'privacyPolicy'])->name('pages.privacy-policy');
Route::get('/terms-of-service', [PagesController::class, 'termsOfService'])->name('pages.terms-of-service');
Route::get('/about-us', [PagesController::class, 'aboutUs'])->name('pages.about-us');
Route::get('/faq', [PagesController::class, 'faq'])->name('pages.faq');
Route::get('/contact-us', [PagesController::class, 'contactUs'])->name('pages.contact-us');
Route::post('/newsletter', [PagesController::class, 'newsletterSub'])->name('newsletter.subscribe');

// ============================
// AUTH ROUTES (Password Based)
// ============================
Route::get('/login', [AuthController::class, 'loginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');
Route::get('/register', [AuthController::class, 'registerForm'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit')->middleware('guest');
Route::get('/register/verify', [AuthController::class, 'verifyRegisterForm'])->name('register.verify')->middleware('guest');
Route::post('/register/verify', [AuthController::class, 'verifyRegister'])->middleware('guest');
Route::post('/register/resend', [AuthController::class, 'resendRegisterOtp'])->name('register.resend')->middleware('guest');

// Forgot Password
Route::get('/forgot-password', [AuthController::class, 'forgotPasswordForm'])->name('password.request')->middleware('guest');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email')->middleware('guest');
Route::get('/forgot-password/verify', [AuthController::class, 'verifyResetForm'])->name('password.verify')->middleware('guest');
Route::post('/forgot-password/verify', [AuthController::class, 'verifyReset'])->middleware('guest');
Route::get('/reset-password-phone', [AuthController::class, 'resetPasswordPhoneForm'])->name('password.reset.phone')->middleware('guest');
Route::post('/reset-password-phone', [AuthController::class, 'resetPasswordPhone'])->name('password.update.phone')->middleware('guest');
Route::get('/reset-password/{token}', [AuthController::class, 'resetPasswordForm'])->name('password.reset')->middleware('guest');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update')->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ============================
// CHECKOUT ROUTES (Public - Optional Login)
// ============================
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::post('/checkout/capture', [CheckoutController::class, 'captureLead'])->name('checkout.capture');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// ============================
// AUTHENTICATED ROUTES
// ============================
Route::middleware('auth')->group(function() {
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Reviews
    Route::post('/products/{productId}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Account
    Route::get('/account', function() {
        $user = auth()->user();
        $orders = \App\Models\Order::where('user_id', auth()->id())->latest()->take(5)->get();
        $wishlistCount = \App\Models\Wishlist::where('user_id', auth()->id())->count();
        return view('account.dashboard', compact('user', 'orders', 'wishlistCount'));
    })->name('account.dashboard');

    Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/{message}/read', [\App\Http\Controllers\MessageController::class, 'markAsRead'])->name('messages.read');
    Route::get('/dashboard', [\App\Http\Controllers\AuthController::class, 'dashboard'])->name('account.dashboard');
});

Route::prefix('199/admin-smartlookbd-access')->name('admin.')->group(function() {
    // Admin Auth
    Route::get('/', function() {
        return redirect()->route('admin.loginform');
    });
    Route::get('/login', [\App\Http\Controllers\AdminAuthController::class, 'loginForm'])->name('loginform');
    Route::post('/login', [\App\Http\Controllers\AdminAuthController::class, 'login'])->name('login');
    Route::post('/logout', [\App\Http\Controllers\AdminAuthController::class, 'logout'])->name('logout');

    // Admin Password Reset
    Route::get('/forgot-password', [\App\Http\Controllers\AdminAuthController::class, 'forgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\AdminAuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/forgot-password/verify', [\App\Http\Controllers\AdminAuthController::class, 'verifyResetForm'])->name('password.verify');
    Route::post('/forgot-password/verify', [\App\Http\Controllers\AdminAuthController::class, 'verifyReset']);
    Route::get('/reset-password', [\App\Http\Controllers\AdminAuthController::class, 'resetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\AdminAuthController::class, 'resetPassword'])->name('password.update');
    
    // Protected Admin Routes
    Route::middleware(['admin'])->group(function() {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index']);

        Route::resource('/products', \App\Http\Controllers\Admin\ProductController::class);
        Route::post('/products-bulk-delete', [\App\Http\Controllers\Admin\ProductController::class, 'bulkDestroy'])->name('products.bulk-delete');
        Route::delete('/products/images/{productImage}', [\App\Http\Controllers\Admin\ProductController::class, 'destroyImage'])->name('products.images.destroy');
        Route::resource('/categories', \App\Http\Controllers\Admin\CategoryController::class);
        Route::resource('/orders', \App\Http\Controllers\Admin\OrderController::class);
        Route::patch('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'update'])->name('orders.status');
        Route::patch('/orders/{order}/payment-status', [\App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
        Route::post('/orders/{order}/send-to-courier', [\App\Http\Controllers\Admin\OrderController::class, 'sendToCourier'])->name('orders.send-to-courier');
        Route::patch('/order-items/{item}/update', [\App\Http\Controllers\Admin\OrderController::class, 'updateItem'])->name('order-items.update');
        Route::delete('/order-items/{item}/remove', [\App\Http\Controllers\Admin\OrderController::class, 'removeItem'])->name('order-items.remove');
        Route::resource('/promo-codes', \App\Http\Controllers\Admin\PromoCodeController::class);
        Route::resource('/banners', \App\Http\Controllers\Admin\BannerController::class);
        Route::resource('/middle-banners', \App\Http\Controllers\Admin\MiddleBannerController::class);

        // Customers list
        Route::get('/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');
        Route::post('/customers/{customer}/message', [\App\Http\Controllers\Admin\CustomerController::class, 'sendMessage'])->name('customers.message');
        Route::delete('/customers/message/{message}', [\App\Http\Controllers\Admin\CustomerController::class, 'deleteMessage'])->name('customers.message.destroy');
        Route::delete('/customers/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'destroy'])->name('customers.destroy');

        Route::get('/reviews/{review}/delete', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.delete');
        Route::resource('/reviews', \App\Http\Controllers\Admin\ReviewController::class)->except(['create', 'show']);

        // Real-time ping
        Route::get('/stats-ping', [\App\Http\Controllers\Admin\DashboardController::class, 'ping'])->name('stats-ping');

        // Site Settings
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

        // Delivery Charges
        Route::get('/delivery-charges', [\App\Http\Controllers\Admin\SettingController::class, 'deliveryIndex'])->name('delivery-charges.index');
        Route::post('/delivery-charges', [\App\Http\Controllers\Admin\SettingController::class, 'deliveryUpdate'])->name('delivery-charges.update');

        // Facebook Pixel & CAPI
        Route::get('/facebook-pixel', [\App\Http\Controllers\Admin\SettingController::class, 'facebookPixelIndex'])->name('facebook-pixel.index');
        Route::post('/facebook-pixel', [\App\Http\Controllers\Admin\SettingController::class, 'facebookPixelUpdate'])->name('facebook-pixel.update');

        // TikTok Pixel
        Route::get('/tiktok-pixel', [\App\Http\Controllers\Admin\SettingController::class, 'tiktokPixelIndex'])->name('tiktok-pixel.index');
        Route::post('/tiktok-pixel', [\App\Http\Controllers\Admin\SettingController::class, 'tiktokPixelUpdate'])->name('tiktok-pixel.update');

        // Show Pop Up
        Route::get('/show-popup', [\App\Http\Controllers\Admin\SettingController::class, 'popupIndex'])->name('popup.index');
        Route::post('/show-popup', [\App\Http\Controllers\Admin\SettingController::class, 'popupUpdate'])->name('popup.update');

        // Global SEO Settings
        Route::get('/settings/seo', [\App\Http\Controllers\Admin\SettingController::class, 'seoIndex'])->name('settings.seo.index');
        Route::put('/settings/seo', [\App\Http\Controllers\Admin\SettingController::class, 'seoUpdate'])->name('settings.seo.update');

        // Announcement Bar
        Route::get('/settings/announcement', [\App\Http\Controllers\Admin\SettingController::class, 'announcementIndex'])->name('settings.announcement.index');
        Route::put('/settings/announcement', [\App\Http\Controllers\Admin\SettingController::class, 'announcementUpdate'])->name('settings.announcement.update');

        // Product Image Slider
        Route::get('/settings/slider', [\App\Http\Controllers\Admin\SettingController::class, 'sliderIndex'])->name('settings.slider.index');
        Route::put('/settings/slider', [\App\Http\Controllers\Admin\SettingController::class, 'sliderUpdate'])->name('settings.slider.update');

        // Model Notification
        Route::get('/settings/model-notification', [\App\Http\Controllers\Admin\SettingController::class, 'modelNotificationIndex'])->name('settings.model-notification.index');
        Route::put('/settings/model-notification', [\App\Http\Controllers\Admin\SettingController::class, 'modelNotificationUpdate'])->name('settings.model-notification.update');

        // Communication Gateway Settings
        Route::get('/settings/sms', [\App\Http\Controllers\Admin\SettingController::class, 'smsIndex'])->name('settings.sms.index');
        Route::put('/settings/sms', [\App\Http\Controllers\Admin\SettingController::class, 'smsUpdate'])->name('settings.sms.update');
        Route::post('/settings/test-smtp', [\App\Http\Controllers\Admin\SettingController::class, 'testSmtp'])->name('settings.test-smtp');
        Route::post('/settings/test-sms', [\App\Http\Controllers\Admin\SettingController::class, 'testSms'])->name('settings.test-sms');

        // Courier Settings
        Route::get('/settings/courier', [\App\Http\Controllers\Admin\SettingController::class, 'courierIndex'])->name('settings.courier.index');
        Route::put('/settings/courier', [\App\Http\Controllers\Admin\SettingController::class, 'courierUpdate'])->name('settings.courier.update');

        // Payment Gateway Settings
        Route::get('/settings/payment', [\App\Http\Controllers\Admin\SettingController::class, 'paymentIndex'])->name('settings.payment.index');
        Route::put('/settings/payment', [\App\Http\Controllers\Admin\SettingController::class, 'paymentUpdate'])->name('settings.payment.update');

        // Sales Report
        Route::get('/sales', [\App\Http\Controllers\Admin\SalesController::class, 'index'])->name('sales.index');

        // Advanced Statistics
        Route::get('/advanced', [\App\Http\Controllers\Admin\AdvancedController::class, 'index'])->name('advanced.index');

        // POS System
        Route::get('/pos', [\App\Http\Controllers\Admin\POSController::class, 'index'])->name('pos.index');
        Route::get('/pos/products', [\App\Http\Controllers\Admin\POSController::class, 'searchProducts'])->name('pos.products');
        Route::post('/pos/order', [\App\Http\Controllers\Admin\POSController::class, 'store'])->name('pos.store');
        // IP Blocking
        Route::resource('/ip-blocks', \App\Http\Controllers\Admin\IpBlockController::class)->only(['index', 'store', 'destroy']);
        // Admin Profile
        Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

        Route::resource('/delivery-areas', \App\Http\Controllers\Admin\DeliveryAreaController::class)->only(['index', 'store', 'update', 'destroy']);

        // Instruction Guide
        Route::get('/guide', function() {
            return view('admin.guide');
        })->name('guide');
    });
});
