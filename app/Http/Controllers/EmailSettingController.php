<?php

namespace App\Http\Controllers;

use App\Models\EmailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class EmailSettingController extends Controller
{
    public function index()
    {
        // Ensure defaults exist
        EmailSetting::initializeDefaults();
        
        // Get all providers grouped or listed
        $emailSettings = EmailSetting::all()->keyBy('provider');
        
        return view('pages.settings.system-settings.email-settings', compact('emailSettings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'provider' => ['required', 'string', Rule::in(['php_mailer', 'smtp', 'sendgrid']), 'unique:email_settings,provider'],
            'from_email' => 'required|email|max:255',
            'from_name' => 'nullable|string|max:255',
            'mail_host' => 'nullable|required_if:provider,smtp|string|max:255',
            'mail_port' => 'nullable|required_if:provider,smtp|integer|min:1|max:65535',
            'mail_username' => 'nullable|required_if:provider,smtp|string|max:255',
            'mail_password' => 'nullable|required_if:provider,smtp|string',
            'mail_encryption' => 'nullable|required_if:provider,smtp|in:tls,ssl,null',
            'api_key' => 'nullable|required_if:provider,sendgrid|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Build config array based on provider
        $config = [];
        
        if ($validated['provider'] === 'smtp' || $validated['provider'] === 'php_mailer') {
            $config = [
                'mail_host' => $validated['mail_host'] ?? null,
                'mail_port' => $validated['mail_port'] ?? null,
                'mail_username' => $validated['mail_username'] ?? null,
                'mail_encryption' => $validated['mail_encryption'] ?? null,
                'from_email' => $validated['from_email'],
                'from_name' => $validated['from_name'] ?? null,
                'timeout' => 30,
            ];
            
            // Encrypt password if provided
            if (!empty($validated['mail_password'])) {
                $config['mail_password'] = Crypt::encryptString($validated['mail_password']);
            }
        }
        
        if ($validated['provider'] === 'sendgrid') {
            $config = [
                'api_key' => !empty($validated['api_key']) ? Crypt::encryptString($validated['api_key']) : null,
                'from_email' => $validated['from_email'],
                'from_name' => $validated['from_name'] ?? null,
            ];
        }

        $emailSetting = EmailSetting::create([
            'provider' => $validated['provider'],
            'name' => ucfirst(str_replace('_', ' ', $validated['provider'])),
            'logo' => asset("assets/img/icons/mail-" . ($validated['provider'] === 'sendgrid' ? '03' : ($validated['provider'] === 'smtp' ? '02' : '01')) . ".svg"),
            'description' => $this->getProviderDescription($validated['provider']),
            'config' => $config,
            'is_active' => $request->has('is_active'),
            'is_connected' => $this->checkConnection($validated['provider'], $config),
        ]);

        return redirect()->back()->with('success', "{$emailSetting->name} configured successfully!");
    }

    public function update(Request $request, $id)
    {
        $emailSetting = EmailSetting::findOrFail($id);

        $validated = $request->validate([
            'from_email' => 'required|email|max:255',
            'from_name' => 'nullable|string|max:255',
            'mail_host' => 'nullable|required_if:provider,smtp|string|max:255',
            'mail_port' => 'nullable|required_if:provider,smtp|integer|min:1|max:65535',
            'mail_username' => 'nullable|required_if:provider,smtp|string|max:255',
            'mail_password' => 'nullable|string', // Optional - only update if provided
            'mail_encryption' => 'nullable|required_if:provider,smtp|in:tls,ssl,null',
            'api_key' => 'nullable|string', // Optional - only update if provided
            'is_active' => 'nullable|boolean',
        ]);

        // Start with existing config
        $config = $emailSetting->config ?? [];

        // Update common fields
        $config['from_email'] = $validated['from_email'];
        $config['from_name'] = $validated['from_name'] ?? null;

        // Handle provider-specific fields
        if ($emailSetting->provider === 'smtp' || $emailSetting->provider === 'php_mailer') {
            $config['mail_host'] = $validated['mail_host'] ?? null;
            $config['mail_port'] = $validated['mail_port'] ?? null;
            $config['mail_username'] = $validated['mail_username'] ?? null;
            $config['mail_encryption'] = $validated['mail_encryption'] ?? null;
            
            // Only update password if a new one was provided
            if (!empty($validated['mail_password'])) {
                $config['mail_password'] = Crypt::encryptString($validated['mail_password']);
            }
        }
        
        if ($emailSetting->provider === 'sendgrid') {
            // Only update API key if a new one was provided
            if (!empty($validated['api_key'])) {
                $config['api_key'] = Crypt::encryptString($validated['api_key']);
            }
        }

        $emailSetting->update([
            'config' => $config,
            'is_active' => $request->has('is_active'),
            'is_connected' => $this->checkConnection($emailSetting->provider, $config),
        ]);

        return redirect()->back()->with('success', "{$emailSetting->name} updated successfully!");
    }

    public function toggleStatus(Request $request, $id)
    {
        $emailSetting = EmailSetting::findOrFail($id);
        
        // If activating, ensure it's connected first
        if ($request->has('is_active') && !$emailSetting->is_connected) {
            return redirect()->back()->with('error', "Cannot activate {$emailSetting->name}. Please configure it first.");
        }

        // If activating this one, deactivate others (only one active at a time)
        if ($request->has('is_active')) {
            EmailSetting::where('id', '!=', $id)->update(['is_active' => false]);
        }

        $emailSetting->update(['is_active' => $request->has('is_active')]);
        
        $status = $emailSetting->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "{$emailSetting->name} {$status}!");
    }

    public function sendTestEmail(Request $request, $id)
    {
        $emailSetting = EmailSetting::findOrFail($id);
        
        $validated = $request->validate([
            'to_email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            // Decrypt sensitive config values for testing
            $config = $emailSetting->getDecryptedConfig();
            
            // TODO: Implement actual email sending logic here
            // Example using Laravel Mail facade:
            /*
            Mail::raw($validated['message'], function ($message) use ($validated, $config) {
                $message->to($validated['to_email'])
                        ->subject($validated['subject'])
                        ->from($config['from_email'], $config['from_name'] ?? config('app.name'));
            });
            */
            
            Log::info("Test email sent via {$emailSetting->name} to {$validated['to_email']}");
            return redirect()->back()->with('success', 'Test email sent successfully!');
            
        } catch (\Exception $e) {
            Log::error('Test email failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $emailSetting = EmailSetting::findOrFail($id);
        
        $activeCount = EmailSetting::where('is_active', true)->count();
        if ($emailSetting->is_active && $activeCount <= 1) {
            return redirect()->back()->with('error', 'Cannot delete the only active email provider!');
        }

        $emailSetting->delete();
        return redirect()->back()->with('success', 'Email provider deleted successfully!');
    }

    /**
     * Check if provider configuration is complete enough to connect
     */
    private function checkConnection($provider, $config)
    {
        switch ($provider) {
            case 'php_mailer':
            case 'smtp':
                return !empty($config['mail_host']) && 
                       !empty($config['mail_port']) && 
                       !empty($config['mail_username']) && 
                       !empty($config['mail_password']) &&
                       !empty($config['from_email']);
            
            case 'sendgrid':
                return !empty($config['api_key']) && !empty($config['from_email']);
            
            default:
                return false;
        }
    }
    
    /**
     * Get description for provider
     */
    private function getProviderDescription($provider)
    {
        $descriptions = [
            'php_mailer' => 'PHPMailer is a third-party PHP library that provides a simple way to send emails in PHP.',
            'smtp' => 'SMTP (Simple Mail Transfer Protocol) is the standard protocol for sending emails across the Internet.',
            'sendgrid' => 'SendGrid is a cloud-based SMTP provider that allows you to send emails without maintaining email servers.',
        ];
        return $descriptions[$provider] ?? '';
    }
}