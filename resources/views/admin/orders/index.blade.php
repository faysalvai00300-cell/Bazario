@extends('layouts.admin')
@section('title', 'Orders Management')
@section('content')

<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-black text-gray-900">Orders Management</h2>
            <p class="text-sm text-gray-500 mt-1">Total Found: <span class="font-bold text-orange-600">{{ $orders->total() }}</span></p>
        </div>
    </div>

<!-- Filter Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 dark:bg-gray-800 dark:border-gray-700 transition-colors">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-5 items-end">
            <!-- Search -->
            <div class="space-y-2">
                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider ml-1 dark:text-gray-500">Search ID / Customer</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400 group-focus-within:text-orange-500 transition-colors"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Order #, Name, Phone..." 
                        class="w-full pl-10 rounded-xl border border-gray-200 bg-white shadow-sm text-sm focus:ring-orange-500 focus:border-orange-500 h-11 transition-all dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                </div>
            </div>

            <!-- Status -->
            <div class="space-y-2">
                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider ml-1 dark:text-gray-500">Status Type</label>
                <select name="status" onchange="this.form.submit()" class="w-full rounded-xl border border-gray-200 bg-white shadow-sm text-sm focus:ring-orange-500 focus:border-orange-500 h-11 transition-all dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <!-- Product -->
            <div class="md:col-span-1 lg:col-span-2 space-y-2">
                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider ml-1 dark:text-gray-500">Filter by Product</label>
                <select name="product_id" onchange="this.form.submit()" class="w-full rounded-xl border border-gray-200 bg-white shadow-sm text-sm focus:ring-orange-500 focus:border-orange-500 h-11 transition-all dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                    <option value="">All Products</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2 justify-end">
                @if(request()->anyFilled(['search', 'status', 'product_id']))
                    <a href="{{ route('admin.orders.index') }}" class="h-11 px-5 flex items-center justify-center rounded-xl bg-red-50 font-semibold text-sm text-red-500 hover:bg-red-100 transition border border-red-100 dark:bg-red-900/10 dark:text-red-400 dark:border-red-900/20" title="Clear Filters">
                        <i data-lucide="x" class="w-4 h-4 mr-1"></i> Clear Filters
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
    <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-gray-200">
        <table class="w-full text-left text-sm text-gray-600 min-w-[800px] dark:text-gray-300">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-900/50 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-4 font-semibold">Order ID</th>
                    <th class="px-6 py-4 font-semibold hidden lg:table-cell">Date</th>
                    <th class="px-4 py-4 font-semibold">Customer</th>
                    <th class="px-4 py-4 font-semibold text-center">IP Address</th>
                    <th class="px-6 py-4 font-semibold hidden md:table-cell">Payment</th>
                    <th class="px-4 py-4 font-semibold text-right">Total</th>
                    <th class="px-4 py-4 font-semibold text-center">Status</th>
                    <th class="px-4 py-4 font-semibold text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50 transition dark:hover:bg-gray-700/50">
                    <td class="px-4 py-4 font-bold text-gray-900 dark:text-white">{{ $order->order_number }}</td>
                    <td class="px-6 py-4 hidden lg:table-cell">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                    <td class="px-4 py-4">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $order->name }}</div>
                        <div class="text-xs text-gray-500 hidden sm:block">{{ $order->phone }}</div>
                        <div class="text-[10px] font-bold text-orange-600 dark:text-orange-400 uppercase tracking-tight">{{ $order->thana }}, {{ $order->city }}</div>
                    </td>
                    <td class="px-4 py-4 text-center">
                        @if($order->ip_address)
                            <span class="font-mono text-[11px] bg-gray-100 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-200 select-all dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                {{ $order->ip_address }}
                            </span>
                        @else
                            <span class="text-gray-300">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 hidden md:table-cell">
                        <div class="font-medium uppercase text-xs">{{ $order->payment_method }}</div>
                        @if($order->transaction_id)
                            <div class="text-[10px] text-orange-600 font-bold mt-0.5 select-all dark:text-orange-400">TrxID: {{ $order->transaction_id }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-right font-bold text-gray-900 dark:text-white">Tk{{ number_format($order->total) }}</td>
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()" class="text-xs font-bold rounded-lg border-gray-200 focus:ring-0 focus:border-orange-500 dark:bg-gray-900 dark:border-gray-700
                                {{ $order->status === 'pending' ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/20 dark:text-orange-400' : '' }}
                                {{ $order->status === 'confirmed' ? 'bg-cyan-50 text-cyan-600 dark:bg-cyan-900/20 dark:text-cyan-400' : '' }}
                                {{ $order->status === 'processing' ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : '' }}
                                {{ $order->status === 'shipped' ? 'bg-purple-50 text-purple-600 dark:bg-purple-900/20 dark:text-purple-400' : '' }}
                                {{ $order->status === 'delivered' ? 'bg-green-50 text-green-600 dark:bg-green-900/20 dark:text-green-400' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400' : '' }}">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline font-semibold text-xs dark:text-blue-400">View Details</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100 dark:border-gray-700">
        {{ $orders->links() }}
    </div>
</div>

@endsection
