<div class="card mb-3 mb-xl-0">
							<div class="card-body">
								<div class="settings-sidebar">
									<h5 class="mb-3 fs-17">App Settings</h5>
									<div class="list-group list-group-flush settings-sidebar">
										<a href="{{  request()->routeIs('invoice-settings') }} ? 'active' : ''" class="d-block p-2 fw-medium active">Invoice Settings</a>
										{{-- <a href="printers-settings.html" class="d-block p-2 fw-medium">Printer</a> --}}
										{{-- <a href="custom-fields-setting.html" class="d-block p-2 fw-medium">Custom Fields</a> --}}
									</div>
								</div>
							</div> <!-- end card body -->
						</div> <!-- end card -->