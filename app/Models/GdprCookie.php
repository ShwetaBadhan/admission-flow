<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GdprCookie extends Model
{
    protected $fillable = [
        'cookie_content',
        'cookie_position',
        'agree_button_text',
        'decline_button_text',
        'show_decline_button',
        'cookies_page_link',
        'is_active',
    ];

    protected $casts = [
        'show_decline_button' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get or create the singleton GDPR settings record
     */
    public static function getOrCreate(array $defaults = [])
    {
        $instance = static::first();
        
        if (!$instance) {
            return static::create(array_merge([
                'cookie_content' => 'We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.',
                'cookie_position' => 'bottom',
                'agree_button_text' => 'Agree',
                'decline_button_text' => 'Decline',
                'show_decline_button' => true,
                'cookies_page_link' => null,
                'is_active' => true,
            ], $defaults));
        }
        
        return $instance;
    }
}