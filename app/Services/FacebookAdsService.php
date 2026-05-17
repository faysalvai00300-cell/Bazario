<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookAdsService
{
    protected $accessToken;
    protected $adAccountId;
    protected $appId;
    protected $appSecret;

    public function __construct()
    {
        $settings = Setting::first();
        if ($settings) {
            $this->accessToken = $settings->facebook_access_token;
            $this->adAccountId = $settings->facebook_ad_account_id;
            $this->appId = $settings->facebook_app_id;
            $this->appSecret = $settings->facebook_app_secret;
        }
    }

    public function isConfigured()
    {
        return !empty($this->accessToken) && !empty($this->adAccountId);
    }

    public function getInsights($days = 7)
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $adAccountId = str_replace('act_', '', $this->adAccountId);
        $url = "https://graph.facebook.com/v19.0/act_{$adAccountId}/insights";

        try {
            $response = Http::get($url, [
                'access_token' => $this->accessToken,
                'date_preset' => $this->getDatePreset($days),
                'fields' => 'spend,impressions,clicks,actions,action_values,cpc,cpp,ctr',
            ]);

            if ($response->successful()) {
                $data = $response->json()['data'][0] ?? null;
                if ($data) {
                    // Extract purchase count
                    if (isset($data['actions'])) {
                        $purchases = collect($data['actions'])->firstWhere('action_type', 'purchase');
                        $data['purchases'] = $purchases ? $purchases['value'] : 0;
                    } else {
                        $data['purchases'] = 0;
                    }

                    // Extract purchase value (Revenue)
                    if (isset($data['action_values'])) {
                        $purchaseValue = collect($data['action_values'])->firstWhere('action_type', 'purchase');
                        $data['purchase_value'] = $purchaseValue ? $purchaseValue['value'] : 0;
                    } else {
                        $data['purchase_value'] = 0;
                    }
                }
                return $data;
            }

            Log::error('Facebook Ads API Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Facebook Ads API Exception: ' . $e->getMessage());
            return null;
        }
    }

    public function getDailyInsights($days = 7)
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $adAccountId = str_replace('act_', '', $this->adAccountId);
        $url = "https://graph.facebook.com/v19.0/act_{$adAccountId}/insights";

        try {
            $response = Http::get($url, [
                'access_token' => $this->accessToken,
                'date_preset' => $this->getDatePreset($days),
                'fields' => 'spend,clicks,actions,action_values,date_start',
                'time_increment' => 1,
            ]);

            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                foreach ($data as &$item) {
                    // Count
                    if (isset($item['actions'])) {
                        $p = collect($item['actions'])->firstWhere('action_type', 'purchase');
                        $item['purchases'] = $p ? $p['value'] : 0;
                    } else {
                        $item['purchases'] = 0;
                    }
                    // Value
                    if (isset($item['action_values'])) {
                        $pv = collect($item['action_values'])->firstWhere('action_type', 'purchase');
                        $item['purchase_value'] = $pv ? $pv['value'] : 0;
                    } else {
                        $item['purchase_value'] = 0;
                    }
                }
                return $data;
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getDatePreset($days)
    {
        switch ($days) {
            case 'today': return 'today';
            case 'yesterday': return 'yesterday';
            case '7': return 'last_7d';
            case '14': return 'last_14d';
            case '30': return 'last_30d';
            case 'this_month': return 'this_month';
            case 'last_month': return 'last_month';
            default: return 'last_7d';
        }
    }
}
