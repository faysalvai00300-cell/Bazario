<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        // Use the currently authenticated user
        $user = auth()->user();
        
        // Final fallback if somehow auth() is missing but middleware passed (env-based login)
        if (!$user) {
            $user = User::where('role', 'admin')->first();
        }
        
        return view('admin.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('admin.loginform')->with('error', 'Please login to continue.');
        }

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'name' => 'required|string|max:255',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($user->save()) {
            return back()->with('success', 'Profile updated successfully.');
        }

        return back()->with('error', 'Something went wrong while saving.');
    }
}
