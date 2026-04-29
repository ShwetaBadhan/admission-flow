<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BankAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = BankAccount::query();

        // Server-side search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('account_holder_name', 'like', "%{$search}%")
                  ->orWhere('bank_name', 'like', "%{$search}%")
                  ->orWhere('branch_name', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%")
                  ->orWhere('aba_number', 'like', "%{$search}%");
            });
        }

        $bankAccounts = $query->latest()->paginate(10)->withQueryString();

        return view('pages.settings.financial-settings.bank-account-settings', compact('bankAccounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_holder_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50|unique:bank_accounts,account_number',
            'aba_number' => 'nullable|string|max:20',
            'is_default' => 'nullable|boolean',
        ]);

        // If setting as default, unset others
        if ($request->boolean('is_default')) {
            BankAccount::where('is_default', true)->update(['is_default' => false]);
        }

        BankAccount::create([
            ...$validated,
            'is_default' => $request->boolean('is_default'),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Bank account added successfully!');
    }

    public function update(Request $request, $id)
    {
        $bankAccount = BankAccount::findOrFail($id);

        $validated = $request->validate([
            'account_holder_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50|unique:bank_accounts,account_number,' . $id,
            'aba_number' => 'nullable|string|max:20',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        // If setting as default, unset others
        if ($request->boolean('is_default')) {
            BankAccount::where('id', '!=', $id)->where('is_default', true)->update(['is_default' => false]);
        }

        $bankAccount->update([
            ...$validated,
            'is_default' => $request->boolean('is_default'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->back()->with('success', 'Bank account updated successfully!');
    }

    public function toggleStatus(Request $request, $id)
    {
        $bankAccount = BankAccount::findOrFail($id);
        
        // Can't deactivate default account
        if ($bankAccount->is_default && !$request->boolean('is_active')) {
            return redirect()->back()->with('error', 'Cannot deactivate the default bank account!');
        }
        
        $bankAccount->update(['is_active' => $request->boolean('is_active')]);
        
        $status = $bankAccount->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Bank account {$status}!");
    }

    public function destroy($id)
    {
        $bankAccount = BankAccount::findOrFail($id);
        
        // Can't delete default account
        if ($bankAccount->is_default) {
            return redirect()->back()->with('error', 'Cannot delete the default bank account!');
        }
        
        $bankAccount->delete();
        return redirect()->back()->with('success', 'Bank account deleted successfully!');
    }
}