<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - Bazario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Space Grotesk', sans-serif; background-color: #0f172a; color: white; }
        .gradient-text { background: linear-gradient(135deg, #f87171, #ef4444); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .glow { filter: drop-shadow(0 0 20px rgba(239, 68, 68, 0.4)); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 antialiased overflow-hidden">
    <!-- Abstract Background -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-red-900/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-900/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-xl text-center space-y-8 animate-in fade-in zoom-in duration-1000">
        <!-- Shield Icon -->
        <div class="flex justify-center">
            <div class="w-32 h-32 bg-red-500/10 rounded-full flex items-center justify-center border border-red-500/20 glow">
                <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01"></path>
                </svg>
            </div>
        </div>

        <div class="space-y-4">
            <h1 class="text-6xl font-bold tracking-tighter sm:text-7xl gradient-text lowercase">Access Blocked</h1>
            <p class="text-slate-400 text-lg leading-relaxed max-w-md mx-auto">
                Your IP address (<span class="text-slate-200 font-mono text-sm px-2 py-1 bg-slate-800 rounded">{{ request()->ip() }}</span>) has been restricted from accessing our services due to security violations or suspicious activity.
            </p>
        </div>

        <div class="pt-8 border-t border-slate-800 flex flex-col items-center gap-6">
            <p class="text-slate-500 text-sm italic">If you believe this is an error, please contact our support team.</p>
            <a href="mailto:support@bazario.com" class="px-8 py-3 bg-white text-slate-900 font-bold hover:bg-red-500 hover:text-white transition-all transform hover:scale-105 active:scale-95 duration-300 uppercase tracking-widest text-xs">
                Email Support
            </a>
        </div>

        <div class="pt-10">
            <div class="brand-logo-font text-2xl font-black text-slate-400">
                Bazario
            </div>
        </div>
    </div>
</body>
</html>
