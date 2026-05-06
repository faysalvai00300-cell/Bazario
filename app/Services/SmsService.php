<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SmsService
{
    /**
     * Send SMS to a specific number
     *
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public function send($phone, $message)
    {
        // Log the message for debugging
        Log::info("SMS sending to {$phone}: {$message}");

        try {
            $apiKey = env('SMS_API_KEY');
            $senderId = env('SMS_SENDER_ID');

            if (!$apiKey || !$senderId) {
                return false;
            }

            $response = Http::get("https://bulksmsbd.net/api/smsapi", [
                'api_key' => $apiKey,
                'type' => 'text',
                'number' => $phone,
                'senderid' => $senderId,
                'message' => $message
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("SMS Sending Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send Order Confirmation SMS
     *
     * @param \App\Models\Order $order
     * @return bool
     */
    public function sendOrderConfirmation($order)
    {
        $itemsList = $order->items->map(function($item) {
            return $item->product_name . " (x" . $item->quantity . ")";
        })->implode(', ');

        $trackLink = route('pages.track-order', ['order_number' => $order->order_number]);
        
        $message = "🎉 অভিনন্দন! আপনার অর্ডার কনফার্ম হয়েছে! 
অর্ডার নম্বর: {$order->order_number}
পণ্য নাম: {$itemsList}
পণ্য মূল্য: Tk " . number_format($order->subtotal) . "
ডেলিভারি চার্জ: Tk " . number_format($order->shipping) . "
মোট: Tk " . number_format($order->total) . "
ট্র্যাক করুন: {$trackLink}";

        return $this->send($order->phone, $message);
    }
}
