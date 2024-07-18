<!DOCTYPE html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>FIOLA &mdash; Create Ticket</title>


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
    <link rel="stylesheet" href="{{ asset('vendor/datatables/css/datatables.min.css') }}">
    <!-- Vendors CSS -->
    <link rel="stylesheet"
        href="{{ asset('vendor/materio/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

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
            <div class="authentication-inner p-4" style="max-width: 100% !important;">
                <!-- Login -->
                <div class="card p-2">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mt-3">
                        <a href="{{ route('website.ticket.create') }}" class="app-brand-link gap-2">
                            <span class="app-brand-text demo text-heading fw-semibold"
                                style="color: #9055fd !important; font-size: 36px;">CREATE TICKET</span>
                        </a>
                    </div>
                    <!-- /Logo -->

                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('website.ticket.list') }}" class="btn btn-primary"><i
                                    class="mdi mdi-arrow-left"></i> LIST TICKET<i class="mdi mdi-list-box"></i> </a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Ooops..</strong>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif
                                <hr>
                                <form method="post" action="{{ route('website.ticket.store') }}"
                                    class="needs-validation" id="myForm" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="">Requestor Information</h5>
                                        </div>
                                        <div class="demo-vertical-spacing demo-only-element">
                                            <div class="row mb-3">
                                                <label class="col-sm-12 col-form-label" for="requestor_name">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" class="form-control" id="requestor_name"
                                                            name="requestor_name" value="{{ old('requestor_name') }}"
                                                            placeholder="Nama Anda" required />
                                                        <label for="requestor_name">Requestor Name <span
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                </label>

                                                <label class="col-sm-12 col-form-label" for="requestor_phone">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" class="form-control" id="requestor_phone"
                                                            name="requestor_phone" value="{{ old('requestor_phone') }}"
                                                            placeholder="No HP Anda" maxlength="15" required />
                                                        <label for="requestor_phone">Requestor Phone <span
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                </label>

                                                <label class="col-sm-12 col-form-label" for="requestor_department">
                                                    <div class="form-floating form-floating-outline">
                                                        <select name="requestor_department" id="requestor_department"
                                                            class="form-control" required>
                                                            <option value="">-- Pilih --</option>
                                                            @foreach ($departments as $department)
                                                                <option value="{{ $department->name }}">
                                                                    {{ $department->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <label for="requestor_department">Requestor Department <span
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                </label>
                                                <hr>
                                                <div class="d-flex justify-content-between">
                                                    <h5 class="">Ticket Information</h5>
                                                </div>
                                                <label class="col-sm-12 col-form-label" for="detail_case">
                                                    <div class="form-floating form-floating-outline">
                                                        <textarea class="form-control auto-resize" id="detail_case" name="detail_case"
                                                            placeholder="Detail Problem yang terjadi" required>{{ old('detail_case') }}</textarea>
                                                        <label for="detail_case">Detail Case <span
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                </label>
                                                <label class="col-sm-12 col-form-label" for="category">
                                                    <div class="form-floating form-floating-outline">
                                                        <select name="category" id="category" class="form-control"
                                                            required>
                                                            <option value="">-- Pilih --</option>
                                                            <option value="Hardware (Laptop, PC, Scanner, DLL)">
                                                                Hardware (Laptop, PC, Scanner, DLL)</option>
                                                            <option value="Software (Error Aplikasi, AISINBISA, DLL)">
                                                                Software
                                                                (Error Aplikasi, AISINBISA, DLL)
                                                            </option>
                                                            <option value="Network (Internet, Server, Wifi, DLL)">
                                                                Network
                                                                (Internet, Server, Wifi, DLL)</option>
                                                        </select>
                                                        <label for="category">Category <span
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                </label>
                                                <label class="col-sm-12 col-form-label" for="location">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" class="form-control" id="location"
                                                            name="location" value="{{ old('location') }}"
                                                            placeholder="Lokasi Problem" required />
                                                        <label for="location">Location <span
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                </label>
                                                <label class="col-sm-12 col-form-label" for="sla">
                                                    <div class="form-floating form-floating-outline">
                                                        <select name="sla" id="sla" class="form-control"
                                                            required>
                                                            <option value="">-- Pilih --</option>
                                                            <option value="Medium">
                                                                Medium</option>
                                                            <option value="High">
                                                                High
                                                            </option>
                                                            <option value="Urgent">
                                                                Urgent</option>
                                                        </select>
                                                        <label for="category">Priority <span
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                </label>
                                                <label class="col-sm-12 col-form-label" for="lampiran">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="file" class="form-control" id="lampiran"
                                                            name="lampiran" value="{{ old('lampiran') }}"
                                                            placeholder="Lampiran" accept="image/*" />
                                                        <label for="lampiran">Attachment </label>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-success"
                                            id="submitButton">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /Login -->
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
        <script src="{{ asset('vendor/datatables/js/datatables.min.js') }}"></script>
        <!-- endbuild -->

        <!-- Vendors JS -->

        <!-- Main JS -->
        <script src="{{ asset('vendor/materio/assets/js/main.js') }}"></script>

        <!-- Page JS -->

        <!-- Place this tag in your head or just before your close body tag. -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
        <script src="{{ asset('vendor/moment/moment.min.js') }}"></script>
        <script src="{{ asset('vendor/plugins/toastr/toastr.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                @if (session()->has('success'))
                    toastr['success']("{{ Session('success') }}")
                @endif
            })
        </script>
        <script>
            const detail_case = document.querySelector('#detail_case');

            detail_case.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var form = document.getElementById('myForm');
                var submitButton = document.getElementById('submitButton');
                var spinner = '<i class="mdi mdi-loading spin"></i>';

                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        form.classList.add('was-validated');
                        event.preventDefault();
                    } else {
                        submitButton.setAttribute('disabled', 'true');
                        submitButton.innerHTML = spinner + ' Submitting...';
                    }
                });

                form.addEventListener('input', function() {
                    if (form.checkValidity()) {
                        submitButton.removeAttribute('disabled');
                        submitButton.innerHTML = 'Submit';
                    }
                });
            });
        </script>
</body>

</html>
