<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Bazario Admin</title>
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
            <h1 class="text-2xl font-bold text-white">Create New Password</h1>
            <p class="text-slate-400 text-sm mt-1">Set a strong password for your admin account</p>
        </div>

        <!-- Card -->
        <div class="bg-slate-800 rounded-3xl p-8 shadow-2xl border border-slate-700">
            <form action="{{ route('admin.password.update') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="text-sm font-medium text-slate-300 mb-2 block">New Password</label>
                    <input type="password" name="password" required autofocus
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-5 py-3.5 text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition @error('password') border-red-500 @enderror">
                    @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-300 mb-2 block">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-5 py-3.5 text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold rounded-xl py-4 shadow-lg shadow-orange-500/25 hover:shadow-orange-500/40 transition hover:-translate-y-0.5">
                        Update Password & Login
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
