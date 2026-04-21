@extends('layout.master')
@section('content')

 <!-- ========================
			Start Page Content
		========================= -->
         
        <div class="page-wrapper">

            <!-- Start Content -->
            <div class="content">

                <!-- Page Header -->
                <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                    <div>
                        <h4 class="mb-1">Settings</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Settings</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="gap-2 d-flex align-items-center flex-wrap">
                        <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
                        <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
                    </div>
                </div>                
				<!-- End Page Header -->

				<div class="card border-0">
					<div class="card-body pb-0 pt-0 px-2">
						@include('components.settings-header')
					</div> <!-- end card body -->
				</div> <!-- end card -->
				
				<!-- start row -->
				<div class="row">

					<div class="col-xl-3 col-lg-12 theiaStickySidebar">

						<div class="card mb-3 mb-xl-0">
							<div class="card-body">
								<div class="settings-sidebar">
									<h5 class="mb-3 fs-17">General Settings</h5>
									<div class="list-group list-group-flush settings-sidebar">
										@include('components.general-settings-sidebar')
									</div>
								</div>
							</div> <!-- end card body -->
						</div> <!-- end card -->

					</div> <!-- end col -->

					<div class="col-xl-9 col-lg-12">

						<div class="card mb-0">
							<div class="card-body">
								<div class="border-bottom mb-3 pb-3">
									<h5 class="mb-0 fs-17">Profile</h5>
								</div>
								<form action="profile-settings.html">
									<div class="mb-3">
										<h6 class="mb-1">Employee Information</h6>
										<p class="mb-0">Provide the information below</p>
									</div>
									<div class="mb-3">
										<div class="profile-upload d-flex align-items-center">
											<div class="profile-upload-img avatar avatar-xxl border border-dashed rounded position-relative flex-shrink-0">
												<span><i class="ti ti-photo"></i></span>
												<img id="ImgPreview" src="assets/img/profiles/avatar-02.jpg" alt="img" class="preview1">
												<a href="javascript:void(0);" id="removeImage1" class="profile-remove">
													<i class="ti ti-x"></i>
												</a>
											</div>
											<div class="profile-upload-content ms-3">
												<label class="d-inline-flex align-items-center position-relative btn btn-primary btn-sm mb-2">
													<i class="ti ti-file-broken me-1"></i>Upload File
													<input type="file" id="imag" class="input-img position-absolute w-100 h-100 opacity-0 top-0 end-0">
												</label>
												<p class="mb-0">JPG, GIF or PNG. Max size of 800K</p>
											</div>
										</div>
									</div>
									<div class="border-bottom mb-3">
										
										<!-- start row -->
										<div class="row">

											<div class="col-md-4">
												<div class="mb-3">
													<label class="form-label">
														First Name <span class="text-danger">*</span>
													</label>
													<input type="text" class="form-control">
												</div>
											</div> <!-- end col -->

											<div class="col-md-4">
												<div class="mb-3">
													<label class="form-label">
														Last Name <span class="text-danger">*</span>
													</label>
													<input type="text" class="form-control">
												</div>
											</div> <!-- end col -->

											<div class="col-md-4">
												<div class="mb-3">
													<label class="form-label">
														User Name <span class="text-danger">*</span>
													</label>
													<input type="text" class="form-control">
												</div>
											</div> <!-- end col -->

											<div class="col-md-4">
												<div class="mb-3">
													<label class="form-label">
														Phone Number <span class="text-danger">*</span>
													</label>
													<input type="text" class="form-control">
												</div>
											</div> <!-- end col -->

											<div class="col-md-4">
												<div class="mb-3">
													<label class="form-label">
														Email <span class="text-danger">*</span>
													</label>
													<input type="text" class="form-control">
												</div>
											</div> <!-- end col -->

										</div>
										<!-- end row -->

									</div>
									<div class="border-bottom mb-3">
										<div class="mb-3">
											<h6 class="mb-1">Address</h6>
											<p class="mb-0">Please enter the address details</p>
										</div>

										<!-- start row -->
										<div class="row">

											<div class="col-md-12">
												<div class="mb-3">
													<label class="form-label">
														Address
													</label>
													<input type="text" class="form-control">
												</div>
											</div> <!-- end col -->
											<div class="col-lg-6 col-md-6">
												<div class="mb-3">
													<label class="form-label">
														Country
													</label>
													<select class="select">
														<option>United States</option>
														<option>Canada</option>
														<option>Germany</option>
														<option>France</option>
													</select>
												</div>
											</div> <!-- end col -->

											<div class="col-lg-6 col-md-6">
												<div class="mb-3">
													<label class="form-label">
														State / Province
													</label>
													<select class="select">
														<option>California</option>
														<option>New York</option>
														<option>Texas</option>
														<option>Florida</option>
													</select>
												</div>
											</div> <!-- end col -->

											<div class="col-lg-6 col-md-6">
												<div class="mb-3">
													<label class="form-label">
														City
													</label>
													<select class="select">
														<option>Los Angeles</option>
														<option>San Diego</option>
														<option>Fresno</option>
														<option>San Francisco</option>
													</select>
												</div>
											</div> <!-- end col -->

											<div class="col-lg-6 col-md-6">
												<div class="mb-3">
													<label class="form-label">
														Postal Code
													</label>
													<input type="text" class="form-control">
												</div>
											</div> <!-- end col -->

										</div>
										<!-- end row -->

									</div>
									<div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
										<a href="#" class="btn btn-sm btn-light">Cancel</a>
										<button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
									</div>
								</form>
							</div> <!-- end card body -->
						</div> <!-- end card -->

					</div> <!-- end col -->
				
				</div>
				<!-- end row -->

            </div>
            <!-- End Content -->            

          
        </div>

        <!-- ========================
			End Page Content
		========================= -->

		<!-- Change Password -->
		<div class="modal fade" id="change_password" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Change Password</h5>
						<button class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle" data-bs-dismiss="modal"
							aria-label="Close">
							<i class="ti ti-x"></i>
						</button>
					</div>
					<form action="">
						<div class="modal-body">
							<div class="mb-3">
								<label class="form-label">Current Password <span
										class="text-danger">*</span></label>
								<input type="password" class="form-control">
							</div>
							<div class="mb-3">
								<label class="form-label">New Password <span class="text-danger">*</span></label>
								<input type="password" class="form-control">
							</div>
							<div class="mb-0">
								<label class="form-label">Confirm Password <span
										class="text-danger">*</span></label>
								<input type="password" class="form-control">
							</div>
						</div>
						<div class="modal-footer">
							<div class="d-flex align-items-center">
								<a href="#" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</a>
								<button type="submit" class="btn btn-primary">Save</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- /Change Password -->

		<!-- Phone Number Password -->
		<div class="modal fade" id="change_phone_number" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Phone Number Verify</h5>
						<button class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle" data-bs-dismiss="modal"
							aria-label="Close">
							<i class="ti ti-x"></i>
						</button>
					</div>
					<form action="security.html">
						<div class="modal-body">
                            <div class="mb-3">
                                <div class="input-blocks">
                                    <label class="form-label">Current Phone Number <span class="text-danger">*</span></label>
                                    <input class="form-control phone" name="phone" type="text">
                                </div>
                            </div>
                            <div class="mb-3">
                                <div>
                                    <label class="form-label">New Phone Number <span class="text-danger">*</span></label>
                                    <input class="form-control phone" name="phone" type="text">
                                </div>
                                <p class="mt-2"><i class="ti ti-info-circle me-1"></i>New phone number only updated once you verified </p>
                            </div>
                            <div>
                                <label class="form-label">Current Password <span class="text-danger">*</span></label>
                                <div class="input-group input-group-flat pass-group">
                                    <input type="password" class="form-control pass-input">
                                    <span class="input-group-text toggle-password ">
                                        <i class="ti ti-eye-off"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
						<div class="modal-footer">
							<div class="d-flex align-items-center">
								<a href="#" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</a>
								<button type="submit" class="btn btn-primary">Save</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- /Phone Number Password -->

		<!-- Change Email Password -->
		<div class="modal fade" id="change_email" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Change Email Address</h5>
						<button class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle" data-bs-dismiss="modal"
							aria-label="Close">
							<i class="ti ti-x"></i>
						</button>
					</div>
					<form action="security-settings.html">
						<div class="modal-body">
                            <div class="mb-3">
                                <div class="input-blocks">
                                    <label class="form-label">Current Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control">
                                </div>
                            </div>
                            <div class="mb-3">
                                <div>
                                    <label class="form-label">New Email Address<span class="text-danger">*</span></label>
                                    <input class="form-control" type="email">
                                </div>
                                <p class="d-inline-flex align-items-center mt-1 mb-0"><i class="ti ti-info-circle me-1"></i>New email address only updated once you verified </p>
                            </div>
                            <div>
                                <label class="form-label">Confirm Password<span class="text-danger">*</span></label>
                                <div class="input-group input-group-flat pass-group">
                                    <input type="password" class="form-control pass-input">
                                    <span class="input-group-text toggle-password ">
                                        <i class="ti ti-eye-off"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
						<div class="modal-footer">
							<div class="d-flex align-items-center">
								<a href="#" class="btn btn-sm btn-light me-2" data-bs-dismiss="modal">Cancel</a>
								<button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- /Change Email Password -->

		<!-- Change Device Password -->
		<div class="modal fade" id="device_management" role="dialog">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Device Management</h5>
						<button class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle" data-bs-dismiss="modal"
							aria-label="Close">
							<i class="ti ti-x"></i>
						</button>
					</div>
					<form action="security-settings.html">
						<div class="modal-body">
							<!-- Start Table -->
							<div class="table-responsive custom-table">
								<table class="table table-nowrap">
									<thead class="table-light">
										<tr>
											<th>Device</th>
											<th>Date</th>
											<th>Location</th>
											<th>IP Address</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Chrome - Windows</td>
											<td>15 May 2025, 10:30AM</td>
											<td>New York / USA</td>
											<td>232.222.12.72</td>
											<td>
												<div class="dropdown table-action">
													<a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light ">
														<i class="ti ti-logout"></i>
													</a>
												</div>
											</td>
										</tr>
										<tr>
											<td>Safari Macos</td>
											<td>10 Apr 2025, 05:15 PM</td>
											<td>New York / USA</td>
											<td>224.111.12.75</td>
											<td>
												<div class="dropdown table-action">
													<a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light ">
														<i class="ti ti-logout"></i>
													</a>
												</div>
											</td>
										</tr>
										<tr>
											<td>Firefox Windows</td>
											<td>15 Mar 2025, 02:40 PM</td>
											<td>New York / USA</td>
											<td>111.222.13.28</td>
											<td>
												<div class="dropdown table-action">
													<a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light ">
														<i class="ti ti-logout"></i>
													</a>
												</div>
											</td>
										</tr>
										<tr>
											<td>Safari Macos</td>
											<td>15 Jan 2025, 08:00AM</td>
											<td>New York / USA</td>
											<td>120.517.26.17</td>
											<td>
												<div class="dropdown table-action">
													<a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light ">
														<i class="ti ti-logout"></i>
													</a>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!-- End Table -->
                        </div>
					</form>
				</div>
			</div>
		</div>
		<!-- /Change Device Password -->

		<!-- Change Account Activity -->
		<div class="modal fade" id="account_activity" role="dialog">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Account Activity</h5>
						<button class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle" data-bs-dismiss="modal"
							aria-label="Close">
							<i class="ti ti-x"></i>
						</button>
					</div>
					<form action="security-settings.html">
						<div class="modal-body">
							<!-- Start Table -->
							<div class="table-responsive custom-table">
								<table class="table table-nowrap">
									<thead class="table-light">
										<tr>
											<th>Date</th>
											<th>Device</th>
											<th>IP Address</th>
											<th>Location</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>15 May 2025, 10:30AM</td>
											<td>Chrome - Windows</td>
											<td>232.222.12.72</td>
											<td>New York / USA</td>
											<td>
												<div class="dropdown table-action">
													<a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light " data-bs-toggle="dropdown" aria-expanded="false">
														<i class="ti ti-logout"></i>
													</a>
												</div>
											</td>
										</tr>
										<tr>
											<td>10 Apr 2025, 05:15 PM</td>
											<td>Safari Macos</td>
											<td>224.111.12.75</td>
											<td>New York / USA</td>
											<td>
												<div class="dropdown table-action">
													<a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light " data-bs-toggle="dropdown" aria-expanded="false">
														<i class="ti ti-logout"></i>
													</a>
												</div>
											</td>
										</tr>
										<tr>
											<td>15 Mar 2025, 02:40 PM</td>
											<td>Firefox Windows</td>
											<td>111.222.13.28</td>
											<td>New York / USA</td>
											<td>
												<div class="dropdown table-action">
													<a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light " data-bs-toggle="dropdown" aria-expanded="false">
														<i class="ti ti-logout"></i>
													</a>
												</div>
											</td>
										</tr>
										<tr>
											<td>15 Jan 2025, 08:00AM</td>
											<td>Safari Macos</td>
											<td>120.517.26.17</td>
											<td>New York / USA</td>
											<td>
												<div class="dropdown table-action">
													<a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light " data-bs-toggle="dropdown" aria-expanded="false">
														<i class="ti ti-logout"></i>
													</a>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!-- End Table -->
                        </div>
					</form>
				</div>
			</div>
		</div>
		<!-- /Change Device Password -->

		<!-- Deactivate Account modal -->
        <div class="modal fade" id="deactive_account">
            <div class="modal-dialog modal-dialog-centered modal-sm rounded-0">
                <div class="modal-content rounded-0">
                    <div class="modal-body p-4 text-center position-relative">
                        <div class="mb-3 position-relative z-1">
                            <span class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle"><i class="ti ti-trash fs-24"></i></span>
                        </div>
                        <h5 class="mb-1">Deactive Account Confirmation</h5>
                        <p class="mb-3">Are you sure you want to deactivate your account.</p>
                        <div class="d-flex justify-content-center">
                            <a href="#" class="btn btn-sm btn-light position-relative z-1 me-2 w-100" data-bs-dismiss="modal">Cancel</a>
                            <a href="#" class="btn btn-sm btn-primary position-relative z-1 w-100" data-bs-dismiss="modal">Yes, Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Deactivate Account modal -->

		<!-- Delete Account -->
		<div class="modal fade" id="delete_account" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Deleting Your Account</h5>
						<button class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle" data-bs-dismiss="modal"
							aria-label="Close">
							<i class="ti ti-x"></i>
						</button>
					</div>
					<form action="security-settings.html">
						<div class="modal-body" data-select2-id="6">                        
							<p class="fw-medium fs-16 mb-1 text-dark">Why Are You Deleting Your Account?</p>
							<p class="fs-16 mb-3">We're sorry to see you go! To help us improve, please let us know your reason for deleting your account</p>
							<div class="row" data-select2-id="5">
								<div class="col-md-12">
									<div>
										<label class="form-label">Reason<span class="text-danger ms-1">*</span></label>
										<select id="deleteReason" class="select select2-hidden-accessible">
											<option value="">Select</option>
											<option value="no_use">No longer using the service</option>
											<option value="privacy">Privacy concerns</option>
											<option value="notifications">Too many notifications/emails</option>
											<option value="ux">Poor user experience</option>
											<option value="others">Others</option>
										</select>
									</div>
								</div><!-- end col -->
								<div class="col-md-12" id="otherReasonBox" style="display: none;">
									<label class="form-label">Please Specify<span class="text-danger ms-1">*</span></label>
									<textarea class="form-control" rows="3" placeholder="Description"></textarea>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<div class="d-flex align-items-center">
								<a href="#" class="btn btn-sm btn-light me-2" data-bs-dismiss="modal">Cancel</a>
								<button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Delete Password -->


@endsection