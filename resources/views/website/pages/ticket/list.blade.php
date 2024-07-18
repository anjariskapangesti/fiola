<!DOCTYPE html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>FIOLA &mdash; List Ticket</title>

    <meta name="description" content="" />
    <style>
        #toast-container>.toast-success {
            background-color: #28a745 !important;
            /* Warna hijau untuk latar belakang */
            color: #fff !important;
            /* Warna putih untuk teks */
        }
    </style>
    <style>
        .detail_case_container {
            max-width: 200px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            box-sizing: border-box;
        }
    </style>
    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo-fiola.png') }}" />
    <link href="{{ asset('vendor/niceadmin/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />

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
                        <a href="{{ route('website.ticket.list') }}" class="app-brand-link gap-2">
                            <span class="app-brand-text demo text-heading fw-semibold"
                                style="color: #9055fd !important; font-size: 36px;">LIST TICKET</span>
                        </a>
                    </div>
                    <!-- /Logo -->

                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('website.ticket.create') }}" class="btn btn-success"><i
                                    class="mdi mdi-plus"></i> CREATE TICKET<i class="mdi mdi-ticket"></i> </a>
                            <a href="{{ route('website.auth.login') }}" class="btn btn-primary"><i
                                    class="mdi mdi-login"></i> FORM ITD<i class="mdi mdi-text-box-outline"></i> </a>
                        </div>
                        {{-- <h5 class="mt-3 mb-2 text-center">TICKET LIST</h5> --}}
                        <div class="table-responsive text-nowrap">
                            @if (Session::get('info'))
                                <div class="alert alert-info">
                                    {{ Session::get('info') }}
                                </div>
                            @endif
                            <table class="table table-bordered" id="app_table" width="100%"
                                style="vertical-align: top;">
                                <thead>
                                    <tr>
                                        <th width="50px">No</th>
                                        <th style="max-width: 200px;">Requestor</th>
                                        <th style="max-width: 200px;">Ticket</th>
                                        <th>Support</th>
                                        <th>Attachment</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                        {{-- <p class="text-center">
                            <span>New on our platform?</span>
                            <a href="auth-register-basic.html">
                                <span>Create an account</span>
                            </a>
                        </p> --}}
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
    <script src="{{ asset('vendor/plugins/toastr/toastr.min.js') }}"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="{{ asset('vendor/moment/moment.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            @if (session()->has('success'))
                toastr['success']("{{ Session('success') }}")
            @endif
        })
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#app_table').DataTable({
                'lengthChange': true,
                'processing': true,
                'serverSide': false,
                'orderable': true,
                ajax: {
                    url: "{{ route('website.ticket.list_ajax') }}",
                },
                columns: [{
                        data: null,
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row, meta) {
                            var rowIndex = meta.row + meta.settings._iDisplayStart + 1;
                            return rowIndex;
                        },
                        className: "text-center" // Menetapkan kelas CSS 'text-center'
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return `
                            <div class="label-container">
                                <div class="mb-1">
                                    <b>Name : </b><br>
                                    <span class="detail_case_container">${row.requestor_name}</span>
                                </div>
                                    <b>Phone : </b><br>
                                    <span class="detail_case_container">${row.requestor_phone}</span>
                                </div>
                                </div>
                                    <b>Department : </b><br>
                                    <span class="detail_case_container">${row.requestor_department}</span>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            // Format tanggal 'created_at' ke 'YYYY-MM-DD HH:mm'
                            let created_at = row.created_at ? moment(row.created_at).format(
                                'YYYY-MM-DD HH:mm') : '';

                            return `
                            <div>
                                <div class="mb-3">
                                    <span class="alert alert-info p-1">${row.no_reg}</span>
                                </div>
                                <div class="mb-3">
                                    <b>Detail Problem : </b><br>
                                    <span class="detail_case_container">${row.detail_case}</span>
                                </div>
                                <div>
                                    <b>Location&emsp;: </b>${row.location}
                                </div>
                                <div class="mb-3">
                                    <b>Priority&emsp;: </b>${row.sla}
                                </div>
                                <div class="mb-3">
                                    <b>Solution&emsp;: </b><br>
                                    <span class="detail_case_container">${row.it_name ? (row.solution ? row.solution : '-') : '<span class="badge btn-primary">Tunggu Approve</span>'}</span>
                                </div>
                                <div>
                                    <b>Reported Date&emsp;: </b>${created_at}
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return `
                            <div>
                                <div class="mb-1">
                                    <b>PIC&emsp;: </b>${row.it_name ? row.it_name : '<span class="badge btn-primary">Tunggu Approve</span>'}
                                </div>
                                <div class="mb-1">
                                    <b>Phone&emsp;: </b>${row.it_phone ? row.it_phone : '<span class="badge btn-primary">Tunggu Approve</span>'}
                                </div>
                                <div class="mb-3">
                                    <b>IT Note&emsp;: </b>${row.it_name ? (row.it_note ? row.it_note : '-') : '<span class="badge btn-primary">Tunggu Approve</span>'}
                                </div>

                                <div class="mb-3">
                                    <b>Accepted Date&emsp;: </b>${row.it_approval_date ? row.it_approval_date : '<span class="badge btn-primary">Tunggu Approve</span>'}
                                </div>

                                <div>
                                    <b>Pending Date&emsp;: </b>${row.on_progress_date ? row.on_progress_date : '-'}
                                </div>
                                <div class="mb-3">
                                    <b>Pending Reason&emsp;: </b>${row.on_progress_note ? row.on_progress_note : '-'}
                                </div>

                                <div>
                                    <b>Finish Date&emsp;: </b>${row.finish_date ? row.finish_date : '-'}
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            let path = row.ticket_photos.length > 0 ? row.ticket_photos[0].path :
                                'No photo available';

                            // Periksa apakah ada path, jika tidak ada tampilkan teks default
                            if (path !== 'No photo available') {
                                return `
                                <div>
                                    <a href="/storage/lampiran/${path}" class="btn btn-info btn-sm" target="_blank">
                                        <i class="mdi mdi-image"></i> View
                                    </a>
                                </div>`;
                            } else {
                                return `
                                <div>
                                    <span>${path}</span>
                                </div>`;
                            }
                        }
                    },

                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            let text = '';
                            let className = '';

                            switch (row.final_status) {
                                case 'created':
                                    text = 'Waiting for ITD to receive';
                                    className = 'alert-warning';
                                    break;
                                case 'IT Approve':
                                    text = 'Accepted by ITD, On Progress';
                                    className = 'alert-info';
                                    break;
                                case 'Pending':
                                    text = 'Pending';
                                    className = 'alert-info';
                                    break;
                                case 'Finished':
                                    text = 'Finished';
                                    className = 'alert-success';
                                    break;
                                default:
                                    text = 'Tunggu Approve';
                                    className = 'alert-info';
                            }

                            return `
                            <div>
                                <button class="alert ${className}">${text}</button>
                            </div>`;
                        }
                    },
                ],
            });
        });
    </script>
</body>

</html>
