@extends('layouts.admin')
@section('title', isset($promoCode) ? 'Edit Promo Code' : 'Create Promo Code')
@section('content')

<div class="mb-6 flex items-center justify-between">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ isset($promoCode) ? 'Edit Promo Code' : 'Create Promo Code' }}</h2>
    <a href="{{ route('admin.promo-codes.index') }}" class="text-gray-500 hover:text-gray-900 text-sm font-medium dark:text-gray-400 dark:hover:text-gray-300 flex items-center gap-1 group">
        <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i> Back to Codes
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 max-w-3xl dark:bg-gray-800 dark:border-gray-700">
    <form action="{{ isset($promoCode) ? route('admin.promo-codes.update', $promoCode) : route('admin.promo-codes.store') }}" method="POST">
        @csrf
        @if(isset($promoCode)) @method('PUT') @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-300">Code <span class="text-red-500">*</span></label>
                <input type="text" name="code" value="{{ old('code', $promoCode->code ?? '') }}" 
                    class="w-full rounded-xl border-gray-200 uppercase font-mono tracking-widest focus:border-orange-500 focus:ring-orange-500 @error('code') border-red-500 @enderror dark:bg-gray-900 dark:border-gray-700 dark:text-white" 
                    placeholder="e.g. SUMMER50" required>
                @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-300">Discount Type <span class="text-red-500">*</span></label>
                <select name="type" class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    <option value="percentage" {{ old('type', $promoCode->type ?? '') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                    <option value="fixed" {{ old('type', $promoCode->type ?? '') === 'fixed' ? 'selected' : '' }}>Fixed Amount (Tk)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-300">Discount Value <span class="text-red-500">*</span></label>
                <input type="number" name="value" value="{{ old('value', $promoCode->value ?? '') }}" step="0.01" 
                    class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 @error('value') border-red-500 @enderror dark:bg-gray-900 dark:border-gray-700 dark:text-white" required>
                @error('value')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-300">Minimum Order Amount (Tk)</label>
                <input type="number" name="min_order" value="{{ old('min_order', $promoCode->min_order ?? '') }}" step="0.01"
                    class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-300">Maximum Discount (Tk)</label>
                <input type="number" name="max_discount" value="{{ old('max_discount', $promoCode->max_discount ?? '') }}" step="0.01"
                    class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                <p class="text-gray-400 text-xs mt-1 dark:text-gray-500">Leave empty for no limit (applicable only for percentage)</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-300">Usage Limit</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit', $promoCode->usage_limit ?? '') }}"
                    class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                <p class="text-gray-400 text-xs mt-1 dark:text-gray-500">Total times this code can be used across the store</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-300">Expiry Date</label>
                <input type="date" name="expires_at" value="{{ old('expires_at', isset($promoCode) && $promoCode->expires_at ? $promoCode->expires_at->format('Y-m-d') : '') }}"
                    class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white">
            </div>

            <div class="md:col-span-2 flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-100 dark:bg-gray-900 dark:border-gray-700">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $promoCode->is_active ?? true) ? 'checked' : '' }} 
                    class="w-5 h-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500 dark:bg-gray-800 dark:border-gray-700">
                <label for="is_active" class="font-semibold text-gray-800 cursor-pointer dark:text-gray-300">Activate Promo Code</label>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-8">
            <a href="{{ route('admin.promo-codes.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 transition dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-900">Cancel</a>
            <button type="submit" class="px-8 py-2.5 rounded-xl font-black transition-all hover:scale-[1.02] active:scale-95 shadow-lg" 
                style="background-color: #FF6A00 !important; color: #FFFFFF !important;">
                {{ isset($promoCode) ? 'Update Code' : 'Create Code' }}
            </button>
        </div>
    </form>
</div>

@endsection
