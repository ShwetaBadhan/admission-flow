<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class EmailSetting extends Model
{
    protected $fillable = [
        'provider',
        'name',
        'logo',
        'description',
        'config',
        'is_active',
        'is_connected',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
        'is_connected' => 'boolean',
    ];

    /**
     * Initialize default providers if they don't exist
     */
    public static function initializeDefaults()
    {
        $providers = [
            'php_mailer' => ['name' => 'PHP Mailer', 'logo' => 'mail-01.svg'],
            'smtp' => ['name' => 'SMTP', 'logo' => 'mail-02.svg'],
            'sendgrid' => ['name' => 'SendGrid', 'logo' => 'mail-03.svg'],
        ];

        foreach ($providers as $provider => $data) {
            static::firstOrCreate(
                ['provider' => $provider],
                [
                    'name' => $data['name'],
                    'logo' => asset("assets/img/icons/{$data['logo']}"),
                    'description' => self::getDescription($provider),
                    'config' => [],
                    'is_active' => false,
                    'is_connected' => false,
                ]
            );
        }
    }

    /**
     * Get decrypted config (for display in forms)
     */
    public function getDecryptedConfig()
    {
        $config = $this->config ?? [];
        
        // Decrypt sensitive fields for display
        foreach (['mail_password', 'api_key', 'secret'] as $field) {
            if (!empty($config[$field])) {
                try {
                    $config[$field] = Crypt::decryptString($config[$field]);
                } catch (\Exception $e) {
                    Log::warning("Failed to decrypt {$field}: " . $e->getMessage());
                    $config[$field] = null;
                }
            }
        }
        
        return $config;
    }

    private static function getDescription($provider)
    {
        return match($provider) {
            'php_mailer' => 'PHPMailer is a third-party PHP library for sending emails.',
            'smtp' => 'SMTP (Simple Mail Transfer Protocol) for sending emails via mail servers.',
            'sendgrid' => 'SendGrid cloud-based email delivery service.',
            default => '',
        };
    }
}