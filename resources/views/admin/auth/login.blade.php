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
        /* Modern Reset and Standard Fonts */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body, input, select, textarea, button, p, span, h1, h2, a, label {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
            font-style: normal !important;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            min-height: 100vh;
            background-color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Split Screen Container */
        .login-page-split {
            display: flex;
            width: 100vw;
            min-height: 100vh;
            background-color: #f8fafc;
        }

        /* Left Side: Premium Interactive Branding Panel */
        .sidebar-preview {
            display: none;
        }

        @media (min-width: 900px) {
            .login-page-split {
                display: flex;
            }

            .sidebar-preview {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                width: 42%;
                background: linear-gradient(160deg, #00002a 0%, #1c1c3a 60%, #2d1458 100%); /* Original Premium Gradient */
                padding: 60px 20px;
                position: relative;
                overflow: hidden;
                border-right: 1px solid rgba(255, 255, 255, 0.05);
            }

            /* Original Top Right Purple Geometric Circle */
            .sidebar-preview::before {
                content: '';
                position: absolute;
                width: 320px;
                height: 320px;
                border-radius: 50%;
                background: rgba(123, 63, 196, 0.15); /* Purple/violet */
                top: -80px;
                right: -80px;
                pointer-events: none;
                z-index: 1;
            }

            /* Original Bottom Left Amber Geometric Circle */
            .sidebar-preview::after {
                content: '';
                position: absolute;
                width: 200px;
                height: 200px;
                border-radius: 50%;
                background: rgba(255, 184, 34, 0.08); /* Amber/orange */
                bottom: 60px;
                left: -40px;
                pointer-events: none;
                z-index: 1;
            }

            .sidebar-preview-content {
                position: relative;
                z-index: 10;
                text-align: center;
                max-width: 380px; /* Adjusted to match natural wrap in screenshot */
                width: 100%;
            }

            /* Clean & Transparent Logo Container - No White Background Box! */
            .sidebar-preview-content .sidebar-logo-badge {
                background: transparent !important;
                border: none !important;
                box-shadow: none !important;
                backdrop-filter: none !important;
                -webkit-backdrop-filter: none !important;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 24px;
                padding: 0;
                transition: transform 0.3s ease;
            }

            .sidebar-preview-content .sidebar-logo-badge:hover {
                transform: scale(1.05);
            }

            .sidebar-preview-content .sidebar-logo-badge img {
                height: 60px; /* Perfect height matching screenshot */
                width: auto;
                object-fit: contain;
                display: block;
                /* Razor-sharp, solid white outline (no blur!) for maximum legibility of black text on dark backgrounds */
                filter: drop-shadow(1px 0px 0px #ffffff) 
                        drop-shadow(-1px 0px 0px #ffffff) 
                        drop-shadow(0px 1px 0px #ffffff) 
                        drop-shadow(0px -1px 0px #ffffff) !important;
            }

            .sidebar-preview-content h2 {
                font-size: 32px;
                font-weight: 800;
                color: #ffffff; /* White text for beautiful dark theme */
                margin-bottom: 16px;
                line-height: 1.25;
                letter-spacing: -0.02em;
            }

            .sidebar-preview-content p {
                font-size: 14px;
                color: rgba(255, 255, 255, 0.6); /* Soft white opacity matching screenshot */
                line-height: 1.6;
                margin-bottom: 36px;
            }

            /* Beautiful dynamic badge pills - adjusted to NOT wrap and fit perfectly in one line */
            .preview-badges {
                display: flex;
                gap: 8px; /* Slightly reduced gap */
                justify-content: center;
                flex-wrap: nowrap; /* Forces them to stay in a single line */
                width: 100%;
            }

            .preview-badge {
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.08);
                color: rgba(255, 255, 255, 0.85);
                font-size: 11px; /* Slightly smaller font to guarantee single-line fit */
                font-weight: 600;
                padding: 6px 12px; /* Slightly reduced padding to guarantee single-line fit */
                border-radius: 30px;
                transition: all 0.2s ease;
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                white-space: nowrap; /* Prevents text inside a badge from wrapping */
            }

            .preview-badge:hover {
                background: rgba(255, 255, 255, 0.12);
                transform: translateY(-2px);
                border-color: rgba(255, 122, 26, 0.4); /* Glowing orange border on hover! */
                color: #ffffff;
            }

            /* Right side main container */
            .login-right {
                flex: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #f8fafc;
                padding: 40px 24px;
            }
        }

        /* Right Side Mobile & General styling */
        .login-right {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            padding: 24px 16px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }

        /* Top Brand Logo Area in Form */
        .login-logo-area {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-logo-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            transition: transform 0.2s ease;
        }

        .login-logo-wrapper img {
            height: 55px; /* Matching left side height */
            width: auto;
            object-fit: contain;
            display: block;
        }

        .login-logo-area h1 {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a; /* Slate 900 */
            margin-bottom: 6px;
            letter-spacing: -0.025em;
        }

        .login-logo-area p {
            font-size: 14px;
            color: #64748b; /* Slate 500 */
            font-weight: 500;
        }

        /* Elegant Login Form Card */
        .login-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 40px 36px;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.06),
                        0 0 0 1px rgba(15, 23, 42, 0.03);
            border: 1px solid #f1f5f9;
        }

        .login-card .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            font-size: 13px;
            font-weight: 500;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .login-card .form-group {
            margin-bottom: 22px;
        }

        .login-card label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #475569; /* Slate 600 */
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Input fields styled with straight font and sleek UI */
        .login-card input[type="email"],
        .login-card input[type="password"] {
            width: 100%;
            padding: 13px 16px;
            border: 1.5px solid #cbd5e1; /* Slate 300 */
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: #0f172a;
            background: #ffffff;
            outline: none;
            transition: all 0.2s ease;
        }

        .login-card input[type="email"]:hover,
        .login-card input[type="password"]:hover {
            border-color: #94a3b8;
        }

        .login-card input:focus {
            border-color: #6366f1; /* Beautiful Indigo */
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            background: #ffffff;
        }

        .login-card input::placeholder {
            color: #94a3b8;
        }

        .login-card .label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .login-card .forgot-link {
            font-size: 12px;
            color: #6366f1;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.15s ease;
        }

        .login-card .forgot-link:hover {
            color: #4f46e5;
            text-decoration: underline;
        }

        /* Modern Gradient Submit Button */
        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 14px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 10px;
            letter-spacing: 0.02em;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }

        .login-btn:hover {
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.35);
            transform: translateY(-1px);
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
        }

        .login-btn:active {
            transform: translateY(1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }

        /* Beautiful Back to Storefront Link */
        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 28px;
            font-size: 14px;
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .back-link svg {
            transition: transform 0.2s ease;
        }

        .back-link:hover {
            color: #0f172a;
        }

        .back-link:hover svg {
            transform: translateX(-4px);
        }
    </style>
</head>
<body>

<div class="login-page-split">
    <!-- Left side panel (desktop only) -->
    <div class="sidebar-preview">
        <div class="sidebar-preview-content">
            <div class="sidebar-logo-badge">
                <img src="{{ asset('bazario-logo.png') }}?v={{ time() }}" alt="Bazario">
            </div>
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
                <div class="login-logo-wrapper">
                    <img src="{{ asset('bazario-logo.png') }}?v={{ time() }}" alt="Bazario">
                </div>
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
