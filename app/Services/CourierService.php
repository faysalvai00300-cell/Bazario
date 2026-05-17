<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CourierService
{
    protected $settings;

    public function __construct()
    {
        $this->settings = Setting::first();
    }

    /**
     * Send order to the configured courier.
     */
    public function sendOrder(Order $order, $courierType = null)
    {
        $type = $courierType ?? ($this->settings->courier_type ?? 'steadfast');

        switch ($type) {
            case 'steadfast':
                return $this->sendToSteadfast($order);
            case 'redx':
                return $this->sendToRedX($order);
            case 'pathao':
                return $this->sendToPathao($order);
            default:
                return ['success' => false, 'message' => 'Invalid courier type configured.'];
        }
    }

    /**
     * Integration with Steadfast Courier.
     */
    private function sendToSteadfast(Order $order)
    {
        $apiKey = $this->settings->steadfast_api_key;
        $secretKey = $this->settings->steadfast_secret_key;

        if (!$apiKey || !$secretKey) {
            return ['success' => false, 'message' => 'Steadfast API credentials missing.'];
        }

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Api-Key' => $apiKey,
                'Secret-Key' => $secretKey,
                'Content-Type' => 'application/json'
            ])->post('https://portal.steadfast.com.bd/api/v1/create_order', [
                'invoice' => (string)$order->order_number,
                'recipient_name' => $order->customer_name,
                'recipient_phone' => $order->customer_phone,
                'recipient_address' => $order->shipping_address,
                'cod_amount' => (int)($order->payment_status === 'paid' ? 0 : $order->total_amount),
                'note' => $order->admin_note ?? 'Fragile. Handle with care.'
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['status']) && $data['status'] == 200) {
                $order->update([
                    'courier_status' => 'sent',
                    'courier_tracking_id' => $data['order']['consignment_id'] ?? null,
                    'courier_name' => 'Steadfast'
                ]);

                return ['success' => true, 'message' => 'Order sent to Steadfast successfully.', 'tracking_id' => $data['order']['consignment_id'] ?? null];
            }

            Log::error('Steadfast API Error: ' . json_encode($data));
            return ['success' => false, 'message' => $data['errors'][0] ?? 'Failed to send order to Steadfast.'];
        } catch (\Exception $e) {
            Log::error('Steadfast Integration Exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Connection error: ' . $e->getMessage()];
        }
    }

    /**
     * Integration with RedX Courier.
     */
    private function sendToRedX(Order $order, $area_id = null)
    {
        $token = $this->settings->redx_api_token ?? null;
        if (!$token) return ['success' => false, 'message' => 'RedX API Token missing.'];

        if (!$area_id) return ['success' => false, 'message' => 'Area ID is required for RedX.'];

        try {
            $response = Http::withoutVerifying()->withToken($token)->post('https://api.redx.com.bd/v1/parcels', [
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'delivery_address' => $order->shipping_address,
                'area_id' => (int)$area_id,
                'cash_to_be_collected' => (int)($order->payment_status === 'paid' ? 0 : $order->total),
                'merchant_invoice_id' => (string)$order->order_number,
                'value' => (int)$order->total,
                'weight' => 0.5, // Default weight
            ]);

            $data = $response->json();
            if ($response->successful() && isset($data['tracking_id'])) {
                $order->update(['courier_status' => 'sent', 'courier_tracking_id' => $data['tracking_id'], 'courier_name' => 'RedX']);
                return ['success' => true, 'message' => 'Sent to RedX successfully.', 'tracking_id' => $data['tracking_id']];
            }
            return ['success' => false, 'message' => $data['message'] ?? 'RedX API error.'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'RedX Error: ' . $e->getMessage()];
        }
    }

    /**
     * Integration with Pathao Courier.
     */
    private function sendToPathao(Order $order, $area_data = [])
    {
        $token = $this->getPathaoToken();
        if (!$token) return ['success' => false, 'message' => 'Pathao Auth failed.'];

        $store_id = $this->settings->pathao_store_id;
        if (!$store_id) return ['success' => false, 'message' => 'Pathao Store ID missing.'];

        try {
            $baseUrl = ($this->settings->pathao_is_test ?? 1) ? 'https://api-hermes.pathao.com' : 'https://api-hermes.pathao.com'; // Use live for both if sandbox not available
            
            $response = Http::withoutVerifying()->withToken($token)->post($baseUrl . '/aladdin/api/v1/orders', [
                'store_id' => (int)$store_id,
                'merchant_order_id' => (string)$order->order_number,
                'recipient_name' => $order->customer_name,
                'recipient_phone' => $order->customer_phone,
                'recipient_address' => $order->shipping_address,
                'recipient_city' => (int)($area_data['city_id'] ?? 0),
                'recipient_zone' => (int)($area_data['zone_id'] ?? 0),
                'recipient_area' => (int)($area_data['area_id'] ?? 0),
                'delivery_type' => 48, // Normal Delivery
                'item_type' => 2, // Parcel
                'order_type' => 'delivery',
                'item_quantity' => 1,
                'amount_to_collect' => (int)($order->payment_status === 'paid' ? 0 : $order->total),
                'item_description' => 'Online Order',
            ]);

            $data = $response->json();
            if ($response->successful() && isset($data['data']['consignment_id'])) {
                $order->update(['courier_status' => 'sent', 'courier_tracking_id' => $data['data']['consignment_id'], 'courier_name' => 'Pathao']);
                return ['success' => true, 'message' => 'Sent to Pathao successfully.', 'tracking_id' => $data['data']['consignment_id']];
            }
            return ['success' => false, 'message' => $data['message'] ?? 'Pathao API error.'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Pathao Error: ' . $e->getMessage()];
        }
    }

    private function getPathaoToken()
    {
        $cacheKey = 'pathao_token_' . $this->settings->id;
        if (cache()->has($cacheKey)) return cache($cacheKey);

        $baseUrl = ($this->settings->pathao_is_test ?? 1) ? 'https://api-hermes-sandbox.pathao.com' : 'https://api-hermes.pathao.com';

        $response = Http::withoutVerifying()->post($baseUrl . '/uaa/oauth/token', [
            'client_id' => $this->settings->pathao_client_id,
            'client_secret' => $this->settings->pathao_client_secret,
            'username' => $this->settings->pathao_username,
            'password' => $this->settings->pathao_password,
            'grant_type' => 'password',
        ]);

        if ($response->successful()) {
            $token = $response->json()['access_token'] ?? null;
            if ($token) {
                cache([$cacheKey => $token], now()->addDays(14));
                return $token;
            }
        }
        return null;
    }

    public function getPathaoCities() {
        $token = $this->getPathaoToken();
        if (!$token) return ['error' => 'Pathao Authentication Failed. Check credentials.'];
        $baseUrl = ($this->settings->pathao_is_test ?? 1) ? 'https://api-hermes-sandbox.pathao.com' : 'https://api-hermes.pathao.com';
        $res = Http::withoutVerifying()->withToken($token)->get($baseUrl . '/aladdin/api/v1/cities');
        
        $json = $res->json();
        // Handle both possible structures
        $data = [];
        if (isset($json['data']['data'])) $data = $json['data']['data'];
        elseif (isset($json['data'])) $data = $json['data'];
        
        if (empty($data)) {
            return [['city_id' => 1, 'city_name' => 'Check API Settings (No Data)']];
        }
        
        return $data;
    }

    public function getPathaoZones($cityId) {
        $token = $this->getPathaoToken();
        if (!$token) return [];
        $baseUrl = ($this->settings->pathao_is_test ?? 1) ? 'https://api-hermes-sandbox.pathao.com' : 'https://api-hermes.pathao.com';
        $res = Http::withoutVerifying()->withToken($token)->get($baseUrl . "/aladdin/api/v1/cities/{$cityId}/zones");
        return $res->json()['data']['data'] ?? [];
    }

    public function getPathaoAreas($zoneId) {
        $token = $this->getPathaoToken();
        if (!$token) return [];
        $baseUrl = ($this->settings->pathao_is_test ?? 1) ? 'https://api-hermes-sandbox.pathao.com' : 'https://api-hermes.pathao.com';
        $res = Http::withoutVerifying()->withToken($token)->get($baseUrl . "/aladdin/api/v1/zones/{$zoneId}/area-list");
        return $res->json()['data']['data'] ?? [];
    }

    public function getRedXAreas() {
        $token = $this->settings->redx_api_token ?? null;
        if (!$token) return [];
        $res = Http::withoutVerifying()->withToken($token)->get('https://api.redx.com.bd/v1/areas');
        return $res->json()['areas'] ?? [];
    }

    /**
     * Check fraud/delivery history by phone number.
     */
    public function checkFraud($phone)
    {
        $apiKey = $this->settings->steadfast_api_key ?? null;
        $secretKey = $this->settings->steadfast_secret_key ?? null;

        if (!$apiKey || !$secretKey) {
            return ['success' => false, 'message' => 'Steadfast API key not configured in Settings.'];
        }

        try {
            // Real Steadfast API endpoint for checking parcel history by phone
            $response = Http::withoutVerifying()->withHeaders([
                'Api-Key' => $apiKey,
                'Secret-Key' => $secretKey,
                'Content-Type' => 'application/json'
            ])->get('https://portal.steadfast.com.bd/api/v1/check_parcel/phone/' . $phone);

            $data = $response->json();

            if ($response->successful()) {
                return [
                    'success' => true,
                    'total' => $data['total_parcels'] ?? 0,
                    'success_count' => $data['success_parcels'] ?? 0,
                    'return_count' => $data['return_parcels'] ?? 0,
                    'rate' => $data['success_rate'] ?? 0,
                ];
            }

            return ['success' => false, 'message' => $data['message'] ?? 'Courier API returned an error.'];
        } catch (\Exception $e) {
            Log::error('Fraud Check Exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()];
        }
    }
}
