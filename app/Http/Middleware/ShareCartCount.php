<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareCartCount
{
    public function handle(Request $request, Closure $next): Response
    {
        $cart = session('cart', []);
        $cartCount = 0;
        foreach ($cart as $item) {
            $cartCount += is_array($item) ? ($item['quantity'] ?? 0) : $item;
        }
        View::share('cartCount', $cartCount);
        return $next($request);
    }
}
