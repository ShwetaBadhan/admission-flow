@extends('layout.master')
@section('content')
 <!-- ========================
			Start Page Content
		========================= -->
         
        <div class="page-wrapper">

            <!-- Start Content -->
            <div class="content pb-0">

                <!-- Page Header -->
                <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                    <div>
                        <h4 class="mb-0">Leads Dashboard</h4>
                    </div>
                    <div class="gap-2 d-flex align-items-center flex-wrap">
						<div class="daterangepick form-control w-auto d-flex align-items-center">
							<i class="ti ti-calendar text-dark me-2"></i>
							<span class="reportrange-picker-field text-dark">23 May 2025 - 30 May 2025</span>
						</div>	
                        <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
                        <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
                    </div>
                </div>
				<!-- End Page Header -->

                <!-- start row -->
                <div class="row">

					<div class="col-xl-6 d-flex">		
						<div class="card flex-fill">
							<div class="card-header">
								<div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
									<h6 class="mb-0">Recently Created Leads</h6>
									<div class="dropdown">
										<a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown" href="javascript:void(0);">
											Last 30 days
										</a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="javascript:void(0);" class="dropdown-item">
												Last 15 days
											</a>
											<a href="javascript:void(0);" class="dropdown-item">
												Last 30 days
											</a>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="table-responsive custom-table">
									<table class="table table-nowrap dataTable" id="lead-project">
										<thead class="table-light">
											<tr>
												<th>Lead Name</th>
												<th>Company Name</th>
												<th>Phone</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
		<tr>
			<td><a href="leads-details.html">Collins</a></td>
			<td>
				<div class="d-flex align-items-center">
					<a href="company-details.html" class="avatar avatar-rounded border">
						<img class="w-auto h-auto" src="assets/img/icons/company-icon-01.svg" alt="User Image">
					</a>
					<div class="ms-2">
						<h6 class="fs-14 fw-medium mb-0">
							<a href="company-details.html" class="d-flex flex-column">NovaWave LLC</a>
						</h6>
					</div>
				</div>
			</td>
			<td>+1 875455453</td>
			<td><span class="badge badge-pill bg-success">Closed</span></td>
		</tr>
		<tr>
			<td><a href="leads-details.html">Konopelski</a></td>
			<td>
				<div class="d-flex align-items-center">
					<a href="company-details.html" class="avatar avatar-rounded border">
						<img class="w-auto h-auto" src="assets/img/icons/company-icon-02.svg" alt="User Image">
					</a>
					<div class="ms-2">
						<h6 class="fs-14 fw-medium mb-0">
							<a href="company-details.html" class="d-flex flex-column">BlueSky Industries</a>
						</h6>
					</div>
				</div>
			</td>
			<td>+1 989757485</td>
			<td><span class="badge badge-pill bg-success">Closed</span></td>
		</tr>
		<tr>
			<td><a href="leads-details.html">Adams</a></td>
			<td>
				<div class="d-flex align-items-center">
					<a href="company-details.html" class="avatar avatar-rounded border">
						<img class="w-auto h-auto" src="assets/img/icons/company-icon-03.svg" alt="User Image">
					</a>
					<div class="ms-2">
						<h6 class="fs-14 fw-medium mb-0">
							<a href="company-details.html" class="d-flex flex-column">Silver Hawk</a>
						</h6>
					</div>
				</div>
			</td>
			<td>+1 546555455</td>
			<td><span class="badge badge-pill bg-success">Closed</span></td>
		</tr>
		<tr>
			<td><a href="leads-details.html">Schumm</a></td>
			<td>
				<div class="d-flex align-items-center">
					<a href="company-details.html" class="avatar avatar-rounded border">
						<img class="w-auto h-auto" src="assets/img/icons/company-icon-04.svg" alt="User Image">
					</a>
					<div class="ms-2">
						<h6 class="fs-14 fw-medium mb-0">
							<a href="company-details.html" class="d-flex flex-column">Summit Peak</a>
						</h6>
					</div>
				</div>
			</td>
			<td>+1 454478787</td>
			<td><span class="badge badge-pill bg-warning">Contacted</span></td>
		</tr>
		<tr>
			<td><a href="leads-details.html">Wisozk</a></td>
			<td>
				<div class="d-flex align-items-center">
					<a href="company-details.html" class="avatar avatar-rounded border">
						<img class="w-auto h-auto" src="assets/img/icons/company-icon-05.svg" alt="User Image">
					</a>
					<div class="ms-2">
						<h6 class="fs-14 fw-medium mb-0">
							<a href="company-details.html" class="d-flex flex-column">RiverStone Ltd</a>
						</h6>
					</div>
				</div>
			</td>
			<td>+1 1245427875</td>
			<td><span class="badge badge-pill bg-success">Closed</span></td>
		</tr>
	</tbody>
									</table>
								</div>
							</div> <!-- end card body -->
                        </div> <!-- end card -->
					</div> <!-- end col --> 

					<div class="col-xl-6 d-flex">		
						<div class="card flex-fill">
							<div class="card-header">
								<div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
									<h6 class="mb-0">Projects By Stage</h6>
									<div class="dropdown">
										<a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown" href="javascript:void(0);">
											Last 30 Days
										</a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="javascript:void(0);" class="dropdown-item">
												Last 30 Days
											</a>
											<a href="javascript:void(0);" class="dropdown-item">
												Last 15 Days
											</a>
											<a href="javascript:void(0);" class="dropdown-item">
												Last 7 Days
											</a>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div id="leadpiechart" class="text-center"></div>
							</div> <!-- end card body -->
                        </div> <!-- end card -->
					</div> <!-- end col --> 

				</div>
                <!-- end row -->

                <!-- start row -->
				<div class="row">

					<div class="col-md-12 d-flex">		
						<div class="card flex-fill">
							<div class="card-header">
								<div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
									<h6 class="mb-0">Projects By Stage</h6>
									<div class="d-flex align-items-center flex-wrap row-gap-3">
										<div class="dropdown me-2">
											<a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown"
												href="javascript:void(0);">
												Sales Pipeline
											</a>
											<div class="dropdown-menu dropdown-menu-end">
												<a href="javascript:void(0);" class="dropdown-item">
													Marketing Pipeline
												</a>
												<a href="javascript:void(0);" class="dropdown-item">
													Sales Pipeline
												</a>
												<a href="javascript:void(0);" class="dropdown-item">
													Email
												</a>
												<a href="javascript:void(0);" class="dropdown-item">
													Chats
												</a>
												<a href="javascript:void(0);" class="dropdown-item">
													Operational
												</a>
											</div>
										</div>
										<div class="dropdown">
											<a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown"
												href="javascript:void(0);">
												Last 30 Days
											</a>
											<div class="dropdown-menu dropdown-menu-end">
												<a href="javascript:void(0);" class="dropdown-item">
													Last 30 Days
												</a>
												<a href="javascript:void(0);" class="dropdown-item">
													Last 15 Days
												</a>
												<a href="javascript:void(0);" class="dropdown-item">
													Last 7 Days
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body py-0">
								<div id="contact-report"></div>
							</div> <!-- end card body -->
                        </div> <!-- end card -->
					</div> <!-- end col --> 

				</div>
                <!-- end row -->

                <!-- start row -->
				<div class="row">

                    <div class="col-md-6 d-flex">	
						<div class="card flex-fill">
							<div class="card-header">
								<div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
									<h6 class="mb-0">Lost Deals Stage</h6>
									<div class="d-flex align-items-center flex-wrap row-gap-3">
										<div class="dropdown me-2">
											<a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown" href="javascript:void(0);">
												Marketing Pipeline
											</a>
											<div class="dropdown-menu dropdown-menu-end">
												<a href="javascript:void(0);" class="dropdown-item">
													Marketing Pipeline
												</a>
												<a href="javascript:void(0);" class="dropdown-item">
													Sales Pipeline
												</a>
												<a href="javascript:void(0);" class="dropdown-item">
													Email
												</a>
												<a href="javascript:void(0);" class="dropdown-item">
													Chats
												</a>
												<a href="javascript:void(0);" class="dropdown-item">
													Operational
												</a>
											</div>
										</div>
										<div class="dropdown">
											<a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown"
												href="javascript:void(0);">
												Last 3 months
											</a>
											<div class="dropdown-menu dropdown-menu-end">
												<a href="javascript:void(0);" class="dropdown-item">
													Last 3 months
												</a>
												<a href="javascript:void(0);" class="dropdown-item">
													Last 6 months
												</a>
												<a href="javascript:void(0);" class="dropdown-item">
													Last 12 months
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body py-0">
								<div id="last-chart"></div>
							</div> <!-- end card body -->
                        </div> <!-- end card -->
					</div> <!-- end col --> 

					<div class="col-md-6 d-flex">		
						<div class="card w-100">
							<div class="card-header">
								<div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
									<h6 class="mb-0">Won Deals Stage</h6>
									<div class="d-flex align-items-center flex-wrap row-gap-3">
										<div class="dropdown me-2">
											<a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown" href="javascript:void(0);">
												Marketing Pipeline
											</a>
											<div class="dropdown-menu dropdown-menu-end">
												<a href="javascript:void(0);" class="dropdown-item">
													Marketing Pipeline
												</a>
												<a href="javascript:void(0);" class="dropdown-item">
													Sales Pipeline
												</a>
												<a href="javascript:void(0);" class="dropdown-item">
													Email
												</a>
												<a href="javascript:void(0);" class="dropdown-item">
													Chats
												</a>
												<a href="javascript:void(0);" class="dropdown-item">
													Operational
												</a>
											</div>
										</div>
										<div class="dropdown">
											<a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown" href="javascript:void(0);">
												Last 3 months
											</a>
											<div class="dropdown-menu dropdown-menu-end">
												<a href="javascript:void(0);" class="dropdown-item">
													Last 3 months
												</a>
												<a href="javascript:void(0);" class="dropdown-item">
													Last 6 months
												</a>
												<a href="javascript:void(0);" class="dropdown-item">
													Last 12 months
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body py-0">
								<div id="won-chart"></div>
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
@endsection