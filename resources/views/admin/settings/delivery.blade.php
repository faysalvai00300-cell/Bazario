@extends('layouts.admin')
@section('title', 'Delivery Charges - Site Settings')
@section('content')

<div class="mb-6">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Delivery Charges & Areas</h2>
</div>

@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
    <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
    {{ session('success') }}
</div>
@endif

<!-- Top Section: Side-by-Side Configuration -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    
    <!-- Left Card: Global Settings (Free Delivery Threshold) -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 dark:bg-gray-800 dark:border-gray-700 transition-colors">
        <form action="{{ route('admin.delivery-charges.update') }}" method="POST" class="space-y-6">
            @csrf
            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2 border-b pb-3 dark:border-gray-700">
                <i data-lucide="settings-2" class="w-5 h-5 text-orange-500"></i>
                Global Settings
            </h3>
            
            <input type="hidden" name="delivery_charge_inside" value="{{ $settings->delivery_charge_inside ?? 70 }}">
            <input type="hidden" name="delivery_charge_outside" value="{{ $settings->delivery_charge_outside ?? 130 }}">
            <input type="hidden" name="delivery_charge" value="0">

            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 mb-1 block">Free Delivery Threshold (Tk)</label>
                <div class="relative mb-6">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Tk</span>
                    <input type="number" name="free_delivery_threshold" value="{{ old('free_delivery_threshold', $settings->free_delivery_threshold ?? 1000) }}" required 
                        class="w-full border border-gray-200 rounded-lg pl-7 pr-3 py-2 text-sm focus:ring-1 focus:ring-orange-500 outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                </div>
                
                <div class="p-4 bg-gray-50 border border-gray-100 rounded-xl hover:bg-orange-50/50 transition dark:bg-gray-900/50 dark:border-gray-700">
                    <label for="is_free_delivery_active" class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_free_delivery_active" id="is_free_delivery_active" value="1" {{ ($settings->is_free_delivery_active ?? false) ? 'checked' : '' }} class="w-6 h-6 cursor-pointer accent-orange-500">
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Enable Free Delivery Status</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl text-sm transition shadow-sm">
                Save Settings
            </button>
        </form>
    </div>

    <!-- Right Card: Quick Add New Area Form -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 dark:bg-gray-800 dark:border-gray-700 transition-colors">
        <form action="{{ route('admin.delivery-areas.store') }}" method="POST" class="space-y-4">
            @csrf
            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2 border-b pb-3 dark:border-gray-700">
                <i data-lucide="plus-circle" class="w-5 h-5 text-green-500"></i>
                Add New Delivery Area
            </h3>
            
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase text-gray-400 mb-1 block">Area Name</label>
                    <input type="text" name="name" required placeholder="e.g. Dhaka Sub-Area" 
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-orange-500 outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-gray-400 mb-1 block">Charge (Tk)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Tk</span>
                        <input type="number" name="charge" required placeholder="100" 
                            class="w-full border border-gray-200 rounded-lg pl-7 pr-3 py-2 text-sm focus:ring-1 focus:ring-orange-500 outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-3 rounded-xl text-sm transition shadow-sm mt-2">
                Add Area to List
            </button>
        </form>
    </div>

</div>

<!-- Bottom Section: Detailed Area List -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700 transition-colors">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
        <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <i data-lucide="list" class="w-4 h-4 text-orange-500"></i>
            Active Custom Delivery Areas
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900/50 text-[10px] uppercase text-gray-400 font-bold border-b dark:border-gray-700">
                <tr>
                    <th class="px-6 py-4">Area Name</th>
                    <th class="px-6 py-4">Charge</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($deliveryAreas as $area)
                <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-700/20 transition">
                    <td class="px-6 py-4 font-semibold text-gray-800 dark:text-gray-200">{{ $area->name }}</td>
                    <td class="px-6 py-4 font-mono font-bold text-orange-600 dark:text-orange-400">Tk{{ number_format($area->charge, 0) }}</td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.delivery-areas.destroy', $area) }}" method="POST" onsubmit="return confirm('Delete this area?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition dark:hover:bg-red-900/20">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">No custom delivery areas added yet. Use the form on the right to add one.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
