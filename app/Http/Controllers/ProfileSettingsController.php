<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileSettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile()->firstOrNew(['user_id' => $user->id]);
        $states = State::all();
        $cities = City::all();

        return view('pages.settings.general-settings.profile-settings', compact('profile', 'states', 'cities'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile()->firstOrNew(['user_id' => $user->id]);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'username'   => ['required', 'string', 'max:255', Rule::unique('user_profiles')->ignore($profile->id)],
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:500',
            'country'    => 'nullable|string|max:100',
            'state_id'   => 'nullable|exists:states,id',
            'city_id'    => 'nullable|exists:cities,id',
            'postal_code'=> 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'white_logo'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'black_logo'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'favicon'       => 'nullable|image|mimes:png,ico|max:512',
            'cover_image'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Handle File Uploads & Delete Old Files
        $imageFields = ['profile_image', 'white_logo', 'black_logo', 'favicon', 'cover_image'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                if ($profile->$field && Storage::disk('public')->exists($profile->$field)) {
                    Storage::disk('public')->delete($profile->$field);
                }
                $validated[$field] = $request->file($field)->store('profiles/' . $user->id, 'public');
            }
        }

        $profile->fill($validated);
        $profile->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
