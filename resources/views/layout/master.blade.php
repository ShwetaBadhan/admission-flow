<!DOCTYPE html>
<html lang="en">

<head>

	<!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Leads Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="">
	<meta name="robots" content="index, follow">
	
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{url ('assets/img/favicon.png')}}">

    <!-- Apple Icon -->
    <link rel="apple-touch-icon" href="{{url ('assets/img/apple-icon.png')}}">

    <!-- Theme Config Js -->
    <script src="{{url ('assets/js/theme-script.js')}}" ></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{url ('assets/css/bootstrap.min.css')}}">

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{url ('assets/plugins/tabler-icons/tabler-icons.min.css')}}">

    <!-- Simplebar CSS -->
    <link rel="stylesheet" href="{{url ('assets/plugins/simplebar/simplebar.min.css')}}">

    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{url ('assets/plugins/datatables/css/dataTables.bootstrap5.min.css')}}">

	<!-- Daterangepicker CSS -->
	<link rel="stylesheet" href="{{url ('assets/plugins/daterangepicker/daterangepicker.css')}}">

     <!-- Choices CSS -->
    <link rel="stylesheet" href="{{url ('assets/plugins/choices.js/public/assets/styles/choices.min.css')}}">

    <!-- Select2 CSS -->
	<link rel="stylesheet" href="{{url ('assets/plugins/select2/css/select2.min.css')}}">

    <!-- Quill CSS -->
    <link rel="stylesheet" href="{{url ('assets/plugins/quill/quill.snow.css')}}">


    <!-- Mobile CSS-->
    <link rel="stylesheet" href="{{url ('assets/plugins/intltelinput/css/intlTelInput.css')}}">
    <link rel="stylesheet" href="{{url ('assets/plugins/intltelinput/css/demo.css')}}">

     <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="{{url ('assets/plugins/flatpickr/flatpickr.min.css')}}">
    <script src="{{url ('assets/json/dashboard.js')}}"></script>
    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{url ('assets/plugins/tabler-icons/tabler-icons.min.css')}}">

   <!-- Sweetalert2 CSS -->
    <link rel="stylesheet" href="{{ url ('assets/plugins/sweetalert2/sweetalert2.min.css')}}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{url ('assets/css/style.css')}}">

</head>


@include('components.top-header')

@yield('content')

@include('components.sidebar')
@include('components.copyright')



   <!-- jQuery -->
    <script src="{{ url ('assets/js/jquery-3.7.1.min.js')}}" ></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ url ('assets/js/bootstrap.bundle.min.js')}}" ></script>  

    <!-- Daterangepikcer JS -->
	<script src="{{ url ('assets/js/moment.min.js')}}" ></script>
	<script src="{{ url ('assets/plugins/daterangepicker/daterangepicker.js')}}" ></script>
    
    <!-- Datatable JS -->
    <script src="{{ url ('assets/plugins/datatables/js/jquery.dataTables.min.js')}}" ></script>
    <script src="{{ url ('assets/plugins/datatables/js/dataTables.bootstrap5.min.js')}}" ></script>

    <!-- Select2 JS -->
	<script src="{{ url ('assets/plugins/select2/js/select2.min.js')}}" ></script>

    <!-- Choices Js -->	
    <script src="{{ url ('assets/plugins/choices.js/public/assets/scripts/choices.min.js')}}" ></script>

    <!-- Mobile Input -->
    <script src="{{ url ('assets/plugins/intltelinput/js/intlTelInput.js')}}" ></script>

    <!-- Drag Card -->
	<script src="{{ url ('assets/js/jquery-ui.min.js')}}" ></script>
	<script src="{{ url ('assets/js/jquery.ui.touch-punch.min.js')}}" ></script>

    <!-- Quill JS -->
    <script src="{{ url ('assets/plugins/quill/quill.min.js')}}" ></script>

	<!-- Simplebar JS -->
	<script src="{{ url ('assets/plugins/simplebar/simplebar.min.js')}}" ></script>

    <!-- Flatpickr JS -->
    <script src="{{ url ('assets/plugins/flatpickr/flatpickr.min.js')}}" ></script>


    	<!-- Apexchart JS -->
	<script src="{{ url ('assets/plugins/apexchart/apexcharts.min.js')}}"></script>
	<script src="{{ url ('assets/plugins/apexchart/chart-data.js')}}"></script>

     <!-- Sweet Alerts js -->
    <script src="{{ url ('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{ url ('assets/js/sweetalerts.js')}}"></script>

    <!-- Main JS -->
    <script src="{{ url ('assets/js/script.js')}}" ></script>
@stack('scripts')



</html>