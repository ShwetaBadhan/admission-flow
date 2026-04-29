<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'email',
        'api_key',
        'secret_key',
        'description',
        'is_active',
        'is_connected',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_connected' => 'boolean',
    ];

    /**
     * Hide encrypted keys from API/JSON output
     */
    protected $hidden = [
        'api_key',
        'secret_key',
    ];

    /**
     * Get decrypted API key (for internal use only)
     */
    public function getDecryptedApiKey(): ?string
    {
        if (!$this->api_key) return null;
        try {
            return \Illuminate\Support\Facades\Crypt::decryptString($this->api_key);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get decrypted secret key (for internal use only)
     */
    public function getDecryptedSecretKey(): ?string
    {
        if (!$this->secret_key) return null;
        try {
            return \Illuminate\Support\Facades\Crypt::decryptString($this->secret_key);
        } catch (\Exception $e) {
            return null;
        }
    }
}