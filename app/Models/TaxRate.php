<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class TaxRate extends Model
{
    protected $fillable = [
        'name',
        'rate',
        'is_active',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Accessor: Get formatted rate
     * Usage: $rate->formattedRate (returns "18.50%")
     */
    protected function formattedRate(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->rate, 2) . '%',
        );
    }
}