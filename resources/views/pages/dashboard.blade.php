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
                    {{-- <div class="gap-2 d-flex align-items-center flex-wrap">
						<div class="daterangepick form-control w-auto d-flex align-items-center">
							<i class="ti ti-calendar text-dark me-2"></i>
							<span class="reportrange-picker-field text-dark">23 May 2025 - 30 May 2025</span>
						</div>	
                        <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
                        <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
                    </div> --}}
                </div>
				<!-- End Page Header -->
 <!-- start row -->
				<div class="row">

					<div class="col-xl-3 col-sm-6 d-flex">
						<div class="card flex-fill">
							<div class="card-body position-relative">
								<p class="fw-medium mb-1">Total Leads</p>
								<h4 class="mb-3">Rs. 15,44,540</h4>
								<div class="d-flex align-items-center gap-2 flex-wrap">
									<span class="d-inline-flex align-items-center badge rounded-pill badge-soft-success border-0">+2.5%</span>
									<p class="text-dark mb-0">From Last Week</p>
								</div>
								<div class="custom-card-icon">
									<div class="avatar avatar-rounded avatar-lg bg-primary-gradient-100 position-absolute top-0 end-0">
										<img src="{{ url ('assets/img/icons/revenue-icon.svg')}}" alt="icon" class="img-fluid w-auto h-auto">
									</div>
								</div>
							</div>
						</div>
					</div> <!-- end col -->

					<div class="col-xl-3 col-sm-6 d-flex">
						<div class="card flex-fill">
							<div class="card-body position-relative">
								<p class="fw-medium mb-1">Total Colleges</p>
								<h4 class="mb-3">147</h4>
								<div class="d-flex align-items-center gap-2 flex-wrap">
									<span class="d-inline-flex align-items-center badge rounded-pill badge-soft-danger border-0">-21.15%</span>
									<p class="text-dark mb-0">From Last Week</p>
								</div>
								<div class="custom-card-icon">
									<div class="avatar avatar-rounded avatar-lg bg-info-gradient-100 position-absolute top-0 end-0">
										<img src="assets/img/icons/deal-icon.svg" alt="icon" class="img-fluid w-auto h-auto">
									</div>
								</div>
							</div>
						</div>
					</div> <!-- end col -->					

					<div class="col-xl-3 col-sm-6 d-flex">
						<div class="card flex-fill">
							<div class="card-body position-relative">
								<p class="fw-medium mb-1">Total Admissions</p>
								<h4 class="mb-3">32.8%</h4>
								<div class="d-flex align-items-center gap-2 flex-wrap">
									<span class="d-inline-flex align-items-center badge rounded-pill badge-soft-success border-0">+15.5%</span>
									<p class="text-dark mb-0">From Last Week</p>
								</div>
								<div class="custom-card-icon">
									<div class="avatar avatar-rounded avatar-lg bg-pink-gradient-100 position-absolute top-0 end-0">
										<img src="assets/img/icons/conversion-icon.svg" alt="icon" class="img-fluid w-auto h-auto">
									</div>
								</div>
							</div>
						</div>
					</div> <!-- end col -->					

					<div class="col-xl-3 col-sm-6 d-flex">
						<div class="card flex-fill">
							<div class="card-body position-relative">
								<p class="fw-medium mb-1">Total Consultants</p>
								<h4 class="mb-3">32.8%</h4>
								<div class="d-flex align-items-center gap-2 flex-wrap">
									<span class="d-inline-flex align-items-center badge rounded-pill badge-soft-success border-0">+15.5%</span>
									<p class="text-dark mb-0">From Last Week</p>
								</div>
								<div class="custom-card-icon">
									<div class="avatar avatar-rounded avatar-lg bg-pink-gradient-100 position-absolute top-0 end-0">
										<img src="assets/img/icons/conversion-icon.svg" alt="icon" class="img-fluid w-auto h-auto">
									</div>
								</div>
							</div>
						</div>
					</div> <!-- end col -->			
					
				</div>
                <!-- end row -->
