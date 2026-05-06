<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookCapiService
{
    /**
     * Send a purchase event to Facebook Conversion API
     */
    public function sendPurchaseEvent(Order $order)
    {
        $settings = Setting::first();
        $pixelId = $settings->facebook_pixel_id ?? null;
        $accessToken = $settings->facebook_access_token ?? env('FB_ACCESS_TOKEN');

        if (!$pixelId || !$accessToken) {
            Log::warning('Facebook CAPI failed: Pixel ID or Access Token missing.');
            return false;
        }

        $apiUrl = "https://graph.facebook.com/v19.0/{$pixelId}/events";

        $data = [
            'data' => [
                [
                    'event_name' => 'Purchase',
                    'event_time' => time(),
                    'action_source' => 'website',
                    'user_data' => [
                        'em' => [
                            hash('sha256', strtolower(trim($order->email ?? ''))),
                            hash('sha256', strtolower(trim($order->user->email ?? '')))
                        ],
                        'ph' => [
                            hash('sha256', (function($p) {
                                $p = preg_replace('/[^0-9]/', '', $p);
                                if (strlen($p) === 11 && str_starts_with($p, '0')) $p = '88' . $p;
                                return $p;
                            })($order->phone))
                        ],
                        'fn' => [hash('sha256', strtolower(trim(explode(' ', $order->name)[0])))],
                        'client_user_agent' => request()->header('User-Agent'),
                        'client_ip_address' => request()->ip(),
                        'fbc' => request()->cookie('_fbc'),
                        'fbp' => request()->cookie('_fbp'),
                    ],
                    'custom_data' => [
                        'value' => (float) $order->total,
                        'currency' => 'BDT',
                        'order_id' => $order->id,
                        'contents' => $order->items->map(function ($item) {
                            return [
                                'id' => (string)$item->product_id,
                                'quantity' => $item->quantity,
                                'item_price' => (float) $item->price
                            ];
                        })->toArray()
                    ],
                    'event_id' => 'purchase_' . $order->id, // Used for deduplication
                ]
            ],
            'access_token' => $accessToken
        ];

        try {
            $response = Http::post($apiUrl, $data);
            if ($response->successful()) {
                Log::info("Facebook CAPI Purchase sent for order #{$order->order_number}");
                return true;
            } else {
                Log::error("Facebook CAPI Error: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Facebook CAPI Exception: " . $e->getMessage());
            return false;
        }
    }
}
