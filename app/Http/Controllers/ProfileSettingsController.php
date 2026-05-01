<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\UserProfile;

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
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'white_logo'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:1024',
        'black_logo'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:1024',
        'favicon'       => 'nullable|image|mimes:png,ico,jpg|max:512',
        'cover_image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
    ]);

    // 🔹 Handle File Uploads
    $imageFields = ['profile_image', 'white_logo', 'black_logo', 'favicon', 'cover_image'];
    
    foreach ($imageFields as $field) {
        if ($request->hasFile($field)) {
            // Delete old file from user_profiles
            if ($profile->$field && Storage::disk('public')->exists($profile->$field)) {
                Storage::disk('public')->delete($profile->$field);
            }
            
            // Store new file
            $file = $request->file($field);
            $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profiles/' . $user->id, $filename, 'public');
            $validated[$field] = $path;

            // 🔥 SYNC: If this is profile_image, also update users.profile_photo
            if ($field === 'profile_image') {
                // Delete old image from users table if different
                if ($user->profile_photo && $user->profile_photo !== $path && Storage::disk('public')->exists($user->profile_photo)) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                $user->profile_photo = $path; // ✅ Sync to users table
            }
        }
    }

    // Save profile data
    $profile->fill($validated);
    $profile->save();

    // ✅ Save synced user data (if profile_image was updated)
    if ($request->hasFile('profile_image')) {
        $user->save(); // Saves the synced profile_photo
    }

    // Clear cached profile data
    cache()->forget("user_profile_{$user->id}");

    return back()->with('success', 'Profile updated successfully.');
}
}
