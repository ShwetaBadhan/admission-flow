<?php

namespace App\Http\Controllers;

use App\Models\InvoiceSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class InvoiceSettingController extends Controller
{
    public function index()
    {
        $settings = InvoiceSetting::firstOrCreate([]);
        return view('pages.settings.app-settings.invoice-settings', compact('settings'));
    }

  public function update(Request $request)
{
    // 🔍 DEBUG: Log raw request data
    Log::info('=== Invoice Settings Debug ===');
    Log::info('Request data:', $request->all());
    Log::info('Has enable_round_off:', [$request->has('enable_round_off')]);
    Log::info('Has show_company_details:', [$request->has('show_company_details')]);
    Log::info('Files:', $request->file() ? array_keys($request->file()) : 'none');

    try {
        $validated = $request->validate([
            'invoice_prefix' => 'nullable|string|max:20',
            'invoice_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'invoice_due_days' => 'nullable|integer|min:1|max:365',
            'enable_round_off' => 'nullable',
            'round_off_type' => 'nullable|in:up,down,nearest',
            'show_company_details' => 'nullable',
            'invoice_terms' => 'nullable|string|max:1000',
            'remove_invoice_image' => 'nullable|in:0,1',
        ]);

        Log::info('Validated data:', $validated);

        // 🔍 Check if table/columns exist
        $settings = InvoiceSetting::first();
        if (!$settings) {
            Log::info('Creating new settings record');
            $settings = new InvoiceSetting();
        } else {
            Log::info('Updating existing record ID: ' . $settings->id);
        }

        // 🔍 Debug image handling
        if ($request->input('remove_invoice_image') == '1' && $settings->invoice_image) {
            Log::info('Removing old image: ' . $settings->invoice_image);
            if (Storage::disk('public')->exists($settings->invoice_image)) {
                Storage::disk('public')->delete($settings->invoice_image);
            }
            $settings->invoice_image = null;
        }

        if ($request->hasFile('invoice_image')) {
            Log::info('New image uploaded');
            $image = $request->file('invoice_image');
            Log::info('Image details:', [
                'name' => $image->getClientOriginalName(),
                'size' => $image->getSize(),
                'type' => $image->getMimeType()
            ]);
            
            if ($settings->invoice_image && Storage::disk('public')->exists($settings->invoice_image)) {
                Storage::disk('public')->delete($settings->invoice_image);
            }
            
            $imageName = time() . '_invoice_logo.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('invoice-settings', $imageName, 'public');
            Log::info('Image stored at: ' . $path);
            $settings->invoice_image = $path;
        }

        // 🔍 Prepare update data with explicit casting
        $updateData = [
            'invoice_prefix' => $validated['invoice_prefix'] ?? 'INV-',
            'invoice_due_days' => (int) ($validated['invoice_due_days'] ?? 5),
            'enable_round_off' => (bool) ($request->input('enable_round_off') === '1' || $request->has('enable_round_off')),
            'round_off_type' => $validated['round_off_type'] ?? 'up',
            'show_company_details' => (bool) ($request->input('show_company_details') === '1' || $request->has('show_company_details')),
            'invoice_terms' => $validated['invoice_terms'] ?? null,
        ];

        Log::info('Update data:', $updateData);

        // 🔍 Attempt update with detailed error catching
        $settings->fill($updateData);
        
        if (!$settings->save()) {
            throw new \Exception('Model save() returned false');
        }

        Log::info('✅ Settings updated successfully');
        return redirect()->back()->with('success', 'Invoice settings updated successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('❌ Validation failed:', [
            'errors' => $e->errors(),
            'input' => $request->all()
        ]);
        return redirect()->back()
            ->withInput()
            ->withErrors($e->errors());
            
    } catch (\Illuminate\Database\QueryException $e) {
        Log::error('❌ Database error:', [
            'message' => $e->getMessage(),
            'sql' => $e->getSql(),
            'bindings' => $e->getBindings()
        ]);
        return redirect()->back()
            ->withInput()
            ->withErrors(['error' => 'Database error: ' . $e->getMessage()]);
            
    } catch (\Illuminate\Database\Eloquent\MassAssignmentException $e) {
        Log::error('❌ Mass assignment error:', [
            'message' => $e->getMessage(),
            'fillable' => (new InvoiceSetting)->getFillable()
        ]);
        return redirect()->back()
            ->withInput()
            ->withErrors(['error' => 'Mass assignment error. Check $fillable in model.']);
            
    } catch (\Exception $e) {
        Log::error('❌ Unexpected error:', [
            'class' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        return redirect()->back()
            ->withInput()
            ->withErrors(['error' => 'Error: ' . $e->getMessage()]);
    }
}
}