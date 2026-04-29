<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class TaxGroup extends Model
{
    protected $fillable = [
        'name',
        'sub_taxes', // JSON array: [1, 2, 3]
        'is_active',
    ];

    protected $casts = [
        'sub_taxes' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Accessor: Get tax rates in this group
     * Usage: $group->taxRates (returns Collection of TaxRate models)
     */
    protected function taxRates(): Attribute
    {
        return Attribute::make(
            get: fn () => \App\Models\TaxRate::whereIn('id', $this->sub_taxes ?? [])->get(),
        );
    }

    /**
     * Accessor: Get total rate for this group
     * Usage: $group->totalRate (returns float)
     */
    protected function totalRate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->taxRates->sum('rate'),
        );
    }

    /**
     * Accessor: Get formatted total rate
     * Usage: $group->formattedTotalRate (returns "18.50%")
     */
    protected function formattedTotalRate(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->totalRate, 2) . '%',
        );
    }
}