@extends('layouts.admin')
@section('title', 'Customers List')
@section('content')

<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 flex-1 w-full">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white whitespace-nowrap">Customers ({{ $customers->total() }})</h2>
        <form action="{{ route('admin.customers.index') }}" method="GET" class="w-full max-w-sm">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 group-focus-within:text-orange-500 transition-colors"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email or phone..." 
                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50 transition-all">
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-900/50 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-4 font-semibold">Customer Name</th>
                    <th class="px-6 py-4 font-semibold">Email</th>
                    <th class="px-6 py-4 font-semibold">Joined At</th>
                    <th class="px-6 py-4 font-semibold text-center">Last IP</th>
                    <th class="px-6 py-4 font-semibold text-center">Total Orders</th>
                    <th class="px-6 py-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($customers as $customer)
                <tr class="hover:bg-gray-50 transition dark:hover:bg-gray-900/50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 font-bold flex items-center justify-center text-sm dark:bg-orange-900/30 dark:text-orange-400 flex-shrink-0">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                            <span class="font-bold text-gray-900 dark:text-white">{{ $customer->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $customer->email }}</td>
                    <td class="px-6 py-4">{{ $customer->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($customer->last_ip)
                            <span class="font-mono text-[10px] bg-gray-50 px-2 py-1 rounded dark:bg-gray-700 dark:text-gray-300">{{ $customer->last_ip }}</span>
                        @else
                            <span class="text-gray-300">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center font-bold text-gray-900 dark:text-white">{{ $customer->orders_count }}</td>
                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                        <a href="{{ route('admin.customers.show', $customer) }}" class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition shadow-sm border border-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-900/30 dark:hover:bg-blue-600 dark:hover:text-white" title="View Order History">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </a>
                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('⚠️ Are you sure you want to delete this customer? This action cannot be undone.')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition shadow-sm border border-red-100 dark:bg-red-900/20 dark:text-red-400 dark:border-red-900/30 dark:hover:bg-red-600 dark:hover:text-white">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach

                @if(isset($guestResults) && $guestResults->isNotEmpty())
                    <tr class="bg-gray-50/50 dark:bg-gray-900/30">
                        <td colspan="5" class="px-6 py-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Guest Customers (No Account Found)</td>
                    </tr>
                    @foreach($guestResults as $guest)
                    <tr class="hover:bg-gray-50 transition border-l-4 border-orange-400 dark:hover:bg-gray-900/50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-400 font-bold flex items-center justify-center text-sm dark:bg-gray-700 dark:text-gray-500 flex-shrink-0">
                                    <i data-lucide="user-minus" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <span class="font-bold text-gray-600 dark:text-gray-300">{{ $guest->name }}</span>
                                    <span class="ml-2 px-1.5 py-0.5 bg-gray-100 text-gray-500 text-[8px] rounded uppercase font-black dark:bg-gray-700 dark:text-gray-500">Guest</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-400 dark:text-gray-500italic">{{ $guest->phone }}</td>
                        <td class="px-6 py-4 text-gray-400">{{ $guest->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-center font-bold text-orange-600">{{ $guest->orders_count }}</td>
                        <td class="px-6 py-4 text-right flex justify-end gap-2">
                            <a href="{{ route('admin.customers.show', 'guest') }}?phone={{ $guest->phone }}" class="p-2 bg-orange-50 text-orange-600 rounded-xl hover:bg-orange-600 hover:text-white transition shadow-sm border border-orange-100 dark:bg-orange-900/20 dark:text-orange-400 dark:border-orange-900/30 dark:hover:bg-orange-600 dark:hover:text-white" title="View Guest History">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100 dark:border-gray-700">
        {{ $customers->links() }}
    </div>
</div>

@endsection
