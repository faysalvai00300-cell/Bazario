@extends('layouts.admin')
@section('title', 'Promo Codes Management')
@section('content')

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 flex-1 w-full">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white whitespace-nowrap">Promo Codes</h2>
        <form action="{{ route('admin.promo-codes.index') }}" method="GET" class="w-full max-w-sm">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 group-focus-within:text-orange-500 transition-colors"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by code..." 
                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50 transition-all">
            </div>
        </form>
    </div>
    <a href="{{ route('admin.promo-codes.create') }}" class="btn-primary px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
        + Add New Code
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-900/50 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-4 font-semibold">Code</th>
                    <th class="px-6 py-4 font-semibold">Type & Value</th>
                    <th class="px-6 py-4 font-semibold">Min Order</th>
                    <th class="px-6 py-4 font-semibold">Usage</th>
                    <th class="px-6 py-4 font-semibold">Valid Until</th>
                    <th class="px-6 py-4 font-semibold text-center">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($promoCodes as $code)
                <tr class="hover:bg-gray-50 transition dark:hover:bg-gray-900/50">
                    <td class="px-6 py-4">
                        <span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 font-mono font-bold rounded border border-gray-200 tracking-wider dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700">
                            {{ $code->code }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                        @if($code->type === 'percentage')
                            {{ $code->value }}% OFF
                            @if($code->max_discount)
                            <span class="block text-xs text-gray-400 font-normal dark:text-gray-500">Up to {{ $code->max_discount }}Tk</span>
                            @endif
                        @else
                            Tk{{ number_format($code->value) }} OFF
                        @endif
                    </td>
                    <td class="px-6 py-4 font-medium">{{ $code->min_order ? 'Tk' . number_format($code->min_order) : 'None' }}</td>
                    <td class="px-6 py-4">
                        <span class="text-gray-900 font-semibold dark:text-white">{{ $code->used_count }}</span>
                        <span class="text-gray-400 text-xs dark:text-gray-500">/ {!! $code->usage_limit ?? '&infin;' !!}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($code->expires_at)
                            @if($code->isExpired())
                                <span class="text-red-500 text-xs font-bold bg-red-50 px-2 py-1 rounded dark:bg-red-900/20 dark:text-red-400">Expired</span>
                            @else
                                {{ $code->expires_at->format('M d, Y') }}
                            @endif
                        @else
                            <span class="text-xs text-gray-400 font-medium dark:text-gray-500">Never</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="w-3 h-3 rounded-full inline-block {{ $code->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.promo-codes.edit', $code) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition dark:text-blue-400 dark:hover:bg-blue-900/30" title="Edit">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.promo-codes.destroy', $code) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this code?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition dark:text-red-400 dark:hover:bg-red-900/30" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                @if($promoCodes->isEmpty())
                <tr><td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">No promo codes found.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    @if($promoCodes->hasPages())
    <div class="p-4 border-t border-gray-100 dark:border-gray-700">
        {{ $promoCodes->links() }}
    </div>
    @endif
</div>

@endsection
