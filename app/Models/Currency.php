<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Currency extends Model
{
    protected $fillable = [
        'name',
        'code',
        'symbol',
        'exchange_rate',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:6',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the default currency
     */
    public static function getDefault()
    {
        return static::where('is_default', true)->where('is_active', true)->first();
    }

    /**
     * Set a currency as default (transaction-safe)
     */
    public static function setDefault($id)
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($id) {
            static::where('is_default', true)->update(['is_default' => false]);
            return static::findOrFail($id)->update(['is_default' => true, 'is_active' => true]);
        });
    }

    /**
     * Format exchange rate for display
     */
    protected function formattedExchangeRate(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->exchange_rate, 2),
        );
    }

    /**
     * Get active currencies for dropdowns
     */
    public static function getActiveForDropdown()
    {
        return static::where('is_active', true)
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->pluck('name . ' . ' (' . 'code' . ')', 'id');
    }
}