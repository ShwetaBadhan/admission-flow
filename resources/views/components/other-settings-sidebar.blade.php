<!-- Settings Sidebar -->
						<div class="card mb-0">
							<div class="card-body">
								<div class="settings-sidebar">
									<h4 class="fs-17 mb-3">Other Settings</h4>
									<div class="list-group list-group-flush settings-sidebar">
										<a href="{{ route('sitemap-settings') }}" 
   class="d-block p-2 fw-medium {{ request()->routeIs('sitemap-settings') ? 'active' : '' }}">
   Sitemap
</a>

<a href="{{ route('clear-cache-settings') }}" 
   class="d-block p-2 fw-medium {{ request()->routeIs('clear-cache-settings') ? 'active' : '' }}">
   Clear Cache
</a>
										{{-- <a href="{{ route ('storage-settings')}}" class="d-block p-2 fw-medium">Storage</a> --}}
										{{-- <a href="{{ route ('cronjob-settings')}}" class="d-block p-2 fw-medium">Cronjob</a> --}}
										{{-- <a href="{{ route ('ban-ip-settings')}}" class="d-block p-2 fw-medium">Ban IP Address</a> --}}
										{{-- <a href="{{ route ('system-backup-settings')}}" class="d-block p-2 fw-medium">System Backup</a> --}}
										{{-- <a href="{{ route ('database-backup-settings')}}" class="d-block p-2 fw-medium">Database Backup</a> --}}
										{{-- <a href="{{ route ('system-update-settings')}}" class="d-block p-2 fw-medium">System Update</a> --}}
									</div>
								</div>
							</div>
						</div>
						<!-- /Settings Sidebar -->