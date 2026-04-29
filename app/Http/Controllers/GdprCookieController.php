<?php

namespace App\Http\Controllers;

use App\Models\GdprCookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GdprCookieController extends Controller
{
    /**
     * Display GDPR cookies settings
     */
    public function index()
    {
        $gdprSettings = GdprCookie::getOrCreate();
        return view('pages.settings.system-settings.cookies', compact('gdprSettings'));
    }

    /**
     * Update GDPR cookies settings
     */
    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'cookie_position' => 'required|string|in:left,right,bottom,top',
                'agree_button_text' => 'required|string|max:100',
                'decline_button_text' => 'nullable|string|max:100',
                'show_decline_button' => 'nullable',
                'cookie_content' => 'nullable|string',
                'cookies_page_link' => 'nullable|url|max:500',
            ]);

            $gdprSettings = GdprCookie::getOrCreate();

            $gdprSettings->update([
                'cookie_position' => $validated['cookie_position'],
                'agree_button_text' => $validated['agree_button_text'],
                'decline_button_text' => $validated['decline_button_text'] ?? 'Decline',
                'show_decline_button' => $request->boolean('show_decline_button'),
                'cookie_content' => $validated['cookie_content'] ?? null,
                'cookies_page_link' => $validated['cookies_page_link'] ?? null,
            ]);

            return redirect()->back()->with('success', 'GDPR cookie settings updated successfully!');

        } catch (\Exception $e) {
            Log::error('GDPR Settings Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update settings. Please try again.']);
        }
    }

    /**
     * Toggle GDPR cookies active status
     */
    public function toggleStatus(Request $request)
    {
        $gdprSettings = GdprCookie::getOrCreate();
        $gdprSettings->update(['is_active' => !$gdprSettings->is_active]);

        $status = $gdprSettings->is_active ? 'enabled' : 'disabled';
        return redirect()->back()->with('success', "GDPR cookies {$status}!");
    }
}