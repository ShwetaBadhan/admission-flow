<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; // ✅ ADD THIS IMPORT

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::all();
        return view('pages.settings.financial-settings.payment-gateway-settings', compact('paymentMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:payment_methods,slug',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'email' => 'nullable|email|max:255',
            'api_key' => 'nullable|string',
            'secret_key' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle logo upload
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            // ✅ FIXED: Use Str::slug() instead of str_slug()
            $logoName = time() . '_' . Str::slug($validated['slug']) . '_logo.' . $logo->getClientOriginalExtension();
            $logoPath = $logo->storeAs('payment-methods', $logoName, 'public');
        }

        $paymentMethod = PaymentMethod::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'logo' => $logoPath,
            'email' => $validated['email'] ?? null,
            'api_key' => !empty($validated['api_key']) ? Crypt::encryptString($validated['api_key']) : null,
            'secret_key' => !empty($validated['secret_key']) ? Crypt::encryptString($validated['secret_key']) : null,
            'is_active' => $request->boolean('is_active'),
            'is_connected' => !empty($validated['api_key']) && !empty($validated['secret_key']),
        ]);

        return redirect()->back()->with('success', "{$paymentMethod->name} payment method added successfully!");
    }

    public function update(Request $request, $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        $validated = $request->validate([
            'email' => 'nullable|email|max:255',
            'api_key' => 'nullable|string',
            'secret_key' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $updateData = [
            'email' => $validated['email'] ?? $paymentMethod->email,
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            if ($file->isValid()) {
                if ($paymentMethod->logo && Storage::disk('public')->exists($paymentMethod->logo)) {
                    Storage::disk('public')->delete($paymentMethod->logo);
                }
                
                $extension = $file->getClientOriginalExtension();
                // ✅ FIXED: Use Str::slug() here too if needed
                $logoName = time() . '_' . Str::slug($paymentMethod->slug) . '_logo.' . $extension;
                $path = $file->storeAs('payment-methods', $logoName, 'public');
                
                if ($path) {
                    $updateData['logo'] = $path;
                }
            }
        }

        if (!empty($validated['api_key'])) {
            $updateData['api_key'] = Crypt::encryptString($validated['api_key']);
        }

        if (!empty($validated['secret_key'])) {
            $updateData['secret_key'] = Crypt::encryptString($validated['secret_key']);
        }

        $updateData['is_active'] = $request->boolean('is_active');
        
        $hasApiKey = !empty($validated['api_key']) || !empty($paymentMethod->api_key);
        $hasSecretKey = !empty($validated['secret_key']) || !empty($paymentMethod->secret_key);
        $updateData['is_connected'] = $hasApiKey && $hasSecretKey;

        $paymentMethod->update($updateData);

        return redirect()->back()->with('success', "{$paymentMethod->name} payment method updated successfully!");
    }

    public function toggleStatus(Request $request, $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->update(['is_active' => !$paymentMethod->is_active]);

        $status = $paymentMethod->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "{$paymentMethod->name} payment method {$status}!");
    }

    public function destroy($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        
        if ($paymentMethod->logo && Storage::disk('public')->exists($paymentMethod->logo)) {
            Storage::disk('public')->delete($paymentMethod->logo);
        }
        
        $paymentMethod->delete();
        return redirect()->back()->with('success', 'Payment method deleted successfully!');
    }

    public function testConnection($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        
        $apiKey = $paymentMethod->api_key ? Crypt::decryptString($paymentMethod->api_key) : null;
        $secretKey = $paymentMethod->secret_key ? Crypt::decryptString($paymentMethod->secret_key) : null;
        
        $isConnected = !empty($apiKey) && !empty($secretKey);
        
        if ($isConnected) {
            $paymentMethod->update(['is_connected' => true]);
            return response()->json(['success' => true, 'message' => 'Connection successful!']);
        }

        return response()->json(['success' => false, 'message' => 'Connection failed! Please check your credentials.'], 400);
    }
}