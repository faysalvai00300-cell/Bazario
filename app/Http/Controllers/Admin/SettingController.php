<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_address' => 'nullable|string',
            'facebook_page_link' => 'nullable|url|max:255',
            'twitter_link' => 'nullable|url|max:255',
            'instagram_link' => 'nullable|url|max:255',
            'tiktok_link' => 'nullable|url|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'bkash_number' => 'nullable|string|max:20',
            'nagad_number' => 'nullable|string|max:20',
        ]);

        $data = $request->all();

        $settings = Setting::first();
        if (!$settings) {
            Setting::create($data);
        } else {
            $settings->update($data);
        }

        // Clear Cache to reflect changes immediately
        Cache::forget('site_settings');

        return back()->with('success', 'Site settings updated successfully.');
    }

    public function deliveryIndex()
    {
        $settings = Setting::first();
        $deliveryAreas = \App\Models\DeliveryArea::orderBy('sort_order', 'asc')->get();
        return view('admin.settings.delivery', compact('settings', 'deliveryAreas'));
    }

    public function deliveryUpdate(Request $request)
    {
        $request->validate([
            'delivery_charge' => 'required|numeric|min:0',
            'delivery_charge_inside' => 'required|numeric|min:0',
            'delivery_charge_outside' => 'required|numeric|min:0',
            'free_delivery_threshold' => 'required|numeric|min:0',
            'is_free_delivery_active' => 'nullable|boolean',
        ]);

        $settings = Setting::first() ?: new Setting();
        
        $settings->delivery_charge = $request->delivery_charge;
        $settings->delivery_charge_inside = $request->delivery_charge_inside;
        $settings->delivery_charge_outside = $request->delivery_charge_outside;
        $settings->free_delivery_threshold = $request->free_delivery_threshold;
        $settings->is_free_delivery_active = $request->has('is_free_delivery_active');
        
        $settings->save();

        Cache::forget('site_settings');
        return back()->with('success', 'Delivery charges updated successfully.');
    }

    public function facebookPixelIndex()
    {
        $settings = Setting::first();
        return view('admin.settings.facebook-pixel', compact('settings'));
    }

    public function facebookPixelUpdate(Request $request)
    {
        $request->validate([
            'facebook_pixel_id' => 'nullable|string|max:50',
            'facebook_access_token' => 'nullable|string',
        ]);

        $data = $request->only(['facebook_pixel_id', 'facebook_access_token']);

        $settings = Setting::first();
        if (!$settings) {
            Setting::create($data);
        } else {
            $settings->update($data);
        }

        Cache::forget('site_settings');
        return back()->with('success', 'Facebook Pixel & CAPI settings updated successfully.');
    }

    public function tiktokPixelIndex()
    {
        $settings = Setting::first();
        return view('admin.settings.tiktok-pixel', compact('settings'));
    }

    public function tiktokPixelUpdate(Request $request)
    {
        $request->validate([
            'tiktok_pixel_id' => 'nullable|string|max:50',
            'tiktok_access_token' => 'nullable|string',
        ]);

        $data = $request->only(['tiktok_pixel_id', 'tiktok_access_token']);

        $settings = Setting::first();
        if (!$settings) {
            Setting::create($data);
        } else {
            $settings->update($data);
        }

        Cache::forget('site_settings');
        return back()->with('success', 'TikTok Pixel & Events API settings updated successfully.');
    }

    public function popupIndex()
    {
        $settings = Setting::first();
        return view('admin.settings.popup', compact('settings'));
    }

    public function popupUpdate(Request $request)
    {
        $request->validate([
            'is_popup_active' => 'nullable|boolean',
            'popup_image' => 'nullable|image|max:10240',
            'popup_link' => 'nullable|string|max:255',
        ]);

        $settings = Setting::first() ?? new Setting();
        
        $settings->is_popup_active = $request->has('is_popup_active');
        $settings->popup_link = $request->popup_link;

        if ($request->hasFile('popup_image')) {
            if ($settings->popup_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($settings->popup_image);
            }
            $settings->popup_image = $request->file('popup_image')->store('popups', 'public');
        }

        $settings->save();
        
        Cache::forget('site_settings');
        return back()->with('success', 'Show Pop Up settings updated successfully.');
    }

    public function seoIndex()
    {
        $settings = Setting::first();
        return view('admin.settings.seo', compact('settings'));
    }

    public function seoUpdate(Request $request)
    {
        $request->validate([
            'seo_meta_title' => 'nullable|string|max:255',
            'seo_meta_description' => 'nullable|string',
            'seo_meta_keywords' => 'nullable|string',
            'custom_html_tags' => 'nullable|string',
        ]);

        $data = $request->only(['seo_meta_title', 'seo_meta_description', 'seo_meta_keywords', 'custom_html_tags']);

        $settings = Setting::first();
        if (!$settings) {
            Setting::create($data);
        } else {
            $settings->update($data);
        }

        Cache::forget('site_settings');
        return back()->with('success', 'Global SEO Settings updated successfully.');
    }

    public function announcementIndex()
    {
        $settings = Setting::first();
        return view('admin.settings.announcement', compact('settings'));
    }

    public function announcementUpdate(Request $request)
    {
        $request->validate([
            'announcement_text' => 'nullable|string|max:500',
            'announcement_speed' => 'nullable|integer|min:5|max:120',
            'model_notification' => 'nullable|string|max:1000',
        ]);

        $data = $request->only(['announcement_text', 'announcement_speed', 'model_notification']);

        $settings = Setting::first();
        if (!$settings) {
            Setting::create($data);
        } else {
            $settings->update($data);
        }

        Cache::forget('site_settings');
        return back()->with('success', 'Announcement Bar settings updated successfully.');
    }

    public function sliderIndex()
    {
        $settings = Setting::first();
        return view('admin.settings.slider', compact('settings'));
    }

    public function sliderUpdate(Request $request)
    {
        $request->validate([
            'is_product_slider_active' => 'nullable|boolean',
            'product_slider_interval' => 'required|integer|min:1000|max:60000',
        ]);

        $data = $request->only(['product_slider_interval']);
        $data['is_product_slider_active'] = $request->has('is_product_slider_active');

        $settings = Setting::first();
        if (!$settings) {
            Setting::create($data);
        } else {
            $settings->update($data);
        }

        Cache::forget('site_settings');
        return back()->with('success', 'Product Image Slider settings updated successfully.');
    }

    public function modelNotificationIndex()
    {
        $settings = Setting::first();
        return view('admin.settings.model-notification', compact('settings'));
    }

    public function modelNotificationUpdate(Request $request)
    {
        $request->validate([
            'model_notification' => 'nullable|string|max:5000',
            'notification_bg_color' => 'nullable|string|max:20',
            'notification_text_color' => 'nullable|string|max:20',
            'notification_text_size' => 'nullable|string|max:20',
            'notification_effect' => 'nullable|string|max:20',
            'notification_animation_speed' => 'nullable|string|max:20',
        ]);

        $data = $request->only([
            'model_notification', 
            'notification_bg_color', 
            'notification_text_color', 
            'notification_text_size', 
            'notification_effect', 
            'notification_animation_speed'
        ]);

        // Ensure non-nullable columns have values
        $data['notification_bg_color'] = $data['notification_bg_color'] ?? '#1e1e2d';
        $data['notification_text_color'] = $data['notification_text_color'] ?? '#ffffff';
        $data['notification_text_size'] = $data['notification_text_size'] ?? '15';
        $data['notification_effect'] = $data['notification_effect'] ?? 'none';
        $data['notification_animation_speed'] = $data['notification_animation_speed'] ?? '4';

        $settings = Setting::first();
        if (!$settings) {
            Setting::create($data);
        } else {
            $settings->update($data);
        }

        Cache::forget('site_settings');
        return back()->with('success', 'Model Notification settings updated successfully.');
    }

    public function smsIndex()
    {
        $settings = Setting::first();
        return view('admin.settings.sms', compact('settings'));
    }

    public function smsUpdate(Request $request)
    {
        $request->validate([
            'is_sms_active' => 'nullable|boolean',
            'sms_api_url' => 'nullable|string',
            'sms_username' => 'nullable|string',
            'sms_api_key' => 'nullable|string',
            'sms_sender_id' => 'nullable|string',
            'is_smtp_active' => 'nullable|boolean',
            'smtp_host' => 'nullable|string',
            'smtp_port' => 'nullable|string',
            'smtp_username' => 'nullable|string',
            'smtp_password' => 'nullable|string',
            'smtp_encryption' => 'nullable|string',
            'smtp_from_address' => 'nullable|string',
        ]);

        $data = $request->only([
            'sms_api_url', 'sms_username', 'sms_api_key', 'sms_sender_id',
            'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption', 'smtp_from_address'
        ]);
        $data['is_sms_active'] = $request->boolean('is_sms_active');
        $data['is_smtp_active'] = $request->boolean('is_smtp_active');

        $settings = Setting::first();
        if (!$settings) {
            Setting::create($data);
        } else {
            $settings->update($data);
        }

        Cache::forget('site_settings');
        return back()->with('success', 'Communication Gateway settings updated successfully.');
    }
    public function testSmtp(Request $request)
    {
        $settings = Setting::first();
        if (!$settings || !$settings->is_smtp_active || empty($settings->smtp_host)) {
            return back()->with('error', 'SMTP is not active or not configured.');
        }

        try {
            \Illuminate\Support\Facades\Mail::raw('This is a test email to verify SMTP configuration.', function ($message) use ($settings) {
                $message->to($settings->smtp_from_address ?? 'test@example.com')
                        ->subject('SMTP Test - ' . ($settings->site_name ?? 'SmartLookBD'));
            });
            return back()->with('success', 'Test email sent successfully to ' . ($settings->smtp_from_address ?? 'your sender email') . '. Check your inbox/spam.');
        } catch (\Exception $e) {
            return back()->with('error', 'SMTP Test Failed: ' . $e->getMessage());
        }
    }

    public function testSms(Request $request)
    {
        $settings = Setting::first();
        if (!$settings || !$settings->is_sms_active || empty($settings->sms_api_url)) {
            return back()->with('error', 'SMS Gateway is not active or API URL is missing.');
        }

        $testPhone = $request->input('test_phone', $settings->contact_phone ?? '');
        if (empty($testPhone)) {
            return back()->with('error', 'Please provide a phone number to test.');
        }

        $otp = "123456";
        $smsMessage = "Your SmartLookBD test verification code is: {$otp}";
        
        $apiUrl = str_replace(
            ['[USER]', '[TO]', '[MESSAGE]', '[KEY]', '[SENDER]'],
            [urlencode($settings->sms_username ?? ''), urlencode($testPhone), urlencode($smsMessage), urlencode($settings->sms_api_key ?? ''), urlencode($settings->sms_sender_id ?? '')],
            $settings->sms_api_url
        );

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(15)
                ->withoutVerifying()
                ->get($apiUrl);

            if ($response->successful()) {
                return back()->with('success', 'Test SMS request sent! Response: ' . substr($response->body(), 0, 100));
            } else {
                return back()->with('error', 'SMS Test Failed (HTTP ' . $response->status() . '): ' . $response->body());
            }
        } catch (\Exception $e) {
            return back()->with('error', 'SMS Test Exception: ' . $e->getMessage());
        }
    }

    public function courierIndex()
    {
        $settings = Setting::first();
        return view('admin.settings.courier', compact('settings'));
    }

    public function courierUpdate(Request $request)
    {
        $request->validate([
            'courier_type' => 'required|in:steadfast,redx,pathao',
            'steadfast_api_key' => 'nullable|string',
            'steadfast_secret_key' => 'nullable|string',
            'redx_api_token' => 'nullable|string',
            'pathao_client_id' => 'nullable|string',
            'pathao_client_secret' => 'nullable|string',
            'pathao_username' => 'nullable|string',
            'pathao_password' => 'nullable|string',
            'pathao_store_id' => 'nullable|string',
            'pathao_is_test' => 'nullable|boolean',
        ]);

        $data = $request->only([
            'courier_type', 'steadfast_api_key', 'steadfast_secret_key',
            'redx_api_token', 'pathao_client_id', 'pathao_client_secret',
            'pathao_username', 'pathao_password', 'pathao_store_id'
        ]);
        $data['pathao_is_test'] = $request->boolean('pathao_is_test');

        $settings = Setting::first();
        if (!$settings) {
            Setting::create($data);
        } else {
            $settings->update($data);
        }

        Cache::forget('site_settings');
        return back()->with('success', 'Courier settings updated successfully.');
    }

    public function paymentIndex()
    {
        $settings = Setting::first();
        return view('admin.settings.payment', compact('settings'));
    }

    public function paymentUpdate(Request $request)
    {
        $request->validate([
            'is_bkash_active' => 'nullable|boolean',
            'bkash_app_key' => 'nullable|string',
            'bkash_app_secret' => 'nullable|string',
            'bkash_username' => 'nullable|string',
            'bkash_password' => 'nullable|string',
            'is_nagad_active' => 'nullable|boolean',
            'nagad_merchant_id' => 'nullable|string',
            'nagad_merchant_number' => 'nullable|string',
            'nagad_public_key' => 'nullable|string',
            'nagad_private_key' => 'nullable|string',
            'is_ssl_active' => 'nullable|boolean',
            'ssl_store_id' => 'nullable|string',
            'ssl_store_password' => 'nullable|string',
            'ssl_is_test' => 'nullable|boolean',
        ]);

        $data = $request->only([
            'bkash_app_key', 'bkash_app_secret', 'bkash_username', 'bkash_password',
            'nagad_merchant_id', 'nagad_merchant_number', 'nagad_public_key', 'nagad_private_key',
            'ssl_store_id', 'ssl_store_password'
        ]);

        $data['is_bkash_active'] = $request->boolean('is_bkash_active');
        $data['is_nagad_active'] = $request->boolean('is_nagad_active');
        $data['is_ssl_active'] = $request->boolean('is_ssl_active');
        $data['ssl_is_test'] = $request->boolean('ssl_is_test');

        $settings = Setting::first();
        if (!$settings) {
            Setting::create($data);
        } else {
            $settings->update($data);
        }

        Cache::forget('site_settings');
        return back()->with('success', 'Payment Gateway settings updated successfully.');
    }
}
