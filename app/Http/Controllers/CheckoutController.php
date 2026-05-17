<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\DeliveryArea;

class CheckoutController extends Controller
{
    private function getCartItems()
    {
        $cartData = session('cart', []);
        if (empty($cartData)) return [];

        $items = [];
        foreach ($cartData as $key => $data) {
            if (is_array($data)) {
                $product = Product::find($data['product_id']);
                if ($product && $product->is_active) {
                    $items[] = [
                        'product' => $product, 
                        'quantity' => $data['quantity'],
                        'size' => $data['size'] ?? '',
                        'color' => $data['color'] ?? '',
                    ];
                }
            } else {
                $product = Product::find($key);
                if ($product && $product->is_active) {
                    $items[] = [
                        'product' => $product, 
                        'quantity' => $data,
                        'size' => '',
                        'color' => '',
                    ];
                }
            }
        }
        return $items;
    }

    private function hasFreeShipping(array $items): bool
    {
        foreach ($items as $item) {
            if ($item['product']->free_shipping) return true;
        }
        return false;
    }

    private function getShippingCharge($subtotal, $promoDiscount, $cartItems, $area = 'outside')
    {
        if ($this->hasFreeShipping($cartItems)) return 0;
        
        $settings = \App\Models\Setting::first();
        if ($settings && $settings->is_free_delivery_active && ($subtotal - $promoDiscount) >= ($settings->free_delivery_threshold ?? 0)) {
            return 0;
        }

        if (is_numeric($area)) {
            $customArea = DeliveryArea::find($area);
            if ($customArea) return $customArea->charge;
        }

        $inside = $settings->delivery_charge_inside ?? 70;
        $outside = $settings->delivery_charge_outside ?? 130;
        
        return ($area === 'inside') ? $inside : $outside;
    }

