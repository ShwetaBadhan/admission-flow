<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        $query = Currency::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('symbol', 'like', "%{$search}%");
            });
        }

        $currencies = $query->latest()->get();
        $defaultCurrency = Currency::where('is_default', true)->first();

        return view('pages.settings.financial-settings.currency-settings', compact('currencies', 'defaultCurrency'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|size:3|unique:currencies,code',
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0.000001',
            'is_default' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($validated, $request) {
            if ($request->boolean('is_default')) {
                Currency::where('is_default', true)->update(['is_default' => false]);
            }

            Currency::create([
                'name' => $validated['name'],
                'code' => strtoupper($validated['code']),
                'symbol' => $validated['symbol'],
                'exchange_rate' => $validated['exchange_rate'],
                'is_default' => $request->boolean('is_default'),
                'is_active' => true,
            ]);
        });

        return redirect()->back()->with('success', 'Currency added successfully!');
    }

    public function update(Request $request, $id)
    {
        $currency = Currency::findOrFail($id);

        $validated = $request->validate([
            'name' => "required|string|max:255",
            'code' => "required|string|size:3|unique:currencies,code,{$id}",
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0.000001',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($currency, $validated, $request) {
            $updateData = [
                'name' => $validated['name'],
                'code' => strtoupper($validated['code']),
                'symbol' => $validated['symbol'],
                'exchange_rate' => $validated['exchange_rate'],
                'is_active' => $request->boolean('is_active', $currency->is_active),
            ];

            // Handle default currency logic
            if ($request->boolean('is_default') && !$currency->is_default) {
                Currency::where('is_default', true)->update(['is_default' => false]);
                $updateData['is_default'] = true;
            } elseif (!$request->boolean('is_default') && $currency->is_default) {
                // Prevent unsetting default if it's the only active currency
                $activeCount = Currency::where('is_active', true)->count();
                if ($activeCount > 1) {
                    $updateData['is_default'] = false;
                }
            }

            $currency->update($updateData);
        });

        return redirect()->back()->with('success', 'Currency updated successfully!');
    }

    public function destroy($id)
    {
        $currency = Currency::findOrFail($id);

        if ($currency->is_default) {
            return redirect()->back()->with('error', 'Cannot delete the default currency!');
        }

        $currency->delete();
        return redirect()->back()->with('success', 'Currency deleted successfully!');
    }

    public function toggleStatus(Request $request, $id)
    {
        $currency = Currency::findOrFail($id);
        
        // Prevent deactivating default currency
        if ($currency->is_default && !$request->boolean('is_active')) {
            return redirect()->back()->with('error', 'Cannot deactivate the default currency!');
        }

        $currency->update(['is_active' => $request->boolean('is_active')]);
        return redirect()->back()->with('success', 'Currency status updated!');
    }

    public function setDefault($id)
    {
        $currency = Currency::findOrFail($id);
        
        DB::transaction(function () use ($currency) {
            Currency::where('is_default', true)->update(['is_default' => false]);
            $currency->update(['is_default' => true, 'is_active' => true]);
        });
        
        return redirect()->back()->with('success', 'Default currency updated to ' . $currency->name . '!');
    }
}