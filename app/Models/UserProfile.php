<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'username',
        'phone',
        'address',
        'country',
        'state_id',
        'city_id',
        'postal_code',
        'profile_image',
        'white_logo',    // For dark sidebar
        'black_logo',    // For light sidebar
        'favicon',
        'cover_image',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    /**
     * Relationship to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get logo URL with fallback to default
     */
    public function getLogoUrlAttribute($type = 'black')
    {
        $field = $type === 'white' ? 'white_logo' : 'black_logo';
        
        if ($this->$field && Storage::disk('public')->exists($this->$field)) {
            return Storage::url($this->$field);
        }
        
        // Fallback to default assets
        return asset("assets/img/logo-{$type}.svg");
    }

    /**
     * Get favicon URL with fallback
     */
    public function getFaviconUrlAttribute()
    {
        if ($this->favicon && Storage::disk('public')->exists($this->favicon)) {
            return Storage::url($this->favicon);
        }
        return asset('assets/img/favicon.png');
    }

    /**
     * Get profile image URL with fallback
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image && Storage::disk('public')->exists($this->profile_image)) {
            return Storage::url($this->profile_image);
        }
        return asset('assets/img/profiles/avatar-01.jpg');
    }
}