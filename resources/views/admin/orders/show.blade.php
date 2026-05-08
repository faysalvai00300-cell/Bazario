@extends('layouts.admin')
@section('title', 'Order ' . $order->order_number)
@section('content')

<div class="mb-6 flex justify-between items-center flex-wrap gap-4">
    <div>
        <h2 class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}" class="mr-2 p-2 hover:bg-gray-100 rounded-full transition dark:hover:bg-gray-700" title="Back to Orders">
                <i data-lucide="arrow-left" class="w-6 h-6 text-gray-600 dark:text-gray-400"></i>
            </a>
            Order {{ $order->order_number }}
            <span class="px-3 py-1 text-sm rounded-full font-bold
                {{ $order->status === 'pending' ? 'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400' : '' }}
                {{ $order->status === 'confirmed' ? 'bg-cyan-100 text-cyan-600 dark:bg-cyan-900/30 dark:text-cyan-400' : '' }}
                {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400' : '' }}
                {{ $order->status === 'delivered' ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' : '' }}
                {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : '' }}">
                {{ ucfirst($order->status) }}
            </span>
        </h2>
        <p class="text-gray-500 text-sm mt-1 dark:text-gray-400">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
    </div>
    
    <div class="flex items-center gap-3">
        <!-- Courier Status Tool -->
        <div class="flex items-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-0.5 rounded-none overflow-hidden shadow-sm">
            <div class="px-3 py-1 bg-gray-50 dark:bg-gray-700/50 border-r border-gray-200 dark:border-gray-700 flex flex-col">
                <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest leading-none underline">Courier</span>
                <span class="text-[10px] font-bold {{ $order->courier_status === 'sent' ? 'text-green-600' : 'text-gray-500' }} leading-tight">
                    {{ $order->courier_status === 'sent' ? 'SENT' : 'NOT DISPATCHED' }}
                </span>
            </div>
            <div class="px-3">
                @if($order->courier_status !== 'sent')
                <form action="{{ route('admin.orders.send-to-courier', $order) }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    <select name="courier_type" class="text-sm font-bold border-gray-200 py-2 pl-3 pr-10 focus:ring-green-500 rounded-none dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 h-[42px]">
                        <option value="steadfast">Steadfast</option>
                        <option value="redx">RedX</option>
                        <option value="pathao">Pathao</option>
                    </select>
                    <button type="submit" style="background-color: #45b86f !important; color: white !important;" class="hover:bg-green-700 px-5 py-2 rounded-none text-sm font-black uppercase tracking-wider transition shadow-md active:scale-95 flex items-center gap-2 h-[42px]">
                        <i data-lucide="send" class="w-4 h-4"></i> Dispatch
                    </button>
                </form>
                @else
                <div class="flex items-center gap-2 px-2 py-2 text-blue-600 bg-blue-50 dark:bg-blue-900/20">
                    <i data-lucide="truck" class="w-4 h-4"></i>
                    <span class="text-xs font-black uppercase tracking-tight">{{ $order->courier_name }}: {{ $order->courier_tracking_id }}</span>
                </div>
                @endif
            </div>
        </div>

        <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="flex gap-2">
            @csrf @method('PATCH')
            <select name="status" class="border-gray-200 rounded-lg text-sm focus:ring-orange-500 font-medium dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="bg-[#FF6A00] hover:bg-[#FF7A1A] text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-all active:scale-95">Update Status</button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Order Items -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 dark:bg-gray-800 dark:border-gray-700 transition-colors">
            <h3 class="font-bold text-gray-900 text-lg mb-4 dark:text-white">Order Items ({{ $order->items->count() }})</h3>
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex items-center gap-4 py-4 border-b border-gray-100 dark:border-gray-700 last:border-0 last:pb-0">
                    <img src="{{ $item->product ? $item->product->getColorImageUrl($item->color) : asset('placeholder.png') }}" 
                        alt="{{ $item->product_name }}" 
                        class="w-24 h-24 rounded-2xl object-cover bg-gray-50 dark:bg-gray-900 cursor-zoom-in hover:opacity-80 transition shadow-sm border border-gray-200 dark:border-gray-700"
                        @click="$store.imageModal.open('{{ $item->product ? $item->product->getColorImageUrl($item->color) : asset('placeholder.png') }}')"
                        title="Click to enlarge">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h4 class="font-bold text-gray-900 truncate dark:text-white flex items-center gap-2">
                                    {{ $item->product_name }}
                                    @if($item->product && $item->product->sku)
                                        <span class="text-[10px] bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded font-black uppercase tracking-widest border border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700">
                                            CODE: {{ $item->product->sku }}
                                        </span>
                                    @endif
                                </h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <form action="{{ route('admin.order-items.update', $item) }}" method="POST" class="flex items-center">
                                        @csrf @method('PATCH')
                                        <span class="text-xs text-gray-500 mr-2 dark:text-gray-400">Qty:</span>
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" 
                                            class="w-16 h-8 text-xs font-bold border-gray-200 rounded-lg focus:ring-orange-500 py-1 px-2 dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                                        <button type="submit" class="ml-1 px-2 py-1 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition dark:bg-blue-900/20 dark:text-blue-400 flex items-center gap-1 border border-blue-100 dark:border-blue-800" title="Update Quantity">
                                            <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                            <span class="text-[9px] font-bold">Save</span>
                                        </button>
                                    </form>
                                    <span class="text-gray-300 dark:text-gray-600 mx-1">|</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Tk{{ number_format($item->price) }}</span>
                                </div>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @if($item->size)
                                    <span class="text-[9px] bg-orange-50 text-orange-600 px-2 py-0.5 rounded font-black uppercase tracking-wider dark:bg-orange-900/30 dark:text-orange-400">Size: {{ $item->size }}</span>
                                    @endif
                                    @if($item->color)
                                    <span class="inline-flex items-center gap-1.5 text-[9px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded font-black uppercase tracking-wider dark:bg-blue-900/30 dark:text-blue-400">
                                        @if(str_starts_with($item->color, '#'))
                                            <span style="background-color: {{ $item->color }}" class="w-5 h-5 rounded-md border border-gray-300 shadow-sm dark:border-gray-600"></span>
                                        @endif
                                        Color: {{ $item->color }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-black text-gray-900 dark:text-white">Tk{{ number_format($item->total) }}</div>
                                <form action="{{ route('admin.order-items.remove', $item) }}" method="POST" onsubmit="return confirm('Remove this item from order?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 mt-2 p-1 rounded-lg hover:bg-red-50 transition dark:hover:bg-red-900/20" title="Remove Item">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 dark:bg-gray-800 dark:border-gray-700 transition-colors">
            <h3 class="font-bold text-gray-900 text-lg mb-4 dark:text-white">Payment Summary</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                    <span>Subtotal</span>
                    <span class="font-semibold text-gray-900 dark:text-white">Tk{{ number_format($order->subtotal) }}</span>
                </div>
                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                    <span>Shipping Fee</span>
                    <span class="font-semibold text-gray-900 dark:text-white">Tk{{ number_format($order->shipping) }}</span>
                </div>
                @if($order->discount > 0)
                <div class="flex justify-between text-green-600 font-medium dark:text-green-400">
                    <span>Discount {{ $order->promo_code ? "({$order->promo_code})" : '' }}</span>
                    <span>-Tk{{ number_format($order->discount) }}</span>
                </div>
                @endif
                <div class="pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <span class="font-bold text-gray-900 text-base dark:text-white">Total</span>
                    <span class="font-black text-[#FF6A00] text-xl">Tk{{ number_format($order->total) }}</span>
                </div>
            </div>
            
            <div class="mt-6 p-4 rounded-xl bg-gray-50 border border-gray-100 flex flex-wrap justify-between items-center gap-4 dark:bg-gray-900 dark:border-gray-700">
                <div>
                    <p class="text-xs text-gray-500 mb-1 dark:text-gray-400">Payment Method</p>
                    <p class="font-bold text-gray-900 uppercase flex items-center gap-2 dark:text-white">
                        @if($order->payment_method === 'cod') <i data-lucide="banknote" class="w-4 h-4 text-green-500"></i> Cash on Delivery
                        @elseif($order->payment_method === 'bkash') <i data-lucide="smartphone" class="w-4 h-4 text-pink-500"></i> bKash 
                        @else <i data-lucide="smartphone" class="w-4 h-4 text-red-500"></i> Nagad 
                        @endif
                    </p>
                    @if($order->transaction_id)
                        <div class="mt-2 text-xs">
                            <span class="text-gray-500 dark:text-gray-400">TrxID:</span> 
                            <span class="font-black text-orange-600 select-all tracking-wider dark:text-orange-400">{{ $order->transaction_id }}</span>
                        </div>
                    @endif
                    @if($order->payment_phone)
                        <div class="mt-1 text-xs">
                            <span class="text-gray-500 dark:text-gray-400">Paid from:</span> 
                            <span class="font-bold text-gray-900 dark:text-white">{{ $order->payment_phone }}</span>
                        </div>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 mb-1 dark:text-gray-400">Payment Status</p>
                    <span class="px-2.5 py-1 rounded-md text-xs font-bold {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                        {{ strtoupper($order->payment_status) }}
                    </span>
                    <form action="{{ route('admin.orders.update-payment-status', $order) }}" method="POST" class="mt-2 text-center">
                        @csrf @method('PATCH')
                        <button type="submit" name="payment_status" value="{{ $order->payment_status === 'paid' ? 'pending' : 'paid' }}" 
                            class="text-[10px] font-bold text-blue-600 hover:underline dark:text-blue-400">
                            Mark as {{ $order->payment_status === 'paid' ? 'Pending' : 'Paid' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Customer Info -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 dark:bg-gray-800 dark:border-gray-700 transition-colors">
            <h3 class="font-bold text-gray-900 text-lg mb-4 dark:text-white">Customer Details</h3>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-orange-100 text-orange-600 font-bold flex items-center justify-center text-xl dark:bg-orange-900/30 dark:text-orange-400">
                    {{ substr($order->name, 0, 1) }}
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white">{{ $order->name }}</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $order->user ? 'Customer since ' . $order->user->created_at->format('M Y') : 'Guest Customer' }}
                    </p>
                </div>
            </div>
            <div class="space-y-3 pt-4 border-t border-gray-100 text-sm dark:border-gray-700">
                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                    <i data-lucide="mail" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
                    {{ $order->email }}
                </div>
                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                    <i data-lucide="phone" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
                    {{ $order->phone }}
                </div>
                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 pt-1">
                    <i data-lucide="map-pin" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
                    <span class="font-bold text-orange-600 dark:text-orange-400">{{ $order->thana }}, {{ $order->city }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 overflow-hidden dark:bg-gray-800 dark:border-gray-700 transition-colors">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-900 text-lg dark:text-white">Shipping Address</h3>
                <button onclick="copyShippingAddress(this)" class="flex items-center gap-1.5 text-xs font-bold text-orange-600 hover:text-orange-700 bg-orange-50 px-2 py-1 rounded transition dark:bg-orange-900/20 dark:text-orange-400 dark:hover:text-orange-300">
                    <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                    Copy
                </button>
            </div>
            
            <div id="shipping-address-content" class="text-sm text-gray-600 leading-relaxed bg-gray-50 p-4 rounded-xl border border-gray-100 relative dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                <p class="font-bold text-gray-900 mb-1 dark:text-white">{{ $order->name }}</p>
                <p>{{ $order->address }}</p>
                <p><strong>থানা:</strong> {{ $order->thana }}</p>
                <p><strong>জেলা:</strong> {{ $order->city }}</p>
                <p><strong>বিভাগ:</strong> {{ $order->state }}</p>
                @if($order->postal_code)
                <p><strong>Postal Code:</strong> {{ $order->postal_code }}</p>
                @endif
                <p class="mt-1"><strong>Phone:</strong> {{ $order->phone }}</p>
            </div>

            @if($order->notes)
            <div class="mt-4">
                <h4 class="font-semibold text-gray-900 text-sm mb-2 dark:text-white">অর্ডার নোট</h4>
                <p class="text-sm text-amber-700 bg-amber-50 p-3 rounded-xl border border-amber-100 italic dark:bg-amber-900/20 dark:border-amber-900/30 dark:text-amber-400">
                    {{ $order->notes }}
                </p>
            </div>
            @endif
        </div>

        </div>
    </div>
</div>
    </div>
</div>

<script>
function copyShippingAddress(btn) {
    const text = document.getElementById('shipping-address-content').innerText;
    
    navigator.clipboard.writeText(text).then(() => {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i data-lucide="check" class="w-3.5 h-3.5 inline-block mr-1"></i> Copied!';
        if (window.lucide) lucide.createIcons();

        setTimeout(() => {
            btn.innerHTML = originalHtml;
            if (window.lucide) lucide.createIcons();
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}
</script>
@endsection