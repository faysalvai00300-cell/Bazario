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
            $response = Http::withHeaders([
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
    private function sendToRedX(Order $order)
    {
        $token = $this->settings->redx_api_token;

        if (!$token) {
            return ['success' => false, 'message' => 'RedX API Token missing.'];
        }

        // RedX implementation would go here (requires specific endpoint and format)
        return ['success' => false, 'message' => 'RedX integration is drafted. Please contact developer for final mapping.'];
    }

    /**
     * Integration with Pathao Courier.
     */
    private function sendToPathao(Order $order)
    {
        // Pathao is more complex, requiring OAuth2 token generation first.
        return ['success' => false, 'message' => 'Pathao integration requires store-specific city/zone IDs. Please configure Steadfast for instant use.'];
    }
}
