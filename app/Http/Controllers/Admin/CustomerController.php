<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->withCount('orders');

        $search = $request->search;
        if ($request->filled('search')) {
            $query->where(static function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(20)->withQueryString();

        // If search returned few/no results and it looks like a phone number or name, 
        // let's look for Guest Orders (orders not linked to any user)
        $guestResults = collect();
        if ($request->filled('search')) {
            // Find unique phone/name combinations from orders that ARE NOT linked to a user
            $guests = \App\Models\Order::whereNull('user_id')
                ->where(function($q) use ($search) {
                    $q->where('phone', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%");
                })
                ->select('name', 'phone', 'email', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get()
                ->unique('phone');

            foreach ($guests as $guest) {
                // Check if this guest isn't already found in the registered users list by phone
                if (!User::where('phone', $guest->phone)->exists()) {
                    $guestResults->push((object)[
                        'id' => null,
                        'name' => $guest->name,
                        'email' => $guest->email,
                        'phone' => $guest->phone,
                        'created_at' => $guest->created_at,
                        'orders_count' => \App\Models\Order::where('phone', $guest->phone)->count(),
                        'is_guest' => true
                    ]);
                }
            }
        }

        return view('admin.customers.index', compact('customers', 'guestResults'));
    }

    public function show($id, Request $request)
    {
        // Handle Guest Lookup via phone number
        if ($id === 'guest') {
            $phone = $request->phone;
            $orders = \App\Models\Order::where('phone', $phone)->latest()->get();
            if ($orders->isEmpty()) return abort(404);
            
            $customer = (object)[
                'name' => $orders->first()->name,
                'phone' => $phone,
                'email' => $orders->first()->email,
                'created_at' => $orders->last()->created_at, // First order date
                'orders' => $orders,
                'is_guest' => true
            ];
            return view('admin.customers.show', compact('customer'));
        }

        $customer = User::with(['messages' => function($q) {
            $q->latest();
        }])->findOrFail($id);
        
        // Fetch all orders by user ID OR phone to catch guest orders as well
        $allOrders = \App\Models\Order::where('user_id', $customer->id)
            ->when($customer->phone, function($q) use ($customer) {
                return $q->orWhere('phone', $customer->phone);
            })
            ->with('items')
            ->latest()
            ->get();
            
        // Manually set the relation to keep the view logic simple
        $customer->setRelation('orders', $allOrders);
        
        return view('admin.customers.show', compact('customer'));
    }

    public function destroy(User $customer)
    {
        // Deleting the user will automatically set user_id to NULL in their orders
        // thanks to the 'onDelete(set null)' constraint in the database.
        $customer->delete();
        
        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully. They can now register again with the same credentials.');
    }

    public function sendMessage(Request $request, User $customer)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        Message::create([
            'user_id' => $customer->id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
            'has_gift' => $request->has('has_gift')
        ]);

        return back()->with('success', 'Message sent to customer successfully!');
    }

    public function deleteMessage(Message $message)
    {
        $message->delete();
        return back()->with('success', 'Message deleted successfully!');
    }
}