    public function index()
    {
        $cartItems = $this->getCartItems();
        if (empty($cartItems)) return redirect()->route('cart.index')->with('error', 'আপনার কার্ট খালি!');

        $subtotal = array_sum(array_map(fn($i) => $i['product']->effective_price * $i['quantity'], $cartItems));
        $promoDiscount = session('promo_discount', 0);
        
        $discountedSubtotal = max(0, $subtotal - $promoDiscount);
        
        $settings = \App\Models\Setting::first();
        $isFreeByThreshold = $settings && $settings->is_free_delivery_active && ($subtotal - $promoDiscount) >= ($settings->free_delivery_threshold ?? 0);
        $hasFreeShipping = $this->hasFreeShipping($cartItems) || $isFreeByThreshold;
        
        $deliveryAreas = DeliveryArea::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $area = request('delivery_area', $deliveryAreas->isNotEmpty() ? $deliveryAreas->first()->id : 'outside');
        $shipping = $this->getShippingCharge($subtotal, $promoDiscount, $cartItems, $area);
        $total = $discountedSubtotal + $shipping;
        $isGatewayConfigured = !empty(env('SSLCZ_STORE_ID'));
        
        $siteSettings = \App\Models\Setting::first();
        $deliveryAreas = DeliveryArea::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        return view('checkout.index', compact('cartItems', 'subtotal', 'total', 'hasFreeShipping', 'shipping', 'promoDiscount', 'isGatewayConfigured', 'siteSettings', 'deliveryAreas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|regex:/^01[3-9]\d{8}$/',
            'division' => 'required|string',
            'district' => 'required|string',
            'thana' => 'required|string',
            'address' => 'required|string',
            'payment_method' => ['required', 'in:cod,bkash,nagad', function($attribute, $value, $fail) {
                if (in_array($value, ['bkash', 'nagad']) && empty(env('SSLCZ_STORE_ID'))) {
                    $fail('দুঃখিত! বিকাশ এবং নগদ পেমেন্ট বর্তমানে বন্ধ আছে। অনুগ্রহ করে ক্যাশ অন ডেলিভারি সিলেক্ট করুন।');
                }
            }],
            'transaction_id' => 'required_if:payment_method,bkash,nagad|nullable|string|max:100',
            'payment_phone' => 'required_if:payment_method,bkash,nagad|nullable|string|max:20',
        ], [
            'phone.regex' => 'সঠিক বাংলাদেশি ফোন নম্বর দিন (১১ ডিজিট, উদাহরন: 01XXXXXXXXX)',
            'transaction_id.required_if' => 'ট্রানজেকশন আইডি প্রদান করুন',
            'payment_phone.required_if' => 'যে নম্বর থেকে টাকা পাঠিয়েছেন তা প্রদান করুন',
        ]);

        $cartItems = $this->getCartItems();
        if (empty($cartItems)) return redirect()->route('cart.index');

        $subtotal = array_sum(array_map(fn($i) => $i['product']->effective_price * $i['quantity'], $cartItems));
        $promoDiscount = session('promo_discount', 0);
        $shipping = $this->getShippingCharge($subtotal, $promoDiscount, $cartItems, $request->delivery_area ?? 'outside');
        $total = max(0, $subtotal - $promoDiscount) + $shipping;

        $checkoutOrderId = session('checkout_order_id');
        $orderData = [
            'order_number' => $checkoutOrderId ? Order::find($checkoutOrderId)->order_number : (string)rand(100000, 999999),
            'user_id' => auth()->check() ? auth()->id() : null,
            'name' => $request->name,
            'email' => optional(auth()->user())->email ?? '',
            'phone' => $request->phone,
            'address' => $request->address,
            'thana' => $request->thana,
            'city' => $request->district ?? '',
            'state' => $request->division,
            'postal_code' => '',
            'subtotal' => $subtotal,
            'discount' => $promoDiscount,
            'shipping' => $shipping,
            'total' => $total,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'payment_phone' => $request->payment_phone,
            'payment_status' => 'pending',
            'status' => 'pending',
            'promo_code' => session('promo_code'),
            'notes' => $request->notes,
            'ip_address' => $request->ip(),
        ];

        if ($checkoutOrderId) {
            $order = Order::find($checkoutOrderId);
            $order->update($orderData);
            // Clear items to avoid duplicates
            $order->items()->delete();
        } else {
            $order = Order::create($orderData);
        }

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product']->id,
                'product_name' => $item['product']->name,
                'product_image' => $item['product']->getColorImagePath($item['color'] ?? ''),
                'price' => $item['product']->effective_price,
                'buying_price' => $item['product']->buying_price,
                'quantity' => $item['quantity'],
                'total' => $item['product']->effective_price * $item['quantity'],
                'size' => $item['size'],
                'color' => $item['color'],
            ]);

            // Decrement stock
            if ($order->status === 'pending') {
                $item['product']->decrement('stock', $item['quantity']);
            }
        }

        // Clear cart and promo and incomplete session
        session()->forget(['cart', 'promo_code', 'promo_discount', 'promo_message', 'checkout_order_id']);

        return redirect()->route('checkout.success', $order->id);
    }

    public function captureLead(Request $request)
    {
        $cartItems = $this->getCartItems();
        if (empty($cartItems)) return response()->json(['success' => false]);

        $subtotal = array_sum(array_map(fn($i) => $i['product']->effective_price * $i['quantity'], $cartItems));
        $promoDiscount = session('promo_discount', 0);
        $shipping = $this->getShippingCharge($subtotal, $promoDiscount, $cartItems, $request->delivery_area ?? 'outside');
        $total = max(0, $subtotal - $promoDiscount) + $shipping;

        $checkoutOrderId = session('checkout_order_id');
        
        $orderData = [
            'name' => $request->name ?? 'Draft Order',
            'phone' => $request->phone ?? '',
            'address' => $request->address ?? '',
            'thana' => $request->thana ?? '',
            'city' => $request->district ?? '',
            'state' => $request->division ?? '',
            'subtotal' => $subtotal,
            'total' => $total,
            'shipping' => $shipping,
            'status' => 'incomplete',
            'email' => optional(auth()->user())->email ?? '',
            'ip_address' => $request->ip(),
        ];

        if ($checkoutOrderId) {
            $order = Order::find($checkoutOrderId);
            if ($order) {
                $order->update($orderData);
            } else {
                $order = Order::create(array_merge($orderData, ['order_number' => (string)rand(100000, 999999)]));
            }
        } else {
            $order = Order::create(array_merge($orderData, ['order_number' => (string)rand(100000, 999999)]));
            session(['checkout_order_id' => $order->id]);
        }

        // Sync items for completeness
        $order->items()->delete();
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product']->id,
                'product_name' => $item['product']->name,
                'product_image' => $item['product']->getColorImagePath($item['color'] ?? ''),
                'price' => $item['product']->effective_price,
                'buying_price' => $item['product']->buying_price,
                'quantity' => $item['quantity'],
                'total' => $item['product']->effective_price * $item['quantity'],
                'size' => $item['size'],
                'color' => $item['color'],
            ]);
        }

        return response()->json(['success' => true, 'order_id' => $order->id]);
    }

    public function success(Order $order)
    {
        return view('checkout.success', compact('order'));
    }
}
