<a href="{{ route('profile-settings') }}" 
   class="d-block p-2 fw-medium {{ request()->routeIs('profile-settings') ? 'active' : '' }}">
   Profile
</a>

<a href="{{ route('security-settings') }}" 
   class="d-block p-2 fw-medium {{ request()->routeIs('security-settings') ? 'active' : '' }}">
   Security
</a>

{{-- <a href="{{ route('notification-settings') }}" 
   class="d-block p-2 fw-medium {{ request()->routeIs('notification-settings') ? 'active' : '' }}">
   Notifications
</a> --}}
{{-- 
<a href="{{ route('connected-apps-settings') }}" 
   class="d-block p-2 fw-medium {{ request()->routeIs('connected-apps-settings') ? 'active' : '' }}">
   Connected Apps
</a> --}}