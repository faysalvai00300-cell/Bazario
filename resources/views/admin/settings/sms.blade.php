@extends('layouts.admin')
@section('title', 'API Gateways (SMS & Email)')
@section('content')

<div class="mb-6 flex items-center justify-between">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white">API Gateways (SMS & Email)</h2>
</div>

<form action="{{ route('admin.settings.sms.update') }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        
        <!-- SMS Configuration Box -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 dark:bg-gray-800 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-800 mb-5 pb-3 border-b border-gray-100 dark:text-white dark:border-gray-700 flex items-center gap-2">
                <i data-lucide="message-square" class="w-5 h-5 text-orange-500"></i> SMS Settings
            </h3>
            
            <div class="space-y-5">
                <label class="flex gap-4 p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition items-start dark:border-gray-700 dark:hover:bg-gray-700/50">
                    <input type="hidden" name="is_sms_active" value="0">
                    <input type="checkbox" name="is_sms_active" value="1" {{ old('is_sms_active', $settings->is_sms_active ?? false) ? 'checked' : '' }} 
                        class="rounded text-green-500 focus:ring-green-500 w-5 h-5 mt-0.5 dark:bg-gray-900 dark:border-gray-700 transition">
                    <div>
                        <span class="text-sm font-bold text-gray-900 block dark:text-white">Enable SMS Notifications</span>
                        <span class="text-xs text-gray-500 mt-1 block dark:text-gray-400">Send real SMS for login OTPs. If off, OTPs are logged silently.</span>
                    </div>
                </label>

                <div>
                    <label class="text-sm font-bold text-gray-700 mb-2 block dark:text-gray-300">Base API URL</label>
                    <input type="url" name="sms_api_url" value="{{ old('sms_api_url', $settings->sms_api_url ?? '') }}" placeholder="e.g. http://bulksmsbd.net/api/smsapi?api_key=[KEY]&number=[TO]&senderid=[SENDER]&message=[MESSAGE]"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#FF6A00] focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                    <p class="text-[10px] sm:text-xs text-gray-400 mt-2 block font-medium">Use markers in the URL: <br>
                        <code class="text-black bg-gray-100 px-1 rounded dark:text-white dark:bg-gray-700">[USER]</code> = Email/Username,
                        <code class="text-black bg-gray-100 px-1 rounded dark:text-white dark:bg-gray-700">[KEY]</code> = API Key, <br>
                        <code class="text-black bg-gray-100 px-1 rounded dark:text-white dark:bg-gray-700">[SENDER]</code> = Sender ID,
                        <code class="text-black bg-gray-100 px-1 rounded dark:text-white dark:bg-gray-700">[TO]</code> = Number,
                        <code class="text-black bg-gray-100 px-1 rounded dark:text-white dark:bg-gray-700">[MESSAGE]</code> = SMS.
                    </p>
                </div>

                <div>
                    <label class="text-sm font-bold text-gray-700 mb-2 block dark:text-gray-300">SMS Username / Email</label>
                    <input type="text" name="sms_username" value="{{ old('sms_username', $settings->sms_username ?? '') }}" placeholder="Enter MiMSMS Email"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#FF6A00] focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                </div>
                
                <div>
                    <label class="text-sm font-bold text-gray-700 mb-2 block dark:text-gray-300">API Key / Token</label>
                    <input type="text" name="sms_api_key" value="{{ old('sms_api_key', $settings->sms_api_key ?? '') }}" placeholder="Enter API Key"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#FF6A00] focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                </div>

                <div>
                    <label class="text-sm font-bold text-gray-700 mb-2 block dark:text-gray-300">Sender ID</label>
                    <input type="text" name="sms_sender_id" value="{{ old('sms_sender_id', $settings->sms_sender_id ?? '') }}" placeholder="e.g. SmartLookBD"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#FF6A00] focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-3">Test SMS Connection</p>
                    <div class="flex gap-2">
                        <input type="text" id="test_phone" placeholder="Phone Number" 
                            class="flex-1 border border-gray-200 rounded-xl px-4 py-2 text-xs focus:ring-2 focus:ring-orange-500 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                        <button type="button" onclick="sendTestSms()" 
                            class="bg-orange-50 text-orange-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-orange-100 transition flex items-center gap-2 dark:bg-orange-900/20 dark:text-orange-400">
                            <i data-lucide="smartphone" class="w-4 h-4"></i> Send Test
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Configuration Box -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 dark:bg-gray-800 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-800 mb-5 pb-3 border-b border-gray-100 dark:text-white dark:border-gray-700 flex items-center gap-2">
                <i data-lucide="mail" class="w-5 h-5 text-blue-500"></i> Email (SMTP) Settings
            </h3>
            
            <div class="space-y-5">
                <label class="flex gap-4 p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition items-start dark:border-gray-700 dark:hover:bg-gray-700/50">
                    <input type="hidden" name="is_smtp_active" value="0">
                    <input type="checkbox" name="is_smtp_active" value="1" {{ old('is_smtp_active', $settings->is_smtp_active ?? false) ? 'checked' : '' }} 
                        class="rounded text-green-500 focus:ring-green-500 w-5 h-5 mt-0.5 dark:bg-gray-900 dark:border-gray-700 transition">
                    <div>
                        <span class="text-sm font-bold text-gray-900 block dark:text-white">Enable Custom SMTP</span>
                        <span class="text-xs text-gray-500 mt-1 block dark:text-gray-400">Use this to send emails via your own server (e.g. cPanel).</span>
                    </div>
                </label>

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="text-sm font-bold text-gray-700 mb-2 block dark:text-gray-300">SMTP Host</label>
                        <input type="text" name="smtp_host" value="{{ old('smtp_host', $settings->smtp_host ?? '') }}" placeholder="e.g. mail.smartlookbd.com"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#FF6A00] focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label class="text-sm font-bold text-gray-700 mb-2 block dark:text-gray-300">SMTP Port</label>
                        <input type="text" name="smtp_port" value="{{ old('smtp_port', $settings->smtp_port ?? '') }}" placeholder="e.g. 465 or 587"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#FF6A00] focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label class="text-sm font-bold text-gray-700 mb-2 block dark:text-gray-300">Encryption</label>
                        <select name="smtp_encryption" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#FF6A00] focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                            <option value="tls" {{ old('smtp_encryption', $settings->smtp_encryption ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ old('smtp_encryption', $settings->smtp_encryption ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label class="text-sm font-bold text-gray-700 mb-2 block dark:text-gray-300">SMTP Username / Email</label>
                        <input type="text" name="smtp_username" value="{{ old('smtp_username', $settings->smtp_username ?? '') }}" placeholder="e.g. info@smartlookbd.com"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#FF6A00] focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                    </div>

                    <div class="col-span-2">
                        <label class="text-sm font-bold text-gray-700 mb-2 block dark:text-gray-300">SMTP Password</label>
                        <input type="password" name="smtp_password" value="{{ old('smtp_password', $settings->smtp_password ?? '') }}" placeholder="Enter password"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#FF6A00] focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                    </div>

                    <div class="col-span-2">
                        <label class="text-sm font-bold text-gray-700 mb-2 block dark:text-gray-300">Sender "From" Address</label>
                        <input type="email" name="smtp_from_address" value="{{ old('smtp_from_address', $settings->smtp_from_address ?? '') }}" placeholder="e.g. no-reply@smartlookbd.com"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#FF6A00] focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">
                    </div>

                    <div class="col-span-2 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" onclick="document.getElementById('test-smtp-form').submit();" class="w-full bg-blue-50 text-blue-600 px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-blue-100 transition flex items-center justify-center gap-2 dark:bg-blue-900/20 dark:text-blue-400">
                            <i data-lucide="send" class="w-4 h-4"></i> Send Test Email to "{{ $settings->smtp_from_address ?? 'Sender Address' }}"
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bottom Action Bar -->
    <div class="mt-6 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm flex justify-end dark:bg-gray-800 dark:border-gray-700">
        <button type="submit" class="bg-[#FF6A00] hover:bg-[#FF7A1A] text-white px-8 py-3 rounded-xl text-sm font-bold shadow-lg shadow-orange-500/20 transition transform active:scale-95">
            Save Gateway Settings
        </button>
    </div>
</form>

<form id="test-smtp-form" action="{{ route('admin.settings.test-smtp') }}" method="POST" class="hidden">
    @csrf
</form>

@endsection

@push('scripts')
<form id="test-sms-form" action="{{ route('admin.settings.test-sms') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="test_phone" id="hidden_test_phone">
</form>

<script>
    function sendTestSms() {
        const phone = document.getElementById('test_phone').value;
        if (!phone) {
            alert('Please enter a phone number to test.');
            return;
        }
        document.getElementById('hidden_test_phone').value = phone;
        document.getElementById('test-sms-form').submit();
    }
</script>
@endpush
