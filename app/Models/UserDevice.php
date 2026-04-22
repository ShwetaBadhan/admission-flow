<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDevice extends Model
{
    protected $fillable = ['user_id', 'device_name', 'ip_address', 'user_agent', 'location', 'last_active_at', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'last_active_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
