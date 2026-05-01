<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LocalizationSetting;
use Illuminate\Http\Request;

class LocalizationSettingController extends Controller
{
    /**
     * Show the form for editing the settings.
     */
    public function index()
    {
        // Fetch the existing settings if they exist
        $settings = LocalizationSetting::first();
         $availableLanguages = \App\Models\Language::where('is_active', true)
        ->orderBy('is_default', 'desc')
        ->orderBy('name')
        ->get();
    
         return view('pages.settings.website-settings.localization-settings', 
        compact('settings', 'availableLanguages')
    );
    }

    /**
     * Update or Create the settings.
     */
    public function update(Request $request)
{
    $validated = $request->validate([
        'default_language' => 'required|string|max:50',
        'language_switcher' => 'nullable|boolean',
        'time_zone' => 'required|string|max:100',
        'date_format' => 'required|string|max:50',
        'time_format' => 'required|in:12,24',
        'financial_year' => 'nullable|integer|min:2000|max:2100',
        'start_month' => 'nullable|integer|min:1|max:12',
        'currency' => 'required|string|max:10',
        'currency_symbol' => 'required|string|max:10',
        'currency_position' => 'required|string|max:50',
        'decimal_separator' => 'required|string|max:5',
        'thousand_separator' => 'required|string|max:5',
        'country_restriction' => 'nullable|string|in:allow_all,deny_all,allow_selected,deny_selected',
        'allowed_files' => 'nullable|string|max:255',
        'max_file_size' => 'nullable|integer|min:1|max:100',
    ]);

    // Handle checkbox
    $validated['language_switcher'] = $request->boolean('language_switcher');

    // Update or create
    $setting = LocalizationSetting::first();
    
   if ($setting) {
    $setting->update($validated);
    $message = 'Settings updated successfully.';
} else {
    LocalizationSetting::create($validated);
    $message = 'Settings created successfully.';
}

// ✅ Clear cached locale so change takes effect immediately
cache()->forget('localization_settings');
if (class_exists(\Illuminate\Support\Facades\App::class)) {
    // Optional: Reload locale immediately
    $newLocale = $validated['default_language'] ?? $setting?->default_language;
    if ($newLocale && \App\Models\Language::where('code', $newLocale)->where('is_active', true)->exists()) {
        app()->setLocale($newLocale);
        session()->put('locale', $newLocale); // Optional: persist in session
    }
}

return redirect()->back()->with('success', $message);
}
}