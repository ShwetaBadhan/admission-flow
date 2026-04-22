<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'flag',
        'is_rtl',
        'is_default',
        'is_active',
        'web_enabled',
        'app_enabled',
        'admin_enabled',
    ];

    protected $casts = [
        'is_rtl' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'web_enabled' => 'boolean',
        'app_enabled' => 'boolean',
        'admin_enabled' => 'boolean',
    ];
}