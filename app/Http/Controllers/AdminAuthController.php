<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class AdminAuthController extends Controller
{
    public function loginForm()
    {
        // If already logged in as admin via guard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        // Return isolated admin login view
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $throttleKey = 'admin-login:' . $request->ip();

        // Check if user is locked out
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors(['email' => "Too many login attempts. Please try again in {$seconds} seconds."])->onlyInput('email');
        }

        // 1. Standard DB Auth (The only way to login now)
        $credentials = $request->only('email', 'password');
        \Log::info('Admin Login Attempt', ['email' => $credentials['email']]);
        
        if (Auth::guard('admin')->attempt($credentials)) {
            \Log::info('Auth::guard(admin)->attempt Success', ['email' => $credentials['email']]);
            $user = Auth::guard('admin')->user();
            
            if ($user->role === 'admin') {
                RateLimiter::clear($throttleKey);
                session(['admin_authenticated' => true]); // Keeping for middleware compatibility
                return redirect()->route('admin.dashboard')->with('success', 'Admin login successful.');
            }
            
            \Log::warning('User is not admin', ['email' => $credentials['email'], 'role' => $user->role]);
            Auth::guard('admin')->logout();
            return back()->withErrors(['email' => 'You are not authorized as an admin.']);
        }

        \Log::error('Auth::attempt Failed', ['email' => $credentials['email']]);

        // Increment failed attempts
        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors(['email' => 'Invalid email or password. Please try again.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        session()->forget('admin_authenticated');
        return redirect()->route('admin.loginform')->with('success', 'Admin logged out successfully.');
    }

    public function forgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $identity = $request->email;
        
        $user = User::where('email', $identity)->where('role', 'admin')->first();
        if (!$user) {
            return back()->withErrors(['email' => 'We cannot find an admin with that email address.'])->withInput();
        }

        $otp = sprintf("%06d", mt_rand(100000, 999999));
        session([
            'admin_reset_identity' => $identity,
            'admin_reset_otp' => $otp,
            'admin_reset_otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        $this->sendOtpNotification($identity, $otp);
        return redirect()->route('admin.password.verify')->with('success', "A reset code has been sent to your email.");
    }

    public function verifyResetForm()
    {
        if (!session('admin_reset_identity')) return redirect()->route('admin.password.request');
        return view('admin.auth.verify-otp', ['identity' => session('admin_reset_identity')]);
    }

    public function verifyReset(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);
        $otp = session('admin_reset_otp');
        $expires = session('admin_reset_otp_expires_at');
        $identity = session('admin_reset_identity');

        if ($request->otp == $otp && Carbon::now()->lt($expires)) {
            session(['admin_password_reset_verified_identity' => $identity]);
            return redirect()->route('admin.password.reset');
        }

        return back()->withErrors(['otp' => 'Invalid or expired code.']);
    }

    public function resetPasswordForm()
    {
        if (!session('admin_password_reset_verified_identity')) return redirect()->route('admin.password.request');
        return view('admin.auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $identity = session('admin_password_reset_verified_identity');
        $user = User::where('email', $identity)->where('role', 'admin')->first();
        
        if ($user) {
            $user->update(['password' => Hash::make($request->password)]);
            
            // Auto login after reset
            Auth::guard('admin')->login($user);
            session(['admin_authenticated' => true]);
            
            session()->forget(['admin_password_reset_verified_identity', 'admin_reset_identity', 'admin_reset_otp', 'admin_reset_otp_expires_at']);
            
            return redirect()->route('admin.dashboard')->with('success', 'Your password has been reset and you are now logged in!');
        }

        return redirect()->route('admin.password.request')->withErrors(['email' => 'An error occurred. Please try again.']);
    }

    private function sendOtpNotification($identity, $otp)
    {
        try {
            Mail::to($identity)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            \Log::error("Admin OTP Mail failed: " . $e->getMessage());
        }
        \Log::info("DEBUG: Admin Verification code for {$identity} is: {$otp}");
    }
}
