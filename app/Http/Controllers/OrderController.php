<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        $orders = Order::where(function($q) use ($user) {
            $q->where('user_id', $user->id);
            if ($user->phone) $q->orWhere('phone', $user->phone);
            if ($user->email) $q->orWhere('email', $user->email);
        })->with('items')->latest()->paginate(10);
        
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $user = auth()->user();
        
        // Allow access if owner by ID, or phone matches, or email matches, or is admin
        $isOwner = ($order->user_id == $user->id) || 
                   ($user->phone && $order->phone == $user->phone) || 
                   ($user->email && $order->email == $user->email);

        if (!$isOwner && $user->role !== 'admin') {
            abort(403);
        }

        $order->load('items.product');
        return view('orders.show', compact('order'));
    }
}
