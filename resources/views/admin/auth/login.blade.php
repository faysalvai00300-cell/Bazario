<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - SmartLookBD</title>
    <meta name="robots" content="noindex, nofollow">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0f172a; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center mb-4">
                <img src="{{ asset('final logo.jpeg') }}" alt="SmartLookBD" class="w-16 h-16 rounded-2xl shadow-lg shadow-orange-500/30 object-cover">
            </div>
            <h1 class="text-2xl font-bold text-white">SmartLookBD Admin</h1>
            <p class="text-slate-400 text-sm mt-1">Sign in to manage SmartLookBD</p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-800 rounded-3xl p-8 shadow-2xl border border-slate-700">
            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500/20 text-red-500 text-sm p-4 rounded-xl mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="text-sm font-medium text-slate-300 mb-2 block">System Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-5 py-3.5 text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition @error('email') border-red-500 @enderror">
                    @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-slate-300 block">Master Password</label>
                        <a href="{{ route('admin.password.request') }}" class="text-xs font-semibold text-orange-500 hover:text-orange-400 transition">Forgot Password?</a>
                    </div>
                    <input type="password" name="password" required
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-5 py-3.5 text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold rounded-xl py-4 shadow-lg shadow-orange-500/25 hover:shadow-orange-500/40 transition hover:-translate-y-0.5">
                        Authenticate
                    </button>
                </div>
            </form>
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('home') }}" class="text-slate-500 text-sm hover:text-white transition flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Storefront
            </a>
        </div>
    </div>

    <script>
        // Automatic Countdown Timer for Throttling
        document.addEventListener('DOMContentLoaded', function() {
            // Target any red text that contains the throttle message
            const errorElements = document.querySelectorAll('.text-red-400, .text-red-500, .bg-red-500\\/10');
            
            errorElements.forEach(el => {
                if (el.innerText.includes('try again in')) {
                    let match = el.innerText.match(/(\d+)/);
                    if (match) {
                        let seconds = parseInt(match[0]);
                        const timerInterval = setInterval(() => {
                            seconds--;
                            if (seconds <= 0) {
                                clearInterval(timerInterval);
                                location.reload();
                            } else {
                                el.innerHTML = `Too many login attempts. Please try again in <span class="font-bold underline">${seconds}</span> seconds.`;
                            }
                        }, 1000);
                    }
                }
            });
        });
    </script>

</body>
</html>
