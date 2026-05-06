@extends('layouts.admin')
@section('title', 'Site Settings')
@section('content')

<!-- Header Section -->
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Site Settings</h2>
    <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 flex items-center gap-1 group">
        <i data-lucide="layout-dashboard" class="w-4 h-4 group-hover:scale-110 transition-transform"></i> Dashboard
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 w-full mx-auto dark:bg-gray-800 dark:border-gray-700">
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- General Info Section -->
                <div class="bg-gray-50/50 p-5 rounded-2xl border border-gray-100 dark:bg-gray-900/50 dark:border-gray-700 transition-all hover:shadow-sm">
                    <h3 class="text-md font-bold text-gray-900 mb-5 flex items-center gap-2 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3">
                        <i data-lucide="info" class="w-5 h-5 text-orange-500"></i> General Info
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Site Name</label>
                            <input type="text" name="site_name" value="{{ old('site_name', $settings->site_name ?? 'SmartLookBD') }}" required 
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Contact Email</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $settings->contact_email ?? '') }}" placeholder="support@SmartLookBD.com"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Contact Phone Number</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings->contact_phone ?? '') }}" placeholder="+880 1700-000000"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">WhatsApp Number (For Floating Button)</label>
                            <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings->whatsapp_number ?? '') }}" placeholder="017XXXXXXXX"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#25D366] focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-[#25D366]/50">
                            <p class="text-[10px] text-gray-400 mt-1 dark:text-gray-500 italic">This number will be used for the floating WhatsApp chat button.</p>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-gray-100 dark:bg-gray-800 dark:border-gray-700" x-data="{ active: {{ ($settings->is_size_chart_active ?? true) ? 'true' : 'false' }} }">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">Enable Size Chart</h4>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">Enable/Disable product size charts globally.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_size_chart_active" value="0">
                                <input type="checkbox" name="is_size_chart_active" value="1" class="sr-only peer" x-model="active">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 dark:peer-focus:ring-orange-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-orange-500"></div>
                                <span class="ml-2 text-xs font-bold transition-colors duration-200" :class="active ? 'text-orange-600' : 'text-gray-400'" x-text="active ? 'Enabled' : 'Disabled'">Enabled</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Payment Numbers Section -->
                <div class="bg-blue-50/30 p-5 rounded-2xl border border-blue-100 dark:bg-blue-900/10 dark:border-blue-900/30 transition-all hover:shadow-sm">
                    <h3 class="text-md font-bold text-gray-900 mb-5 flex items-center gap-2 dark:text-white border-b border-blue-100 dark:border-blue-800/50 pb-3">
                        <i data-lucide="smartphone" class="w-5 h-5 text-blue-500"></i> Mobile Payment Numbers
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">bKash Personal Number</label>
                            <input type="text" name="bkash_number" value="{{ old('bkash_number', $settings->bkash_number ?? '') }}" placeholder="01XXXXXXXXX"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                            <p class="text-[10px] text-gray-400 mt-1 dark:text-gray-500 italic">Leave empty to disable bKash instructions at checkout.</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Nagad Personal Number</label>
                            <input type="text" name="nagad_number" value="{{ old('nagad_number', $settings->nagad_number ?? '') }}" placeholder="01XXXXXXXXX"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                            <p class="text-[10px] text-gray-400 mt-1 dark:text-gray-500 italic">Leave empty to disable Nagad instructions at checkout.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Social Links Section -->
                <div class="bg-indigo-50/30 p-5 rounded-2xl border border-indigo-100 dark:bg-indigo-900/10 dark:border-indigo-900/30 transition-all hover:shadow-sm">
                    <h3 class="text-md font-bold text-gray-900 mb-5 flex items-center gap-2 dark:text-white border-b border-indigo-100 dark:border-indigo-800/50 pb-3">
                        <i data-lucide="share-2" class="w-5 h-5 text-indigo-500"></i> Social & Marketing
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Facebook Page Link</label>
                            <input type="url" name="facebook_page_link" value="{{ old('facebook_page_link', $settings->facebook_page_link ?? '') }}" placeholder="https://facebook.com/yourpage"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Instagram Link</label>
                            <input type="url" name="instagram_link" value="{{ old('instagram_link', $settings->instagram_link ?? '') }}" placeholder="https://instagram.com/yourprofile"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Twitter (X) Link</label>
                            <input type="url" name="twitter_link" value="{{ old('twitter_link', $settings->twitter_link ?? '') }}" placeholder="https://twitter.com/yourprofile"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">TikTok Link</label>
                            <input type="url" name="tiktok_link" value="{{ old('tiktok_link', $settings->tiktok_link ?? '') }}" placeholder="https://tiktok.com/@yourprofile"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                        </div>
                    </div>
                </div>

                <!-- Location & Address -->
                <div class="bg-gray-50/50 p-5 rounded-2xl border border-gray-100 dark:bg-gray-900/50 dark:border-gray-700 transition-all hover:shadow-sm">
                    <h3 class="text-md font-bold text-gray-900 mb-5 flex items-center gap-2 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3">
                        <i data-lucide="map-pin" class="w-5 h-5 text-red-500"></i> Location & Address
                    </h3>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Company Full Address</label>
                        <textarea name="contact_address" rows="5" placeholder="123 Shopping Ave, Dhaka 1200, Bangladesh"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none resize-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">{{ old('contact_address', $settings->contact_address ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button Footer -->
        <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end">
            <button type="submit" class="w-full sm:w-auto admin-primary-btn px-10 py-3.5 rounded-xl text-sm font-bold shadow-lg shadow-orange-500/20 flex items-center justify-center gap-2 transition-all active:scale-95">
                <i data-lucide="save" class="w-5 h-5"></i> Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
