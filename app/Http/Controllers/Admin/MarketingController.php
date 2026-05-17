<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FacebookAdsService;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public function adsAnalytics(Request $request, FacebookAdsService $fbService)
    {
        $days = $request->get('days', '7');
        
        $fbAdsData = null;
        $dailyData = [];
        if ($fbService->isConfigured()) {
            $fbAdsData = $fbService->getInsights($days);
            $dailyData = $fbService->getDailyInsights($days);
        }

        return view('admin.marketing.ads-analytics', compact('fbAdsData', 'dailyData', 'days'));
    }
}
