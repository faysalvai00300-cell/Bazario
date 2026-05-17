<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Bazario</title>
    <meta name="robots" content="noindex, nofollow">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f5f6f8;
        }
        .login-wrapper {
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }
        .login-logo-area {
            text-align: center;
            margin-bottom: 28px;
        }
        .login-logo-area img {
            width: 64px;
            height: 64px;
            object-fit: contain;
            border-radius: 14px;
            margin-bottom: 12px;
        }
        .login-logo-area h1 {
            font-size: 22px;
            font-weight: 800;
            color: #1a1a2e;
            margin-bottom: 4px;
        }
        .login-logo-area p {
            font-size: 13px;
            color: #6b7280;
        }
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 36px 32px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            border: 1px solid #f0f0f0;
        }
        .login-card .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            font-size: 13px;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .login-card .form-group {
            margin-bottom: 18px;
        }
        .login-card label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .login-card input[type="email"],
        .login-card input[type="password"] {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            color: #111827;
            background: #fff;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .login-card input:focus {
            border-color: #7b3fc4;
            box-shadow: 0 0 0 3px rgba(123,63,196,0.1);
        }
        .login-card .label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        .login-card .forgot-link {
            font-size: 12px;
            color: #7b3fc4;
            text-decoration: none;
            font-weight: 600;
        }
        .login-card .forgot-link:hover { color: #5b2a99; }
        .login-btn {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #7b3fc4, #5b2a99);
            color: #fff;
            font-weight: 800;
            font-size: 14px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: box-shadow 0.2s, transform 0.15s;
            margin-top: 8px;
            letter-spacing: 0.03em;
        }
        .login-btn:hover {
            box-shadow: 0 8px 24px rgba(123,63,196,0.4);
            transform: translateY(-1px);
        }
        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 24px;
            font-size: 13px;
            color: #9ca3af;
            text-decoration: none;
            transition: color 0.15s;
        }
        .back-link:hover { color: #374151; }
        .sidebar-preview {
            display: none;
        }
        @media (min-width: 900px) {
            body { padding: 0; }
            .login-page-split {
                display: flex;
                width: 100vw;
                min-height: 100vh;
            }
            .sidebar-preview {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                width: 42%;
                background: linear-gradient(160deg, #00002a 0%, #1c1c3a 60%, #2d1458 100%);
                padding: 60px 48px;
                position: relative;
                overflow: hidden;
            }
            .sidebar-preview::before {
                content: '';
                position: absolute;
                width: 320px;
                height: 320px;
                border-radius: 50%;
                background: rgba(123,63,196,0.15);
                top: -80px;
                right: -80px;
            }
            .sidebar-preview::after {
                content: '';
                position: absolute;
                width: 200px;
                height: 200px;
                border-radius: 50%;
                background: rgba(255,184,34,0.08);
                bottom: 60px;
                left: -40px;
            }
            .sidebar-preview-content { position: relative; z-index: 1; text-align: center; }
            .sidebar-preview-content img { width: 80px; height: 80px; object-fit: contain; border-radius: 18px; margin-bottom: 20px; }
            .sidebar-preview-content h2 { font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 10px; line-height: 1.2; }
            .sidebar-preview-content p { font-size: 14px; color: rgba(255,255,255,0.55); line-height: 1.7; max-width: 280px; }
            .preview-badges { display: flex; gap: 10px; justify-content: center; margin-top: 32px; flex-wrap: wrap; }
            .preview-badge { background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.7); font-size: 11px; font-weight: 600; padding: 6px 14px; border-radius: 20px; }
            .login-right {
                flex: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #f5f6f8;
                padding: 40px;
            }
            .login-wrapper { max-width: 420px; }
        }
    </style>
</head>
<body>

<div class="login-page-split">
    <!-- Left side panel (desktop only) -->
    <div class="sidebar-preview">
        <div class="sidebar-preview-content">
            <img src="{{ asset('main-logo.png') }}" alt="Bazario" style="width: 80px; height: 80px;">
            <h2>Bazario<br>Admin Panel</h2>
            <p>Complete eCommerce management system for your online store. Track orders, products, customers and more.</p>
            <div class="preview-badges">
                <span class="preview-badge">📦 Orders</span>
                <span class="preview-badge">🛍️ Products</span>
                <span class="preview-badge">👥 Customers</span>
                <span class="preview-badge">📊 Analytics</span>
            </div>
        </div>
    </div>

    <!-- Right side login form -->
    <div class="login-right">
        <div class="login-wrapper">
            <div class="login-logo-area">
                <img src="{{ asset('main-logo.png') }}" alt="Bazario" style="width: 64px; height: 64px;">
                <h1>Welcome Back</h1>
                <p>Sign in to your admin account</p>
            </div>

            <div class="login-card">
                @if(session('error'))
                    <div class="error-box">{{ session('error') }}</div>
                @endif

                <form action="{{ route('admin.login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@example.com">
                        @error('email') <p style="color:#dc2626;font-size:11px;margin-top:4px;">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <div class="label-row">
                            <label style="margin-bottom:0;">Password</label>
                            <a href="{{ route('admin.password.request') }}" class="forgot-link">Forgot Password?</a>
                        </div>
                        <input type="password" name="password" required placeholder="••••••••">
                    </div>

                    <button type="submit" class="login-btn">Sign In to Dashboard</button>
                </form>
            </div>

            <a href="{{ route('home') }}" class="back-link">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Storefront
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const errorElements = document.querySelectorAll('.error-box');
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
                            el.innerHTML = `Too many login attempts. Please try again in <strong>${seconds}</strong> seconds.`;
                        }
                    }, 1000);
                }
            }
        });
    });
</script>

</body>
</html>
