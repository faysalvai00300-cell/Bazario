<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCartKey()
    {
        if (auth()->check()) {
            return 'user_' . auth()->id();
        }
        if (!session()->has('cart_session_id')) {
            session(['cart_session_id' => uniqid('cart_', true)]);
        }
        return session('cart_session_id');
    }

    private function getCartItems()
    {
        $cartData = session('cart', []);
        if (empty($cartData))
            return [];

        $items = [];
        foreach ($cartData as $key => $data) {
            if (is_array($data)) {
                $product = Product::with('flashSales')->find($data['product_id']);
                if ($product && $product->is_active) {
                    $items[] = [
                        'product' => $product,
                        'quantity' => $data['quantity'],
                        'size' => $data['size'] ?? '',
                        'color' => $data['color'] ?? '',
                        'cart_key' => $key
                    ];
                }
            } else {
                // Backward compatibility for old simple product_id => qty
                $product = Product::with('flashSales')->find($key);
                if ($product && $product->is_active) {
                    $items[] = [
                        'product' => $product,
                        'quantity' => $data,
                        'size' => '',
                        'color' => '',
                        'cart_key' => $key
                    ];
                }
            }
        }
        return $items;
    }

    private function getSubtotal($items)
    {
        return array_sum(array_map(fn($i) => $i['product']->effective_price * $i['quantity'], $items));
    }

    private function hasFreeShipping($items)
    {
        foreach ($items as $item) {
            if ($item['product']->free_shipping)
                return true;
        }
        return false;
    }

    public function index()
    {
        $cartItems = $this->getCartItems();
        $subtotal = $this->getSubtotal($cartItems);
        $settings = \App\Models\Setting::first();
        $deliveryCharge = $settings->delivery_charge ?? 60;
        $freeThreshold = $settings->free_delivery_threshold ?? 1000;

        $promoDiscount = session('promo_discount', 0);
        $isFreeByThreshold = $settings && $settings->is_free_delivery_active && ($subtotal - $promoDiscount) >= ($settings->free_delivery_threshold ?? 0);
        $shipping = $this->hasFreeShipping($cartItems) || $isFreeByThreshold ? 0 : $deliveryCharge;
        $total = $subtotal - $promoDiscount + $shipping;

        return view('cart.index', compact('cartItems', 'subtotal', 'total', 'deliveryCharge', 'shipping'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $productId = $request->product_id;
        $qty = $request->quantity ?? 1;
        $size = $request->size ?? '';
        $color = $request->color ?? '';

        $product = Product::findOrFail($productId);
        if ($product->stock < $qty) {
            return response()->json(['success' => false, 'message' => 'Sorry, insufficient stock available.']);
        }

        $cartKey = $productId . '_' . \Illuminate\Support\Str::slug($size) . '_' . \Illuminate\Support\Str::slug($color);
        $cart = session('cart', []);

        if (isset($cart[$cartKey])) {
            if (is_array($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] += $qty;
            } else {
                // Convert old simple entry to new structure
                $cart[$cartKey] = [
                    'product_id' => $productId,
                    'quantity' => $cart[$cartKey] + $qty,
                    'size' => $size,
                    'color' => $color
                ];
            }
        } else {
            $cart[$cartKey] = [
                'product_id' => $productId,
                'quantity' => $qty,
                'size' => $size,
                'color' => $color
            ];
        }
        session(['cart' => $cart]);

        // Optimized: Return full cart details immediately to avoid a second AJAX request
        $cartItems = $this->getCartItems();
        $subtotal = $this->getSubtotal($cartItems);
        $count = collect($cart)->sum(fn($i) => is_array($i) ? ($i['quantity'] ?? 0) : $i);

        $formattedItems = collect($cartItems)->map(function ($item) {
            return [
                'id' => $item['product']->id,
                'name' => $item['product']->name,
                'price' => (float) $item['product']->effective_price,
                'qty' => $item['quantity'],
                'image' => $item['product']->getColorImageUrl($item['color'] ?? ''),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => $product->name . ' added to cart!',
            'cart_count' => $count,
            'item_price' => (float) $product->effective_price,
            'cart_details' => [
                'items' => $formattedItems,
                'subtotal' => $subtotal,
                'count' => $count
            ]
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|string',
            'quantity' => 'required|integer|min:1'
        ]);
        $cart = session('cart', []);
        if (isset($cart[$request->cart_key])) {
            if (is_array($cart[$request->cart_key])) {
                $cart[$request->cart_key]['quantity'] = $request->quantity;
            } else {
                $cart[$request->cart_key] = $request->quantity;
            }
        }
        session(['cart' => $cart]);

        return $this->details();
    }

    public function remove($cartKey)
    {
        $cart = session('cart', []);
        unset($cart[$cartKey]);
        session(['cart' => $cart]);

        return $this->details();
    }

    public function applyPromo(Request $request)
    {
        $request->validate(['promo_code' => 'required|string']);
        $code = strtoupper(trim($request->promo_code));
        $promo = PromoCode::where('code', $code)->first();

        if (!$promo || !$promo->isValid()) {
            session(['promo_message' => 'Invalid or expired promo code.']);
            session(['promo_discount' => 0]);
            session(['promo_code' => null]);
            return redirect()->route('cart.index');
        }

        $cartItems = $this->getCartItems();
        $subtotal = $this->getSubtotal($cartItems);
        $discount = $promo->calculateDiscount($subtotal);

        if ($discount == 0) {
            session(['promo_message' => "Minimum order of Tk{$promo->min_order} required."]);
            return redirect()->route('cart.index');
        }

        session(['promo_discount' => $discount, 'promo_code' => $code, 'promo_message' => "Promo applied! You saved Tk{$discount}."]);
        return redirect()->route('cart.index');
    }

    public function count()
    {
        $cart = session('cart', []);
        $count = 0;
        foreach ($cart as $item) {
            $count += is_array($item) ? $item['quantity'] : $item;
        }
        return response()->json(['count' => $count]);
    }

    public function details()
    {
        $cartItems = $this->getCartItems();
        $subtotal = $this->getSubtotal($cartItems);
        $count = collect(session('cart', []))->sum(fn($i) => is_array($i) ? ($i['quantity'] ?? 0) : $i);

        $formattedItems = collect($cartItems)->map(function ($item) {
            return [
                'id' => $item['product']->id,
                'cart_key' => $item['cart_key'],
                'name' => $item['product']->name,
                'size' => $item['size'],
                'color' => $item['color'],
                'price' => $item['product']->effective_price,
                'qty' => $item['quantity'],
                'image' => $item['product']->getColorImageUrl($item['color'] ?? ''),
            ];
        });

        return response()->json([
            'items' => $formattedItems,
            'subtotal' => $subtotal,
            'count' => $count
        ]);
    }

    public function applyCouponAjax(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);
        $code = strtoupper(trim($request->coupon_code));
        $promo = PromoCode::where('code', $code)->first();

        if (!$promo || !$promo->isValid()) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired coupon code.']);
        }

        $cartItems = $this->getCartItems();
        $subtotal = $this->getSubtotal($cartItems);
        $discount = $promo->calculateDiscount($subtotal);

        if ($discount == 0) {
            return response()->json(['success' => false, 'message' => "Minimum order of Tk{$promo->min_order} required."]);
        }

        session(['promo_discount' => $discount, 'promo_code' => $code, 'coupon_code' => $code]);

        // Calculate final totals for immediate UI update
        $settings = \App\Models\Setting::first();
        $deliveryCharge = $settings->delivery_charge ?? 60;
        $isFreeByThreshold = $settings && $settings->is_free_delivery_active && ($subtotal - $discount) >= ($settings->free_delivery_threshold ?? 0);
        $shipping = $this->hasFreeShipping($cartItems) || $isFreeByThreshold ? 0 : $deliveryCharge;
        $total = ($subtotal - $discount) + $shipping;

        return response()->json([
            'success' => true,
            'message' => "Coupon applied! You saved Tk{$discount}.",
            'discount' => $discount,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total
        ]);
    }

    public function removeCouponAjax()
    {
        session()->forget(['promo_discount', 'promo_code', 'coupon_code']);

        $cartItems = $this->getCartItems();
        $subtotal = $this->getSubtotal($cartItems);

        $settings = \App\Models\Setting::first();
        $deliveryCharge = $settings->delivery_charge ?? 60;
        $isFreeByThreshold = $settings && $settings->is_free_delivery_active && $subtotal >= ($settings->free_delivery_threshold ?? 0);
        $shipping = $this->hasFreeShipping($cartItems) || $isFreeByThreshold ? 0 : $deliveryCharge;
        $total = $subtotal + $shipping;

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed.',
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total
        ]);
    }
}
