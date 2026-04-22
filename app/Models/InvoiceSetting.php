<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_prefix',
        'invoice_image',
        'round_off_value',
        'enable_round_off',
        'show_company_details',
        'invoice_terms',
    ];

    protected $casts = [
        'enable_round_off' => 'boolean',
        'show_company_details' => 'boolean',
        'round_off_value' => 'integer',
    ];

    /**
     * Get the invoice settings (create if not exists)
     */
    public static function getOrCreate()
    {
        return static::firstOrCreate([]);
    }
}