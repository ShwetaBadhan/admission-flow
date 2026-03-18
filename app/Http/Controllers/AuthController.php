<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller; // ← Ensure this import exists

class AuthController extends Controller
{
    // 👉 GET login page
    public function login()
    {
        return view('pages.auth.login');
    }

    // 👉 POST login form
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            
            // ✅ FIXED: Check if user is inactive
            if (auth()->user()->status != 1) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is inactive. Please contact admin.'
                ])->withInput($request->except('password'));
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password'
        ])->withInput($request->except('password'));
    }

    // 👉 Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}