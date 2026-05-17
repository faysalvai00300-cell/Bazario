<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Carbon\Carbon;
use App\Models\Order;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $field = $request->input('login_type', 'email');
        $identity = $request->input($field);

        $request->validate([
            $field => 'required|string',
            'password' => 'required|string',
        ]);

        $value = trim($identity);
        if ($field === 'phone') {
            // Standardize phone number format
            $value = preg_replace('/[^0-9]/', '', $value);
            if (str_starts_with($value, '880')) {
                $value = substr($value, 3); // Remove 880
            } elseif (str_starts_with($value, '88')) {
                $value = substr($value, 2); // Remove 88
            }
            
            // If user typed 11 digits starting with 0, it's already correct.
            // If user typed 10 digits (no leading 0), add it.
            if (strlen($value) === 10) {
                $value = '0' . $value;
            }
        }

        $credentials = [$field => $value, 'password' => $request->password];

        $user = User::where($field, $value)->first();
        if (!$user) {
            return back()->withErrors([$field => "No account found with this {$field}."])->withInput();
        }

        if ($user->role === 'admin') {
            return back()->withErrors([$field => 'Admin accounts must login through the admin portal.'])->withInput();
        }

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $user = Auth::user();
            $request->session()->regenerate();
            $user->update(['last_ip' => $request->ip()]);

            // Link guest orders
            if ($user->email) Order::where('email', $user->email)->whereNull('user_id')->update(['user_id' => $user->id]);
            if ($user->phone) Order::where('phone', $user->phone)->whereNull('user_id')->update(['user_id' => $user->id]);

            return redirect()->intended(route('home'));
        }

        return back()->withErrors(['password' => 'The password you entered is incorrect.'])->withInput();
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|string|max:15',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.confirmed' => 'Passwords do not match.'
        ]);

        $email = $request->email;
        $phone = null;

        if ($request->filled('phone')) {
            $phone = preg_replace('/[^0-9]/', '', $request->phone);
            if (str_starts_with($phone, '880')) {
                $phone = substr($phone, 3);
            } elseif (str_starts_with($phone, '88')) {
                $phone = substr($phone, 2);
            }

            if (strlen($phone) === 10) {
                $phone = '0' . $phone;
            }

            if (strlen($phone) !== 11 || !str_starts_with($phone, '01')) {
                return back()->withErrors(['phone' => 'Please enter a valid 11-digit phone number.'])->withInput();
            }

            if (User::where('phone', $phone)->exists()) {
                return back()->withErrors(['phone' => 'This phone number is already registered. Please login.'])->withInput();
            }
        }

        if ($request->filled('email')) {
            if (User::where('email', $email)->exists()) {
                return back()->withErrors(['email' => 'This email is already registered. Please login.'])->withInput();
            }
        }

        if (!$email && !$phone) {
            return back()->withErrors(['phone' => 'Either Email or Phone is required.'])->withInput();
        }

        // Store registration data
        $regData = [
            'name' => $request->name,
            'email' => $email,
            'phone' => $phone,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ];


        if ($phone) {
            $otp = sprintf("%06d", mt_rand(100000, 999999));
            session([
                'temp_reg_data' => $regData,
                'reg_otp' => $otp,
                'reg_otp_expires_at' => Carbon::now()->addMinutes(10),
                'otp_identity' => $phone,
                'otp_type' => 'phone'
            ]);

            $this->sendOtpNotification($phone, $otp);
            return redirect()->route('register.verify')->with('success', 'A verification code has been sent to your phone number.');
        } elseif ($email) {
            $otp = sprintf("%06d", mt_rand(100000, 999999));
            session([
                'temp_reg_data' => $regData,
                'reg_otp' => $otp,
                'reg_otp_expires_at' => Carbon::now()->addMinutes(10),
                'otp_identity' => $email,
                'otp_type' => 'email'
            ]);

            $this->sendOtpNotification($email, $otp);
            return redirect()->route('register.verify')->with('success', 'A verification code has been sent to your email address.');
        }
    }

    public function verifyRegisterForm()
    {
        if (!session('temp_reg_data')) {
            return redirect()->route('register');
        }
        return view('auth.verify-otp', [
            'type' => 'register',
            'identity' => session('otp_identity')
        ]);
    }

    public function verifyRegister(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $otp = session('reg_otp');
        $expires = session('reg_otp_expires_at');
        $regData = session('temp_reg_data');

        if ($request->otp == $otp && Carbon::now()->lt($expires)) {
            $regData['last_ip'] = $request->ip();
            $user = User::create($regData);
            
            Auth::login($user);
            session()->forget(['temp_reg_data', 'reg_otp', 'reg_otp_expires_at']);
            $request->session()->regenerate();

            // Link guest orders
            if ($user->email) Order::where('email', $user->email)->whereNull('user_id')->update(['user_id' => $user->id]);
            if ($user->phone) Order::where('phone', $user->phone)->whereNull('user_id')->update(['user_id' => $user->id]);

            return redirect()->route('home')->with('success', 'Registration successful!');
        }

        return back()->withErrors(['otp' => 'Invalid or expired code.']);
    }

    public function resendRegisterOtp()
    {
        if (!session('temp_reg_data')) return redirect()->route('register');

        $otp = sprintf("%06d", mt_rand(100000, 999999));
        session(['reg_otp' => $otp, 'reg_otp_expires_at' => Carbon::now()->addMinutes(10)]);

        $identity = session('otp_identity');
        $this->sendOtpNotification($identity, $otp);

        return back()->with('success', 'A new verification code has been sent.');
    }

    private function sendOtpNotification($identity, $otp)
    {
        $isEmail = filter_var($identity, FILTER_VALIDATE_EMAIL);

        if ($isEmail) {
            try {
                Mail::to($identity)->send(new OtpMail($otp));
            } catch (\Exception $e) {
                \Log::error("OTP Mail failed: " . $e->getMessage());
            }
        } else {
            $settings = \App\Models\Setting::first();
            if ($settings && $settings->is_sms_active && !empty($settings->sms_api_url)) {
                $smsMessage = "Your Bazario verification code is: {$otp}";
                $apiUrl = str_replace(
                    ['[USER]', '[TO]', '[MESSAGE]', '[KEY]', '[SENDER]'],
                    [urlencode($settings->sms_username ?? ''), urlencode($identity), urlencode($smsMessage), urlencode($settings->sms_api_key ?? ''), urlencode($settings->sms_sender_id ?? '')],
                    $settings->sms_api_url
                );

                try {
                    // Added timeout and SSL bypass for cPanel/Shared Hosting compatibility
                    $response = \Illuminate\Support\Facades\Http::timeout(15)
                        ->withoutVerifying()
                        ->get($apiUrl);
                    
                    if (!$response->successful()) {
                        \Log::error("OTP SMS API returned error: " . $response->body());
                    }
                } catch (\Exception $e) {
                    \Log::error("OTP SMS Request failed: " . $e->getMessage());
                }
            }
        }
        \Log::info("DEBUG: Verification code for {$identity} is: {$otp}");
    }

    public function forgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $type = $request->input('reset_type', 'email');
        $identity = null;
        
        if ($type === 'phone') {
            $request->validate(['phone' => 'required|string']);
            $identity = preg_replace('/[^0-9]/', '', $request->phone);
            if (str_starts_with($identity, '880')) $identity = substr($identity, 3);
            elseif (str_starts_with($identity, '88')) $identity = substr($identity, 2);
            if (strlen($identity) === 10) $identity = '0' . $identity;

            $user = User::where('phone', $identity)->where('role', '!=', 'admin')->first();
            if (!$user) return back()->withErrors(['phone' => 'We cannot find a user with that phone number.'])->withInput();
        } else {
            $request->validate(['email' => 'required|email']);
            $identity = $request->email;
            $user = User::where('email', $identity)->where('role', '!=', 'admin')->first();
            if (!$user) return back()->withErrors(['email' => 'We cannot find a user with that email address.'])->withInput();
        }

        $otp = sprintf("%06d", mt_rand(100000, 999999));
        session([
            'reset_identity' => $identity,
            'reset_otp' => $otp,
            'reset_otp_expires_at' => Carbon::now()->addMinutes(10),
            'otp_identity' => $identity,
            'otp_type' => $type
        ]);

        $this->sendOtpNotification($identity, $otp);
        return redirect()->route('password.verify')->with('success', "A reset code has been sent to your {$type}.");
    }

    public function verifyResetForm()
    {
        if (!session('reset_identity')) return redirect()->route('password.request');
        return view('auth.verify-otp', ['type' => 'password_reset', 'identity' => session('reset_identity')]);
    }

    public function verifyReset(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);
        $otp = session('reset_otp');
        $expires = session('reset_otp_expires_at');
        $identity = session('reset_identity');

        if ($request->otp == $otp && Carbon::now()->lt($expires)) {
            session(['password_reset_verified_identity' => $identity]);
            return redirect()->route('password.reset.phone');
        }

        return back()->withErrors(['otp' => 'Invalid or expired code.']);
    }

    public function resetPasswordPhoneForm()
    {
        if (!session('password_reset_verified_identity')) return redirect()->route('password.request');
        return view('auth.reset-password-phone');
    }

    public function resetPasswordPhone(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $identity = session('password_reset_verified_identity');
        $type = session('otp_type');

        $user = User::where($type === 'phone' ? 'phone' : 'email', $identity)->where('role', '!=', 'admin')->first();
        
        if ($user) {
            $user->update(['password' => Hash::make($request->password)]);
            
            // Auto login after reset
            Auth::login($user);
            $request->session()->regenerate();

            session()->forget(['password_reset_verified_identity', 'reset_identity', 'reset_otp', 'reset_otp_expires_at', 'otp_identity', 'otp_type']);
            
            return redirect()->route('home')->with('success', 'Your password has been reset and you are now logged in!');
        }

        return redirect()->route('password.request')->withErrors(['identity' => 'An error occurred. Please try again.']);
    }

    public function resetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
            'token' => 'required'
        ]);

        $reset = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->withErrors(['email' => 'Invalid token!']);
        }

        $user = User::where('email', $request->email)->where('role', '!=', 'admin')->first();
        if (!$user) return back()->withErrors(['email' => 'Unauthorized access.']);

        $user->update(['password' => Hash::make($request->password)]);

        \DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();

        // Auto login for token based too
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Your password has been reset and you are now logged in!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }
}
