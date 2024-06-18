<!DOCTYPE html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>FIOLA</title>

    <meta name="description" content="" />

    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo-fiola.png') }}" />
    <link href="{{ asset('vendor/niceadmin/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('vendor/materio/assets/vendor/fonts/materialdesignicons.css') }}">

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="{{ asset('vendor/materio/assets/vendor/libs/node-waves/node-waves.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/materio/assets/vendor/css/core.css') }}"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('vendor/materio/assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('vendor/materio/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet"
        href="{{ asset('vendor/materio/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('vendor/materio/assets/vendor/css/pages/page-auth.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('vendor/materio/assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('vendor/materio/assets/js/config.js') }}"></script>
</head>

<body>
    <!-- Content -->
    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4" style="max-width: 700px !important;">
                <!-- Login -->
                <div class="card p-2">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mt-5">
                        <a href="{{ route('website.type') }}" class="app-brand-link gap-2">
                            <span class="app-brand-text demo text-heading fw-semibold"
                                style="color: #9055fd !important; font-size: 36px;">FIOLA</span>
                        </a>
                    </div>
                    <!-- /Logo -->

                    <div class="card-body mt-2">
                        <h4 class="mb-4 text-center">Form & Ticket ITD Online Application</h4>
                        <h5 class="mb-2 text-center">Pilih Type Support :</h5>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('website.auth.login') }}" class="btn btn-primary btn-xl">FORM /
                                REQUEST</a>
                            <a href="{{ route('website.ticket.list') }}" class="btn btn-primary btn-xl">TICKET /
                                PROBLEM</a>
                        </div>
                        <hr>
                        <h5 class="mb-2 text-center">Tim ITD Support :</h5>
                        <table class="table table-responsive table-bordered mt-3 text-center">
                            <thead>
                                <tr>
                                    <th>Non Shift</th>
                                    <th>Shift 2</th>
                                    <th>Shift 3</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Rohmat Maulana Ishaq</td>
                                    <td>Handika</td>
                                    <td>Muhammad Diki Dwi Nugraha</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>
                                        <div class="d-flex justify-content-between">
                                            <a href="https://wa.me/6282125008160" target="_blank"
                                                class="btn btn-success"><i class="mdi mdi-whatsapp"></i></a>
                                            <a href="wa.me/6282125008160" class="btn btn-info"><i
                                                    class="mdi mdi-email"></i></a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-between">
                                            <a href="https://wa.me/6282125008160" target="_blank"
                                                class="btn btn-success"><i class="mdi mdi-whatsapp"></i></a>
                                            <a href="wa.me/6282125008160" class="btn btn-info"><i
                                                    class="mdi mdi-email"></i></a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-between">
                                            <a href="https://wa.me/6282125008160" target="_blank"
                                                class="btn btn-success"><i class="mdi mdi-whatsapp"></i></a>
                                            <a href="wa.me/6282125008160" class="btn btn-info"><i
                                                    class="mdi mdi-email"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        {{-- <p class="text-center">
                            <span>New on our platform?</span>
                            <a href="auth-register-basic.html">
                                <span>Create an account</span>
                            </a>
                        </p> --}}
                    </div>
                </div>
                <!-- /Login -->
                <img src="{{ asset('vendor/materio/assets/img/illustrations/tree-3.png') }}" alt="auth-tree"
                    class="authentication-image-object-left d-none d-lg-block" />
                <img src="{{ asset('vendor/materio/assets/img/illustrations/auth-basic-mask-light.png') }}"
                    class="authentication-image d-none d-lg-block" alt="triangle-bg"
                    data-app-light-img="illustrations/auth-basic-mask-light.png"
                    data-app-dark-img="illustrations/auth-basic-mask-dark.png" />
                <img src="{{ asset('vendor/materio/assets/img/illustrations/tree.png') }}" alt="auth-tree"
                    class="authentication-image-object-right d-none d-lg-block" />
            </div>
        </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('vendor/materio/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('vendor/materio/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('vendor/materio/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('vendor/materio/assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('vendor/materio/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('vendor/materio/assets/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{ asset('vendor/materio/assets/js/main.js') }}"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
