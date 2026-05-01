<!-- Settings Sidebar -->
<div class="card">
    <div class="card-body">
        <div class="settings-sidebar">
            <h4 class="fw-bold mb-3 fs-17">Financial Settings</h4>
            <div class="list-group list-group-flush settings-sidebar">
                <a href="{{ route('payment-gateway-settings.index') }}"
                    class="d-block p-2 fw-medium {{ request()->routeIs('payment-gateway-settings.index') ? 'active' : '' }}">
                    Payment Gateways
                </a>

                <a href="{{ route('bank-account-settings.index') }}"
                    class="d-block p-2 fw-medium {{ request()->routeIs('bank-account-settings.index') ? 'active' : '' }}">
                    Bank Accounts
                </a>

                <a href="{{ route('tax-rate-settings.index') }}"
                    class="d-block p-2 fw-medium {{ request()->routeIs('tax-rate-settings.index') ? 'active' : '' }}">
                    Tax Rates
                </a>

                <a href="{{ route('currency-settings.index') }}"
                    class="d-block p-2 fw-medium {{ request()->routeIs('currency-settings.index') ? 'active' : '' }}">
                    Currencies
                </a>
            </div>
        </div>
    </div>
</div>
<!-- /Settings Sidebar -->
