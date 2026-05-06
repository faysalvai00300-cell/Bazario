@extends('layouts.admin')

@section('title', 'Model Notification Settings')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Model Notification</h1>
        <p class="text-sm text-gray-500 mt-1 dark:text-gray-400">Manage the notification box that appears below Category Page 1 on the homepage.</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 dark:bg-gray-800 dark:border-gray-700" 
     x-data="{ 
        text: {{ json_encode(old('model_notification', $settings->model_notification ?? '')) }},
        bg: '{{ old('notification_bg_color', $settings->notification_bg_color ?? '#1e1e2d') }}',
        textColor: '{{ old('notification_text_color', $settings->notification_text_color ?? '#ffffff') }}',
        textSize: '{{ old('notification_text_size', $settings->notification_text_size ?? '15') }}',
        effect: '{{ old('notification_effect', $settings->notification_effect ?? 'shine') }}',
        animSpeed: '{{ old('notification_animation_speed', $settings->notification_animation_speed ?? '4') }}'
     }">
    <form action="{{ route('admin.settings.model-notification.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="space-y-6">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 border-b pb-2 dark:text-white dark:border-gray-700">📣 Advanced Notification Settings</h3>
                
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Notification Text</label>
                    <textarea name="model_notification" rows="4" x-model="text"
                        placeholder="Enter your message here..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white dark:focus:ring-orange-500/50">{{ old('model_notification', $settings->model_notification ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Background Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="notification_bg_color" x-model="bg"
                                class="h-10 w-12 rounded cursor-pointer border-none bg-transparent">
                            <input type="text" x-model="bg" class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-xs uppercase dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Text Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="notification_text_color" x-model="textColor"
                                class="h-10 w-12 rounded cursor-pointer border-none bg-transparent">
                            <input type="text" x-model="textColor" class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-xs uppercase dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Text Size (PX)</label>
                        <div class="flex items-center gap-2">
                            <input type="range" min="10" max="30" step="1" x-model="textSize" class="flex-1 accent-orange-500">
                            <input type="text" name="notification_text_size" x-model="textSize" class="w-12 border border-gray-200 rounded-lg px-2 py-2 text-center text-xs dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Anim Speed (Sec)</label>
                        <div class="flex items-center gap-2">
                            <input type="range" min="1" max="10" step="0.5" x-model="animSpeed" class="flex-1 accent-orange-500">
                            <input type="text" name="notification_animation_speed" x-model="animSpeed" class="w-12 border border-gray-200 rounded-lg px-2 py-2 text-center text-xs dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 mb-2 block dark:text-gray-300">Visual Effect</label>
                    <select name="notification_effect" x-model="effect"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                        <option value="none">None (Static)</option>
                        <option value="shine">Premium Shine</option>
                        <option value="pulse">Soft Pulse Glow</option>
                        <option value="marquee">Scrolling Marquee</option>
                    </select>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="admin-primary-btn px-10 py-3 rounded-xl text-sm font-bold shadow-lg shadow-orange-500/20 active:scale-95 transition-all">
                        Save Styling
                    </button>
                </div>
            </div>

            {{-- Live Preview Section --}}
            <div class="bg-gray-50 rounded-2xl border border-dashed border-gray-200 p-6 dark:bg-gray-900/50 dark:border-gray-700 flex flex-col justify-center">
                <h4 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-6 dark:text-gray-500 text-center">Live Preview</h4>
                
                <style>
                    .preview-shine-v2 { position: relative; overflow: hidden; }
                    .preview-shine-v2::after {
                        content: ''; position: absolute; top: 0; left: -150%; width: 100%; height: 100%;
                        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), rgba(255,255,255,0.35), rgba(255,255,255,0.1), transparent);
                        animation: preview-shine-v2-anim var(--speed) cubic-bezier(0.4, 0, 0.2, 1) infinite;
                        transform: skewX(-20deg);
                    }
                    @keyframes preview-shine-v2-anim { 0% { left: -150%; } 30%, 100% { left: 150%; } }
                    
                    .preview-pulse-glow { animation: preview-pulse-glow-anim var(--speed) ease-in-out infinite; }
                    @keyframes preview-pulse-glow-anim {
                        0%, 100% { opacity: 0.8; transform: scale(1); }
                        50% { opacity: 1; transform: scale(1.02); }
                    }

                    .preview-marquee {
                        white-space: nowrap;
                        animation: preview-marquee-anim calc(var(--speed) * 3) linear infinite;
                        display: inline-block;
                        padding-left: 100%;
                    }
                    @keyframes preview-marquee-anim {
                        0% { transform: translateX(0); }
                        100% { transform: translateX(-100%); }
                    }
                </style>

                <div class="relative py-6 px-4 flex items-center justify-center rounded-sm shadow-xl border border-white/5 transition-all duration-300 overflow-hidden"
                     :class="{ 'preview-shine-v2': effect === 'shine', 'preview-pulse-glow': effect === 'pulse' }"
                     :style="{ backgroundColor: bg, '--speed': animSpeed + 's' }">
                    
                    <div class="w-full" :class="{ 'overflow-hidden': effect === 'marquee' }">
                        <p class="relative z-10 font-black tracking-[0.15em] text-center uppercase transition-all duration-300" 
                           :class="{ 'preview-marquee': effect === 'marquee' }"
                           :style="{ color: textColor, fontSize: textSize + 'px', textShadow: '0 0 10px ' + textColor + '44', whiteSpace: effect === 'marquee' ? 'nowrap' : 'pre-wrap' }"
                           x-text="text || 'Your message will appear here...'">
                        </p>
                    </div>
                </div>
                
                <p class="text-[10px] text-gray-400 mt-6 text-center italic dark:text-gray-500 underline underline-offset-4 decoration-gray-300">
                    * Interactive preview: text size and animation speed reflect results
                </p>
            </div>
        </div>
    </form>
</div>
@endsection
