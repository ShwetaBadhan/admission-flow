<?php

namespace App\Http\Controllers;

use App\Models\UserDevice;
use App\Models\UserLoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SecuritySettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $devices = $user->devices()->where('is_active', true)->latest('last_active_at')->get();
        $logs = $user->loginLogs()->take(10)->get();

        return view('pages.settings.general-settings.security-settings', compact('user', 'devices', 'logs'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->password_updated_at = now();
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    public function toggleTwoFactor(Request $request)
    {
        $user = Auth::user();
        $user->two_factor_enabled = $request->boolean('enabled');
        $user->save();

        return back()->with('success', 'Two-factor authentication ' . ($user->two_factor_enabled ? 'enabled' : 'disabled') . '.');
    }

    public function updatePhone(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^\+[0-9]{10,15}$/'],
            'password' => ['required', 'current_password'],
        ]);

        Auth::user()->update([
            'phone' => $request->phone,
            'phone_verified_at' => null,
        ]);

        return back()->with('success', 'Phone number updated. Please verify via OTP.');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:users,email,'.Auth::id()],
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();
        $user->email = $request->email;
        $user->email_verified_at = null;
        $user->save();

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Email updated. Check inbox to verify.');
    }

    public function logoutDevice($id)
    {
        $device = Auth::user()->devices()->where('id', $id)->first();
        if ($device) {
            $device->update(['is_active' => false]);
            return back()->with('success', 'Device logged out successfully.');
        }
        return back()->with('error', 'Device not found.');
    }

    public function deactivateAccount(Request $request)
    {
        $request->validate(['password' => ['required', 'current_password']]);
        Auth::user()->update(['account_deactivated_at' => now()]);
        Auth::logout();
        return redirect()->route('login')->with('success', 'Account deactivated successfully.');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
            'reason' => ['required', 'in:no_use,privacy,notifications,ux,others'],
            'other_reason' => ['nullable', 'required_if:reason,others', 'max:255'],
        ]);

        Auth::user()->delete();
        Auth::logout();
        return redirect()->route('login')->with('success', 'Account deleted permanently.');
    }
}