<!-- start row -->
				<div class="row">

					<div class="col-xxl-8 col-xl-7 d-flex">
						<div class="card flex-fill">
							<div class="card-body pb-0">
								<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
									<h5 class="mb-0 fs-16 fw-bold d-inline-flex items-center"><span class="line-title d-block me-2"></span>Revenue Analytics</h5>
									<ul class="nav nav-tabs nav-solid-danger border rounded gap-2 p-1">
										<li class="nav-item"><a class="nav-link py-1 px-2 rounded active" href="#wekly" data-bs-toggle="tab">Weekly</a></li>
										<li class="nav-item"><a class="nav-link py-1 px-2 rounded" href="#monthly" data-bs-toggle="tab">Monthly</a></li>
										<li class="nav-item"><a class="nav-link py-1 px-2 rounded" href="#yearly" data-bs-toggle="tab">Yearly</a></li>
									</ul>
								</div>
								<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
									<div class="d-flex align-items-center flex-wrap gap-2">
										<h4 class="mb-0">495K</h4>
										<p class="mb-0">Revenue with Sales (USD)</p>
									</div>
									<div class="d-flex align-items-center flex-wrap gap-2">
										<div class="d-flex align-items-center border rounded px-2 py-1">
											<p class="d-flex align-items-center mb-0"><i class="ti ti-circle-filled fs-8 text-primary me-1"></i>Revenue</p>
										</div>
										<div class="d-flex align-items-center border rounded px-2 py-1">
											<p class="d-flex align-items-center mb-0"><i class="ti ti-circle-filled fs-8 text-light-500 me-1"></i>Sales</p>
										</div>
									</div>
								</div>
								<div id="performance-stats"></div>
							</div>
						</div> <!-- end card -->
					</div> <!-- end col -->

					<div class="col-xxl-4 col-xl-5 d-flex">
						<div class="card flex-fill">
							<div class="card-body">
								<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-0">
									<h5 class="mb-0 fs-16 fw-bold d-inline-flex items-center"><span class="line-title d-block me-2"></span>Traffic Sources</h5>
									<a href="" class="btn btn-sm btn-icon btn-outline-light"><i class="ti ti-arrow-right"></i></a>
								</div>
								<div id="traffic-sources-chart"></div>
							</div>
							<div class="mb-1">									
								<div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
									<p class="text-dark d-flex align-items-center mb-0"><i class="ti ti-circle-filled text-success fs-8 me-1"></i>Organic Search</p>
									<p class="text-dark fw-semibold mb-0">6598</p>
								</div>
									
								<div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
									<p class="text-dark d-flex align-items-center mb-0"><i class="ti ti-circle-filled text-info fs-8 me-1"></i>Direct Traffic</p>
									<p class="text-dark fw-semibold mb-0">2458</p>
								</div>
									
								<div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
									<p class="text-dark d-flex align-items-center mb-0"><i class="ti ti-circle-filled text-warning fs-8 me-1"></i>Referral Traffic</p>
									<p class="text-dark fw-semibold mb-0">1456</p>
								</div>
									
								<div class="px-3 pt-2 pb-3 d-flex align-items-center justify-content-between">
									<p class="text-dark d-flex align-items-center mb-0"><i class="ti ti-circle-filled text-purple fs-8 me-1"></i>Social Media</p>
									<p class="text-dark fw-semibold mb-0">845</p>
								</div>
									
									
							</div>
						</div> <!-- end card -->
					</div> <!-- end col -->

				</div>
                <!-- end row -->
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
			<td><a href="">Collins</a></td>
			<td>
				<div class="d-flex align-items-center">
					<a href="" class="avatar avatar-rounded border">
						<img class="w-auto h-auto" src="{{ url ('assets/img/icons/company-icon-01.svg')}}" alt="User Image">
					</a>
					<div class="ms-2">
						<h6 class="fs-14 fw-medium mb-0">
							<a href="" class="d-flex flex-column">NovaWave LLC</a>
						</h6>
					</div>
				</div>
			</td>
			<td>+1 875455453</td>
			<td><span class="badge badge-pill bg-success">Closed</span></td>
		</tr>
		<tr>
			<td><a href="">Konopelski</a></td>
			<td>
				<div class="d-flex align-items-center">
					<a href="" class="avatar avatar-rounded border">
						<img class="w-auto h-auto" src="assets/img/icons/company-icon-02.svg" alt="User Image">
					</a>
					<div class="ms-2">
						<h6 class="fs-14 fw-medium mb-0">
							<a href="" class="d-flex flex-column">BlueSky Industries</a>
						</h6>
					</div>
				</div>
			</td>
			<td>+1 989757485</td>
			<td><span class="badge badge-pill bg-success">Closed</span></td>
		</tr>
		<tr>
			<td><a href="">Adams</a></td>
			<td>
				<div class="d-flex align-items-center">
					<a href="" class="avatar avatar-rounded border">
						<img class="w-auto h-auto" src="assets/img/icons/company-icon-03.svg" alt="User Image">
					</a>
					<div class="ms-2">
						<h6 class="fs-14 fw-medium mb-0">
							<a href="" class="d-flex flex-column">Silver Hawk</a>
						</h6>
					</div>
				</div>
			</td>
			<td>+1 546555455</td>
			<td><span class="badge badge-pill bg-success">Closed</span></td>
		</tr>
		<tr>
			<td><a href="">Schumm</a></td>
			<td>
				<div class="d-flex align-items-center">
					<a href="" class="avatar avatar-rounded border">
						<img class="w-auto h-auto" src="assets/img/icons/company-icon-04.svg" alt="User Image">
					</a>
					<div class="ms-2">
						<h6 class="fs-14 fw-medium mb-0">
							<a href="" class="d-flex flex-column">Summit Peak</a>
						</h6>
					</div>
				</div>
			</td>
			<td>+1 454478787</td>
			<td><span class="badge badge-pill bg-warning">Contacted</span></td>
		</tr>
		<tr>
			<td><a href="">Wisozk</a></td>
			<td>
				<div class="d-flex align-items-center">
					<a href="" class="avatar avatar-rounded border">
						<img class="w-auto h-auto" src="assets/img/icons/company-icon-05.svg" alt="User Image">
					</a>
					<div class="ms-2">
						<h6 class="fs-14 fw-medium mb-0">
							<a href="" class="d-flex flex-column">RiverStone Ltd</a>
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