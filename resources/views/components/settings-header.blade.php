<ul class="nav nav-tabs nav-bordered nav-bordered-primary">

    <!-- General Settings -->
    <li class="nav-item me-3">
        <a href="{{ route('profile-settings') }}" 
           class="nav-link p-2 {{ request()->routeIs([
                'profile-settings*',
                'security-settings*',
                'notification-settings*',
                'connected-apps-settings*'
           ]) ? 'active' : '' }}">
            <i class="ti ti-settings-cog me-2"></i>General Settings
        </a>
    </li>

    <!-- Website Settings -->
    <li class="nav-item me-3">
        <a href="{{ route('localization-settings') }}" 
           class="nav-link p-2 {{ request()->routeIs([
                
                'localization-settings*',
                'language-settings*'
           ]) ? 'active' : '' }}">
            <i class="ti ti-world-cog me-2"></i>Website Settings
        </a>
    </li>

    <!-- App Settings -->
    <li class="nav-item me-3">
        <a href="{{ route('invoice-settings.index') }}" 
           class="nav-link p-2 {{ request()->routeIs([
                'invoice-settings*',
                'app-settings*'
           ]) ? 'active' : '' }}">
            <i class="ti ti-apps me-2"></i>App Settings
        </a>
    </li>

    <!-- System Settings -->
    <li class="nav-item me-3">
        <a href="{{ route('email-settings.index') }}" 
           class="nav-link p-2 {{ request()->routeIs([
                'email-settings*',
                'cookies*'
                
           ]) ? 'active' : '' }}">
            <i class="ti ti-device-laptop me-2"></i>System Settings
        </a>
    </li>

    <!-- Financial Settingsssss -->
    <li class="nav-item me-3">
        <a href="{{ route('payment-gateway-settings.index') }}" 
           class="nav-link p-2 {{ request()->routeIs([
                'payment-gateway-settings*',
                'bank-account-settings*',
                'tax-rate-settings*',
                'currency-settings*'
           ]) ? 'active' : '' }}">
            <i class="ti ti-moneybag me-2"></i>Financial Settings
        </a>
    </li>

    <!-- Other Settings -->
    {{-- <li class="nav-item">
        <a href="{{ route('sitemap-settings') }}" 
           class="nav-link p-2 {{ request()->routeIs([
                'sitemap-settings*',
                'clear-cache-settings*'
           ]) ? 'active' : '' }}">
            <i class="ti ti-flag-cog me-2"></i>Other Settings
        </a>
    </li> --}}

</ul>