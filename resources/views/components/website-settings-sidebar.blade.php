<div class="card">
    <div class="card-body">
        <div class="settings-sidebar">
            <h5 class="mb-3 fs-17">Website Settings</h5>

            <div class="list-group list-group-flush settings-sidebar">

                {{-- <a href="{{ route('company-settings') }}" 
                   class="d-block p-2 fw-medium {{ request()->routeIs('company-settings*') ? 'active' : '' }}">
                   Company Settings
                </a> --}}

                <a href="{{ route('localization-settings') }}" 
                   class="d-block p-2 fw-medium {{ request()->routeIs('localization-settings*') ? 'active' : '' }}">
                   Localization
                </a>

                <a href="{{ route('language-settings') }}" 
                   class="d-block p-2 fw-medium {{ request()->routeIs('language-settings*') ? 'active' : '' }}">
                   Language
                </a>

            </div>
        </div>
    </div>
</div>