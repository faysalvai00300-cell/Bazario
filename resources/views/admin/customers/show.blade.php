@extends('layouts.admin')
@section('title', 'Customer Details - ' . $customer->name)
@section('content')

    <div class="mb-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-6">
            <a href="{{ route('admin.customers.index') }}" class="p-2 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                <i data-lucide="arrow-left" class="w-5 h-5 text-gray-600"></i>
            </a>
            <div>
                <h2 class="text-xl md:text-2xl font-black text-gray-900">Customer Details</h2>
                <p class="text-xs md:text-sm text-gray-500">Viewing comprehensive data for {{ $customer->name }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Customer Info Card -->
            <div class="lg:col-span-1 space-y-6">
                <div
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex flex-col items-center text-center mb-6">
                        <div
                            class="w-24 h-24 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center mb-4 ring-8 ring-orange-50/50 border-2 border-orange-100 shadow-sm flex-shrink-0 aspect-square mx-auto">
                            <i data-lucide="user" class="w-10 h-10"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $customer->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $customer->email }}</p>
                        <span
                            class="mt-3 px-3 py-1 bg-green-100 text-green-600 text-[10px] font-black uppercase rounded-full tracking-wider dark:bg-green-900/20 dark:text-green-400">
                            Verified Member
                        </span>
                    </div>

                    <div class="space-y-4 border-t border-gray-50 pt-6 dark:border-gray-700">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Phone:</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ $customer->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Joined:</span>
                            <span
                                class="font-bold text-gray-900 dark:text-white">{{ $customer->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Total Orders:</span>
                            <span class="font-bold text-orange-600">{{ $customer->orders->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Total Spent:</span>
                            <span
                                class="font-bold text-green-600">৳{{ number_format($customer->orders->where('status', 'delivered')->sum('total')) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity/Notes -->
                <div
                    class="bg-orange-50 rounded-2xl p-5 border border-orange-100 dark:bg-orange-900/10 dark:border-orange-900/20 mb-6">
                    <h4 class="text-xs font-black text-orange-800 uppercase tracking-widest mb-3 dark:text-orange-400">
                        Status</h4>
                    <p class="text-xs text-orange-700 leading-relaxed dark:text-orange-300/80">
                        @if(isset($customer->is_guest) && $customer->is_guest)
                            This is a <span class="font-bold uppercase text-[9px]">Guest Customer</span>. They have not
                            registered an account yet but have placed orders.
                        @else
                            This is a <span class="font-bold uppercase text-[9px]">Registered Member</span>.
                            The customer has <span
                                class="font-bold">{{ $customer->orders->where('status', 'cancelled')->count() }}</span>
                            cancelled orders.
                        @endif
                    </p>
                </div>
            </div>

            <!-- Orders History -->
            <div class="lg:col-span-2">
                <div
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between dark:border-gray-700">
                        <h3 class="font-bold text-gray-900 dark:text-white">Order History</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                            <thead
                                class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-900/50 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 md:px-6 py-4 font-semibold">Order ID</th>
                                    <th class="px-4 md:px-6 py-4 font-semibold">Date</th>
                                    <th class="px-4 md:px-6 py-4 font-semibold">Status</th>
                                    <th class="px-4 md:px-6 py-4 font-semibold text-right">Amount</th>
                                    <th class="px-4 md:px-6 py-4 font-semibold text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($customer->orders as $order)
                                    <tr class="hover:bg-gray-50 transition dark:hover:bg-gray-900/50">
                                        <td class="px-4 md:px-6 py-4 font-bold text-gray-900 dark:text-white text-xs md:text-sm">
                                            #{{ $order->order_number }}</td>
                                        <td class="px-4 md:px-6 py-4 text-[10px] md:text-xs tracking-tighter md:tracking-normal">{{ $order->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                                {{ $order->status === 'pending' ? 'bg-orange-100 text-orange-600' : '' }}
                                                {{ $order->status === 'confirmed' ? 'bg-cyan-100 text-cyan-600' : '' }}
                                                {{ $order->status === 'delivered' ? 'bg-green-100 text-green-600' : '' }}
                                                {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-600' : '' }}
                                                {{ !in_array($order->status, ['pending', 'confirmed', 'delivered', 'cancelled']) ? 'bg-blue-100 text-blue-600' : '' }}">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 md:px-6 py-4 text-right font-bold text-gray-900 dark:text-white text-xs md:text-sm whitespace-nowrap">
                                            ৳{{ number_format($order->total) }}</td>
                                        <td class="px-4 md:px-6 py-4 text-right">
                                            <a href="{{ route('admin.orders.show', $order) }}"
                                                class="text-orange-600 hover:text-orange-700 font-black text-[10px] md:text-xs uppercase tracking-tight whitespace-nowrap">
                                                View Order
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <i data-lucide="shopping-bag" class="w-10 h-10 text-gray-200 mb-2"></i>
                                                <p>No orders found for this customer.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messaging Section (Moved to Bottom) -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Send Message Form -->
            @if(!(isset($customer->is_guest) && $customer->is_guest))
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
                <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6 dark:text-white flex items-center gap-2">
                    <i data-lucide="send" class="w-4 h-4 text-orange-500"></i> Send New Message
                </h4>
                <form action="{{ route('admin.customers.message', $customer->id) }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <textarea name="message" rows="5" required
                            class="w-full px-5 py-4 rounded-2xl bg-gray-50 border border-gray-100 text-sm focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all dark:bg-gray-900 dark:border-gray-700 dark:text-white"
                            placeholder="Write your message to the customer..."></textarea>
                    </div>
                    <div class="mb-6 flex items-center gap-3 bg-orange-50/50 p-4 rounded-xl border border-orange-100/50">
                        <input type="checkbox" name="has_gift" id="has_gift" value="1" class="w-5 h-5 text-orange-500 border-gray-300 rounded-md focus:ring-orange-500">
                        <label for="has_gift" class="text-xs font-black text-orange-800 uppercase tracking-wider flex items-center gap-2 cursor-pointer">
                            <i data-lucide="gift" class="w-4 h-4"></i> Send with Gift (Confetti Animation)
                        </label>
                    </div>
                    <button type="submit" 
                        class="w-full py-4 bg-gray-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-[#FF6A00] transition-all duration-300 shadow-xl shadow-gray-200 hover:shadow-orange-200">
                        Send Message Now
                    </button>
                </form>
            </div>
            @endif

            <!-- Message History -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
                <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6 dark:text-white flex items-center gap-2">
                    <i data-lucide="history" class="w-4 h-4 text-orange-500"></i> Communication History
                </h4>
                <div class="space-y-4 max-h-[450px] overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($customer->messages as $msg)
                    <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100 dark:bg-gray-900 dark:border-gray-700 relative group transition-all hover:border-orange-100">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[10px] font-black text-orange-500 uppercase tracking-widest flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                Sent by Admin
                                @if($msg->has_gift)
                                    <i data-lucide="gift" class="w-3 h-3"></i>
                                @endif
                            </span>
                            <div class="flex items-center gap-3">
                                <span class="text-[9px] text-gray-400 font-bold tracking-tight">{{ $msg->created_at->format('d M, Y • h:i A') }}</span>
                                <form action="{{ route('admin.customers.message.destroy', $msg->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100 focus:opacity-100" title="Delete Message">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <p class="text-[13px] text-gray-700 dark:text-gray-300 leading-relaxed font-medium">{{ $msg->message }}</p>
                    </div>
                    @empty
                    <div class="py-12 text-center text-gray-400">
                        <i data-lucide="message-square" class="w-12 h-12 mx-auto mb-3 opacity-20"></i>
                        <p class="text-xs font-bold uppercase tracking-widest">No message history yet</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection