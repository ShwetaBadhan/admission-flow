<?php

namespace App\Http\Controllers;

use App\Models\TaxRate;
use App\Models\TaxGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaxController extends Controller
{
    public function index(Request $request)
    {
        // Tax Rates with search
        $taxRatesQuery = TaxRate::query();
        if ($request->filled('search_rates')) {
            $taxRatesQuery->where('name', 'like', "%{$request->search_rates}%");
        }
        $taxRates = $taxRatesQuery->latest()->get();

        // Tax Groups with search
        $taxGroupsQuery = TaxGroup::query();
        if ($request->filled('search_groups')) {
            $taxGroupsQuery->where('name', 'like', "%{$request->search_groups}%");
        }
        $taxGroups = $taxGroupsQuery->latest()->get();

        // All active tax rates for dropdown in groups
        $allTaxRates = TaxRate::where('is_active', true)->get();

        return view('pages.settings.financial-settings.tax-rate-settings', compact(
            'taxRates', 'taxGroups', 'allTaxRates'
        ));
    }

    // ===== TAX RATES CRUD =====
    
    public function storeRate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tax_rates,name',
            'rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        TaxRate::create([
            'name' => $validated['name'],
            'rate' => $validated['rate'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->back()->with('success', 'Tax rate added successfully!');
    }

    public function updateRate(Request $request, $id)
    {
        $taxRate = TaxRate::findOrFail($id);

        $validated = $request->validate([
            'name' => "required|string|max:255|unique:tax_rates,name,{$id}",
            'rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $taxRate->update([
            'name' => $validated['name'],
            'rate' => $validated['rate'],
            'is_active' => $request->boolean('is_active', $taxRate->is_active),
        ]);

        return redirect()->back()->with('success', 'Tax rate updated successfully!');
    }

    public function destroyRate($id)
    {
        $taxRate = TaxRate::findOrFail($id);
        
        // Check if used in any tax group
        if (TaxGroup::whereJsonContains('sub_taxes', $id)->exists()) {
            return redirect()->back()->with('error', 'Cannot delete: This tax rate is used in a tax group!');
        }
        
        $taxRate->delete();
        return redirect()->back()->with('success', 'Tax rate deleted successfully!');
    }

    // ===== TAX GROUPS CRUD =====
    
    public function storeGroup(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tax_groups,name',
            'sub_taxes' => 'required|array|min:1',
            'sub_taxes.*' => 'exists:tax_rates,id',
            'is_active' => 'nullable|boolean',
        ]);

        TaxGroup::create([
            'name' => $validated['name'],
            'sub_taxes' => $validated['sub_taxes'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->back()->with('success', 'Tax group added successfully!');
    }

    public function updateGroup(Request $request, $id)
    {
        $taxGroup = TaxGroup::findOrFail($id);

        $validated = $request->validate([
            'name' => "required|string|max:255|unique:tax_groups,name,{$id}",
            'sub_taxes' => 'required|array|min:1',
            'sub_taxes.*' => 'exists:tax_rates,id',
            'is_active' => 'nullable|boolean',
        ]);

        $taxGroup->update([
            'name' => $validated['name'],
            'sub_taxes' => $validated['sub_taxes'],
            'is_active' => $request->boolean('is_active', $taxGroup->is_active),
        ]);

        return redirect()->back()->with('success', 'Tax group updated successfully!');
    }

    public function destroyGroup($id)
    {
        TaxGroup::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Tax group deleted successfully!');
    }
}