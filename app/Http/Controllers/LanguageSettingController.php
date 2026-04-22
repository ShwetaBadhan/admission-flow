<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LanguageSettingController extends Controller
{
    /**
     * Display a listing of the languages.
     */
    public function index()
    {
        $languages = Language::orderBy('is_default', 'desc')->orderBy('name')->get();
        return view('pages.settings.website-settings.language-settings', compact('languages'));
    }

    /**
     * Store a newly created language.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:languages,code',
            'flag' => 'nullable|image|mimes:svg,png,jpg,jpeg|max:2048',
            'is_rtl' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'web_enabled' => 'nullable|boolean',
            'app_enabled' => 'nullable|boolean',
            'admin_enabled' => 'nullable|boolean',
        ]);

        // Handle flag upload
        if ($request->hasFile('flag')) {
            $validated['flag'] = $request->file('flag')->store('flags', 'public');
        }

        // If setting as default, unset other defaults
        if (!empty($validated['is_default'])) {
            Language::where('is_default', true)->update(['is_default' => false]);
        }

        Language::create($validated);

        return redirect()->back()->with('success', 'Language added successfully!');
    }

    /**
     * Update the specified language.
     */
    public function update(Request $request, $id)
    {
        $language = Language::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:languages,code,' . $id,
            'flag' => 'nullable|image|mimes:svg,png,jpg,jpeg|max:2048',
            'is_rtl' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'web_enabled' => 'nullable|boolean',
            'app_enabled' => 'nullable|boolean',
            'admin_enabled' => 'nullable|boolean',
        ]);

        // Handle flag upload
        if ($request->hasFile('flag')) {
            // Delete old flag
            if ($language->flag && Storage::disk('public')->exists($language->flag)) {
                Storage::disk('public')->delete($language->flag);
            }
            $validated['flag'] = $request->file('flag')->store('flags', 'public');
        }

        // If setting as default, unset other defaults
        if (!empty($validated['is_default']) && !$language->is_default) {
            Language::where('is_default', true)->update(['is_default' => false]);
        }

        // Prevent unsetting default if it's the only default
        if (empty($validated['is_default']) && $language->is_default) {
            $otherDefaults = Language::where('is_default', true)->where('id', '!=', $id)->count();
            if ($otherDefaults === 0) {
                return redirect()->back()->with('error', 'At least one language must be set as default!');
            }
        }

        $language->update($validated);

        return redirect()->back()->with('success', 'Language updated successfully!');
    }

    /**
     * Remove the specified language.
     */
    public function destroy($id)
    {
        $language = Language::findOrFail($id);

        // Prevent deleting default language
        if ($language->is_default) {
            return redirect()->back()->with('error', 'Cannot delete the default language!');
        }

        // Delete flag file
        if ($language->flag && Storage::disk('public')->exists($language->flag)) {
            Storage::disk('public')->delete($language->flag);
        }

        $language->delete();

        return redirect()->back()->with('success', 'Language deleted successfully!');
    }

    /**
     * Toggle language status (AJAX)
     */
    public function toggleStatus(Request $request, $id)
    {
        $language = Language::findOrFail($id);
        $language->is_active = !$language->is_active;
        $language->save();

        return response()->json([
            'success' => true,
            'is_active' => $language->is_active
        ]);
    }

    /**
     * Toggle RTL status (AJAX)
     */
    public function toggleRTL(Request $request, $id)
    {
        $language = Language::findOrFail($id);
        $language->is_rtl = !$language->is_rtl;
        $language->save();

        return response()->json([
            'success' => true,
            'is_rtl' => $language->is_rtl
        ]);
    }

    /**
     * Set as default language (AJAX)
     */
    public function setDefault(Request $request, $id)
    {
        $language = Language::findOrFail($id);
        
        Language::where('is_default', true)->update(['is_default' => false]);
        $language->is_default = true;
        $language->save();

        return response()->json([
            'success' => true,
            'message' => 'Default language updated successfully!'
        ]);
    }

    /**
     * Toggle platform enabled status (AJAX)
     */
    public function togglePlatform(Request $request, $id, $platform)
    {
        $language = Language::findOrFail($id);
        $field = $platform . '_enabled';

        if (!in_array($platform, ['web', 'app', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Invalid platform'], 400);
        }

        $language->$field = !$language->$field;
        $language->save();

        return response()->json([
            'success' => true,
            'enabled' => $language->$field
        ]);
    }
}