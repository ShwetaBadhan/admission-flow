<!-- Settings Sidebar -->
								<div class="card">
									<div class="card-body">
										<div class="settings-sidebar">
											<h4 class="fw-bold mb-3 fs-17">Financial Settings</h4>
											<div class="list-group list-group-flush settings-sidebar">
												<a href="{{ route('payment-gateway-settings') }}"
   class="d-block p-2 fw-medium {{ request()->routeIs('payment-gateway-settings') ? 'active' : '' }}">
   Payment Gateways
</a>

<a href="{{ route('bank-account-settings') }}"
   class="d-block p-2 fw-medium {{ request()->routeIs('bank-account-settings') ? 'active' : '' }}">
   Bank Accounts
</a>

<a href="{{ route('tax-rate-settings') }}"
   class="d-block p-2 fw-medium {{ request()->routeIs('tax-rate-settings') ? 'active' : '' }}">
   Tax Rates
</a>

<a href="{{ route('currency-settings') }}"
   class="d-block p-2 fw-medium {{ request()->routeIs('currency-settings') ? 'active' : '' }}">
   Currencies
</a>
											</div>
										</div>
									</div>
								</div>
								<!-- /Settings Sidebar -->
