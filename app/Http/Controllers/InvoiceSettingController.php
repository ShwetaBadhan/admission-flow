<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InvoiceSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceSettingController extends Controller
{
    /**
     * Show invoice settings page
     */
    public function index()
    {
        $settings = InvoiceSetting::getOrCreate();
        return view('admin.pages.settings.app-settings.invoice-settings', compact('settings'));
    }
public function templates()
{
       $settings = InvoiceSetting::first();
    return view('admin.pages.settings.app-settings.invoice-templates-settings', compact('settings'));
}
    /**
     * Store or update invoice settings
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_prefix' => 'nullable|string|max:50',
            'invoice_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB
            'round_off_value' => 'nullable|integer|in:5,10',
            'enable_round_off' => 'nullable|boolean',
            'show_company_details' => 'nullable|boolean',
            'invoice_terms' => 'nullable|string',
        ]);

        // Get or create settings
        $settings = InvoiceSetting::getOrCreate();

        // Handle image upload
        if ($request->hasFile('invoice_image')) {
            // Delete old image if exists
            if ($settings->invoice_image && Storage::disk('public')->exists($settings->invoice_image)) {
                Storage::disk('public')->delete($settings->invoice_image);
            }

            $image = $request->file('invoice_image');
            $imageName = time() . '_invoice_logo.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('invoice-settings', $imageName, 'public');
            $validated['invoice_image'] = $path;
        }

        // Handle boolean fields
        $validated['enable_round_off'] = $request->has('enable_round_off');
        $validated['show_company_details'] = $request->has('show_company_details');

        // Update or create
        $settings->update($validated);

        return redirect()->back()->with('success', 'Invoice settings updated successfully!');
    }
}