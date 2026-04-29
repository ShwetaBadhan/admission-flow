<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'account_holder_name',
        'bank_name',
        'branch_name',
        'account_number',
        'aba_number',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Get the default bank account
     */
    public static function getDefault()
    {
        return static::where('is_default', true)->where('is_active', true)->first();
    }

    /**
     * Get active accounts for dropdown
     */
    public static function getActiveForDropdown()
    {
        return static::where('is_active', true)
            ->orderBy('is_default', 'desc')
            ->orderBy('bank_name')
            ->pluck('bank_name . ' - ' . account_number', 'id');
    }
}