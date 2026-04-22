<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // ← Important!
// use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\UserProfile;
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
// use SoftDeletes;
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'boolean',
        'two_factor_enabled' => 'boolean',
    'password_updated_at' => 'datetime',
    'phone_verified_at' => 'datetime',
    'account_deactivated_at' => 'datetime',
    
    ];
    public function devices()
{
    return $this->hasMany(UserDevice::class);
}

public function loginLogs()
{
    return $this->hasMany(UserLoginLog::class)->latest('logged_at');
}
public function profile()
{
    return $this->hasOne(UserProfile::class);
}
}