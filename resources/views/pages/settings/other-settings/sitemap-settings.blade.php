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

						@include('components.other-settings-sidebar')

					</div> <!-- end col -->

							<div class="col-xl-9 col-lg-12">

						<!-- Settings Info -->
						<div class="card mb-0">
							<div class="card-body">
								<div class="border-bottom mb-3 pb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
									<h4 class="fs-17 mb-0">Sitemap</h4>
									<a href="javascript:void(0)" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add_sitemap"><i class="ti ti-square-rounded-plus-filled me-1"></i>Generate Sitemap</a>
								</div>

								<!-- Start Table -->
								<div class="table-responsive custom-table">
									<table class="table table-nowrap">
										<thead class="table-light">
											<tr>
												<th>URL</th>
												<th>File Name</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><a href="javascript:void(0);">https://localhost/crms</a></td>
												<td>sitemap18725604.xml</td>
												<td>
													<div class="dropdown table-action">
														<a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light " data-bs-toggle="dropdown" aria-expanded="false">
															<i class="ti ti-dots-vertical"></i>
														</a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_sitemap">
																<i class="ti ti-edit text-blue me-1"></i>Edit
															</a>
															<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_sitemap">
																<i class="ti ti-trash text-blue me-1"></i>Delete
															</a>
														</div>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<!-- End Table -->
							</div>
						</div>
						<!-- /Settings Info -->

					</div>
				</div>
				<!-- end row -->

            </div>
            <!-- End Content -->            

          
        </div>

        <!-- ========================
			End Page Content
		========================= -->
	<!-- Add sitemap -->
		<div class="modal fade" id="add_sitemap" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Add Sitemap</h5>
						<button type="button"
                            class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                            data-bs-dismiss="modal" aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
					</div>
					<form action="sitemap.html">
						<div class="modal-body">
							<div class="mb-0">
								<label class="form-label">Sitemap URL <span class="text-danger">*</span></label>
								<input type="text" class="form-control">
							</div>
						</div>
						<div class="modal-footer">
							<div class="d-flex align-items-center justify-content-end m-0">
								<a href="#" class="btn btn-sm btn-light me-2" data-bs-dismiss="modal">Cancel</a>
								<button type="submit" class="btn btn-sm btn-primary">Create New</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- /Add sitemap -->

        <!-- Edit sitemap -->
		<div class="modal fade" id="edit_sitemap" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Edit Sitemap</h5>
						<button type="button"
                            class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                            data-bs-dismiss="modal" aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
					</div>
					<form action="sitemap.html">
						<div class="modal-body">
							<div class="mb-0">
								<label class="form-label">Sitemap URL <span class="text-danger">*</span></label>
								<input type="text" class="form-control" value="sitemap18725604.xml">
							</div>
						</div>
						<div class="modal-footer">
							<div class="d-flex align-items-center justify-content-end m-0">
								<a href="#" class="btn btn-sm btn-light me-2" data-bs-dismiss="modal">Cancel</a>
								<button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- /Edit sitemap -->

		<!-- delete modal -->
        <div class="modal fade" id="delete_sitemap">
            <div class="modal-dialog modal-dialog-centered modal-sm rounded-0">
                <div class="modal-content rounded-0">
                    <div class="modal-body p-4 text-center position-relative">
                        <div class="mb-3 position-relative z-1">
                            <span class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle"><i class="ti ti-trash fs-24"></i></span>
                        </div>
                        <h5 class="mb-1">Delete Confirmation</h5>
                        <p class="mb-3">Are you sure you want to remove sitemap you selected.</p>
                        <div class="d-flex justify-content-center">
                            <a href="#" class="btn btn-sm btn-light position-relative z-1 me-2 w-100" data-bs-dismiss="modal">Cancel</a>
                            <a href="#" class="btn btn-sm btn-primary position-relative z-1 w-100" data-bs-dismiss="modal">Yes, Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- delete modal -->

@endsection