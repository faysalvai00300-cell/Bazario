<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Bazario Admin</title>
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
                <img src="{{ asset('Bazario-logo.png') }}" alt="Bazario" class="w-16 h-16 rounded-2xl shadow-lg shadow-orange-500/30 object-cover">
            </div>
            <h1 class="text-2xl font-bold text-white">Reset Admin Password</h1>
            <p class="text-slate-400 text-sm mt-1">Enter your system email to receive a reset code</p>
        </div>

        <!-- Card -->
        <div class="bg-slate-800 rounded-3xl p-8 shadow-2xl border border-slate-700">
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-500 text-sm p-4 rounded-xl mb-6 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.password.email') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="text-sm font-medium text-slate-300 mb-2 block">System Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="admin@Bazario.com"
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-5 py-3.5 text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition @error('email') border-red-500 @enderror">
                    @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold rounded-xl py-4 shadow-lg shadow-orange-500/25 hover:shadow-orange-500/40 transition hover:-translate-y-0.5">
                        Send Reset Code
                    </button>
                </div>
            </form>
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('admin.loginform') }}" class="text-slate-500 text-sm hover:text-white transition flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Login
            </a>
        </div>
    </div>

</body>
</html>
