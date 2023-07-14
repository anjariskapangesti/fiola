<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>
        FIOLA &mdash; {{ $title }}
    </title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('vendor/niceadmin/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('vendor/niceadmin/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('vendor/niceadmin/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/niceadmin/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/niceadmin/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/niceadmin/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/niceadmin/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/niceadmin/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/niceadmin/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-6.4.0/css/all.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"> --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css"
        integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" crossorigin="anonymous" /> --}}

    <!-- Template Main CSS File -->
    <link href="{{ asset('vendor/niceadmin/css/style.css') }}" rel="stylesheet">
    @stack('styles')

    <!-- =======================================================
  * Template Name: NiceAdmin - v2.5.0
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    <!-- ======= Header ======= -->
    @include('website.layouts.header')
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    @include('website.layouts.sidebar')
    <!-- End Sidebar-->

    <main id="main" class="main">
        
        @yield('content')
        
    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>IT Development</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('vendor/jquery/jquery-3.5.1.js') }}"></script>
    <script src="{{ asset('vendor/niceadmin/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('vendor/niceadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/niceadmin/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('vendor/niceadmin/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('vendor/niceadmin/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('vendor/niceadmin/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('vendor/niceadmin/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('vendor/niceadmin/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('vendor/plugins/toastr/toastr.min.js') }}"></script>
    {{-- <script src="{{ asset('vendor/fontawesome-6.4.0/js/all.min.js') }}"></script> --}}


    <!-- Template Main JS File -->
    <script src="{{ asset('vendor/niceadmin/js/main.js') }}"></script>

    @stack('scripts')

</body>

</html>
