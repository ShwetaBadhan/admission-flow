<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\LocalizationSetting;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Check session first (user override)
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
            return $next($request);
        }

        // 2. Check URL parameter (?lang=fr)
        if ($request->has('lang')) {
            $locale = $request->get('lang');
            if ($this->isValidLocale($locale)) {
                session()->put('locale', $locale);
                app()->setLocale($locale);
                return $next($request);
            }
        }

        // 3. Use default from database
        $settings = LocalizationSetting::first();
        $defaultLocale = $settings?->default_language ?? config('app.locale', 'en');
        
        if ($this->isValidLocale($defaultLocale)) {
            app()->setLocale($defaultLocale);
        }

        return $next($request);
    }

    private function isValidLocale($locale)
    {
        // Check against active languages in DB
        return \App\Models\Language::where('code', $locale)
            ->where('is_active', true)
            ->exists();
    }
}