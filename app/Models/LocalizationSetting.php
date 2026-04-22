<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalizationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'time_zone',
        'start_week',
        'date_format',
        'time_format',
        'default_language',
        'language_switcher',
        'currency',
        'currency_symbol',
        'currency_position',
        'decimal_separator',
        'thousand_separator',
    ];

    protected $casts = [
        'language_switcher' => 'boolean',
    ];
}