<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('settings')) {
            $settings = \Illuminate\Support\Facades\Cache::remember('site_settings', 60, function () {
                return Setting::first() ?? new Setting();
            });
            View::share('siteSettings', $settings);

            // Override SMTP settings if enabled
            if ($settings->is_smtp_active && !empty($settings->smtp_host)) {
                config([
                    'mail.default' => 'smtp',
                    'mail.mailers.smtp.host' => $settings->smtp_host,
                    'mail.mailers.smtp.port' => $settings->smtp_port,
                    'mail.mailers.smtp.encryption' => $settings->smtp_encryption,
                    'mail.mailers.smtp.username' => $settings->smtp_username,
                    'mail.mailers.smtp.password' => $settings->smtp_password,
                    'mail.from.address' => $settings->smtp_from_address,
                    'mail.from.name' => $settings->site_name ?? config('app.name'),
                ]);
            }

            $categories = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->get();
            View::share('categories', $categories);

            $newArrivals = \App\Models\Product::where('is_active', true)
                ->where('is_new', true)
                ->latest()
                ->take(4)
                ->get();
            View::share('newArrivals', $newArrivals);

            View::composer('*', function ($view) {
                if (auth()->check()) {
                    $count = \App\Models\Message::where('user_id', auth()->id())
                        ->where('is_read', false)
                        ->count();
                    $view->with('unreadMessagesCount', $count);
                } else {
                    $view->with('unreadMessagesCount', 0);
                }
            });
        }
    }
}
