<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SidebarComposer
{
    public function compose(View $view)
    {
        $user = Auth::user();
        
        if ($user) {
            // Cache profile for 15 minutes to reduce DB queries
            $profile = Cache::remember("user_profile_{$user->id}", 900, function () use ($user) {
                return $user->profile;
            });
            
            $view->with('userProfile', $profile);
        }
    }
}