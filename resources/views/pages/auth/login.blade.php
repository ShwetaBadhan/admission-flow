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

    <!-- Begin Wrapper -->
    <div class="main-wrapper">

       <div class="overflow-hidden p-3 acc-vh">
            
            <!-- start row -->
            <div class="row vh-100 w-100 g-0"> 

                <div class="col-lg-6 vh-100 overflow-y-auto overflow-x-hidden">

                     <!-- start row -->
                    <div class="row">

                        <div class="col-md-10 mx-auto">
                         <form action="{{ route('login.submit') }}" method="POST"
                            class=" vh-100 d-flex justify-content-between flex-column p-4 pb-0">
                            @csrf
                           
                                <div class="text-center mb-4 auth-logo">
                                    <img src="assets/img/logo.svg" class="img-fluid" alt="Logo">
                                </div>
                                <div>
                                    <div class="mb-3">
                                        <h3 class="mb-2">Sign In</h3>
                                        <p class="mb-0">Access the CRMS panel using your email and passcode.</p>
                                    </div>
                                    {{-- 🔴 ERROR MESSAGE --}}
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                {{ $errors->first() }}
                                            </div>
                                        @endif
                                    <div class="mb-3">
                                        <label class="form-label">Email Address</label>
                                        <div class="input-group input-group-flat">
                                            <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                                            <span class="input-group-text">
                                                <i class="ti ti-mail"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <div class="input-group input-group-flat pass-group">
                                            <input type="password" name="password" class="form-control pass-input">
                                            <span class="input-group-text toggle-password ">
                                                <i class="ti ti-eye-off"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="form-check form-check-md d-flex align-items-center">
                                            <input class="form-check-input mt-0" type="checkbox" value="" name="remember" id="checkebox-md" checked="">
                                            <label class="form-check-label text-dark ms-1" for="checkebox-md">
                                                Remember Me
                                            </label>
                                        </div>
                                        <div class="text-end">
                                            <a href="{{ route('forgot-password') }}" class="link-danger fw-medium link-hover">Forgot Password?</a>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary w-100">Sign In</button>
                                    </div>
                                   
                                    
                                </div>
                                <div class="text-center pb-4">
                                    <p class="text-dark mb-0">Copyright &copy; <script >document.write(new Date().getFullYear())</script> - Vibrantick Infotech Solutions</p>
                                </div>
                            </form>
                        </div> <!-- end col -->

                    </div>
                    <!-- end row -->

                </div>

                <div class="col-lg-6 account-bg-01"></div> <!-- end col -->

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