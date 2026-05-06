@extends('layouts.admin')
@section('title', 'Manage Delivery Areas')
@section('content')

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Delivery Areas</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Manage custom shipping areas and their charges.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Add/Edit Form -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-6 shadow-sm rounded-2xl h-fit">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="map-pin" class="w-5 h-5 text-green-500"></i>
                Add New Area
            </h3>
            <form action="{{ route('admin.delivery-areas.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1">Area Name</label>
                    <input type="text" name="name" required placeholder="e.g. Inside Dhaka City" 
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1">Shipping Charge (Tk)</label>
                    <input type="number" step="0.01" name="charge" required placeholder="e.g. 70" 
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="0" 
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                </div>
                <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-3 text-sm transition rounded-xl flex items-center justify-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Delivery Area
                </button>
            </form>
        </div>
    </div>

    <!-- List -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Area Name</th>
                            <th class="px-6 py-4 font-semibold">Charge (Tk)</th>
                            <th class="px-6 py-4 font-semibold">Sort</th>
                            <th class="px-6 py-4 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($areas as $area)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 dark:text-white">{{ $area->name }}</div>
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-green-600 dark:text-green-400">Tk{{ number_format($area->charge, 0) }}</td>
                            <td class="px-6 py-4 text-xs font-medium text-gray-400">{{ $area->sort_order }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Edit Trigger (Simplified for now) -->
                                    <form action="{{ route('admin.delivery-areas.destroy', $area) }}" method="POST" onsubmit="return confirm('Delete this area?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-xl transition dark:text-red-400 dark:hover:bg-red-900/20" title="Delete">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">No custom delivery areas added yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
