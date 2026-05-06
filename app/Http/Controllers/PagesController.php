<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function trackOrder(Request $request)
    {
        $order = null;
        if ($request->filled('order_number')) {
            $orderNumber = $request->order_number;
            // Support both #12345 and 12345
            $orderNumber = str_replace('#', '', $orderNumber);
            
            $order = \App\Models\Order::where('order_number', $orderNumber)->first();
            
            if (!$order) {
                return back()->with('error', 'অর্ডার নম্বরটি পাওয়া যায়নি!');
            }
        }

        return view('pages.track-order', compact('order'));
    }

    public function returnPolicy()
    {
        return view('pages.return-policy');
    }

    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function contactUs()
    {
        return view('pages.contact-us');
    }

    public function aboutUs()
    {
        return view('pages.about-us');
    }

    public function termsOfService()
    {
        return view('pages.terms-of-service');
    }

    public function newsletterSub(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        // Simulation of subscription
        return back()->with('success', 'Thanks for subscribing to our newsletter!');
    }
}
