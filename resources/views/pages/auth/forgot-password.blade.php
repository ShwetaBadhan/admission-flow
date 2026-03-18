<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="Dreams Technologies">
	<meta name="robots" content="index, follow">
	
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ url ('assets/img/favicon.png')}}">

    <!-- Apple Icon -->
    <link rel="apple-touch-icon" href="{{ url ('assets/img/apple-icon.png')}}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ url ('assets/css/bootstrap.min.css')}}">

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{ url ('assets/plugins/tabler-icons/tabler-icons.min.css')}}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ url ('assets/css/style.css')}}">

</head>

<body class="account-page bg-white">

      <!-- Main Wrapper -->
    <div class="main-wrapper">

        <div class="overflow-hidden p-3 acc-vh">
            
            <!-- start row -->
            <div class="row vh-100 w-100 g-0"> 

                <div class="col-lg-6 vh-100  overflow-y-auto overflow-x-hidden">

                    <!-- start row -->
                    <div class="row">

                        <div class="col-md-10 mx-auto">
                            <form action="email-verification.html" class=" vh-100 d-flex justify-content-between flex-column p-4 pb-0">
                                <div class="text-center mb-3 auth-logo">
                                    <img src="assets/img/logo.svg" class="img-fluid" alt="Logo">
                                </div>
                                <div>
                                    <div class="mb-3">
                                        <h3 class="mb-2">Forgot Password?</h3>
                                        <p class="mb-0">If you forgot your password, well, then we’ll email you instructions to reset your password.</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email Address</label>
                                        <div class="input-group input-group-flat">
                                            <input type="email" class="form-control">
                                            <span class="input-group-text">
                                                <i class="ti ti-mail"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                                    </div>
                                    <div class="mb-3 text-center">
                                        <p class="mb-0">Return to <a href="{{ route('login') }}" class="link-indigo fw-bold link-hover"> Login</a></p>
                                    </div>
                                   
                                   
                                </div>
                                <div class="text-center pb-4">
                                    <p class="text-dark mb-0">Copyright &copy; <script type="13930ade56636f15990e6d5d-text/javascript">document.write(new Date().getFullYear())</script> - CRMS</p>
                                </div>
                            </form>
                        </div> <!-- end col -->

                    </div>
                    <!-- end row -->

                </div>

                <div class="col-lg-6 d-none d-lg-block account-bg-03"></div> <!-- end col -->

            </div>
            <!-- end row -->

        </div>

    </div>
    <!-- End Wrapper -->

    <!-- jQuery -->
    <script src="{{ url ('assets/js/jquery-3.7.1.min.js')}}" ></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ url ('assets/js/bootstrap.bundle.min.js')}}" ></script>    

    <!-- Main JS -->
    <script src="{{ url ('assets/js/script.js')}}" ></script>

</html>