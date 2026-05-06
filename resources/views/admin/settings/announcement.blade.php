@extends('layouts.admin')

@section('title', 'Announcement Bar Settings')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Announcement Bar</h1>
        <p class="text-sm text-gray-500 mt-1 dark:text-gray-400">Manage the scrolling announcement ticker on the top of your store.</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 dark:bg-gray-800 dark:border-gray-700">
    <form action="{{ route('admin.settings.announcement.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 border-b pb-2 dark:text-white dark:border-gray-700">📢 Announcement Bar Settings</h3>
            
            <div>
                <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Announcement Text</label>
                <input type="text" name="announcement_text"
                    value="{{ old('announcement_text', $settings->announcement_text ?? '') }}"
                    placeholder="e.g. 🎉 Special Sale! Free Shipping on orders over Tk1000"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
            </div>
            
            <div>
                <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Scroll Speed</label>
                <select name="announcement_speed" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none bg-white dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    <option value="15" {{ ($settings->announcement_speed ?? 30) == 15 ? 'selected' : '' }}>🐇 Fast (15s)</option>
                    <option value="25" {{ ($settings->announcement_speed ?? 30) == 25 ? 'selected' : '' }}>🏃 Medium-Fast (25s)</option>
                    <option value="30" {{ ($settings->announcement_speed ?? 30) == 30 ? 'selected' : '' }}>🚶 Normal (30s)</option>
                    <option value="45" {{ ($settings->announcement_speed ?? 30) == 45 ? 'selected' : '' }}>🐢 Slow (45s)</option>
                    <option value="60" {{ ($settings->announcement_speed ?? 30) == 60 ? 'selected' : '' }}>🐌 Very Slow (60s)</option>
                </select>
            </div>
        </div>

        <div class="pt-8 mt-6 border-t border-gray-100 flex justify-end dark:border-gray-700">
            <button type="submit" class="admin-primary-btn px-8 py-2.5 rounded-xl text-sm font-semibold">Save Announcement</button>
        </div>
    </form>
</div>
@endsection
