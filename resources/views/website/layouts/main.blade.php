<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('vendor/materio/assets') }}" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>FIOLA &mdash; {{ $title }}</title>

    <meta name="description" content="" />
    {{-- <meta http-equiv="refresh" content="2"> --}}

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo-fiola.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('vendor/materio/assets/vendor/fonts/materialdesignicons.css') }}" />

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
    <link rel="stylesheet" href="{{ asset('vendor/materio/assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/bs-step/bs-step.css') }}">

    <!-- Page CSS -->
    <style>
        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Ensure layout-menu is visible by default */
        .layout-menu {
            display: block;
        }

        /* Ensure the button is visible only on large screens */
        @media (min-width: 1200px) {
            .d-xl-block {
                display: block !important;
            }

            .d-none.d-xl-block {
                display: none;
            }
        }

        /* Hide sidebar when sidebar-collapsed class is applied */
        .layout-container.sidebar-collapsed .layout-menu {
            display: none;
        }

        /* Adjust layout when sidebar is hidden */
        .layout-container.sidebar-collapsed .layout-page {
            padding-left: 0;
            /* Remove padding when sidebar is hidden */
        }

        /* Adjust content wrapper to occupy full width */
        .layout-container.sidebar-collapsed .content-wrapper {
            margin-left: 0;
            /* Remove margin when sidebar is hidden */
        }

        /* Ensure the layout-page adjusts when sidebar is hidden */
        .layout-container.sidebar-collapsed .layout-menu-fixed:not(.layout-menu-collapsed) .layout-page,
        .layout-container.sidebar-collapsed .layout-menu-fixed-offcanvas:not(.layout-menu-collapsed) .layout-page {
            padding-left: 0rem;
        }
    </style>
    @stack('styles')
    <!-- Helpers -->
    <script src="{{ asset('vendor/materio/assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('vendor/materio/assets/js/config.js') }}"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('website.layouts.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                @include('website.layouts.navbar')
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @yield('content')
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('website.layouts.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

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
    <script src="{{ asset('vendor/materio/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('vendor/materio/assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('vendor/materio/assets/js/dashboards-analytics.js') }}"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="{{ asset('vendor/plugins/toastr/toastr.min.js') }}"></script>
    <script>
        function getApprovalCount() {
            $.ajax({
                url: "{{ route('website.get_approval_count') }}",
                success: function(response) {
                    // Define the categories and suffixes
                    const categories = ['account', 'folderaccess', 'newfolder', 'software', 'hardware', 'vpn',
                        'project', 'fitur', 'relayout', 'network'
                    ];
                    const suffixes = ['mgr_count', 'it_count', 'it_mgr_count', 'execution_count',
                        'confirm_count'
                    ];

                    // Loop through each category and suffix to update the counts
                    categories.forEach(category => {
                        suffixes.forEach(suffix => {
                            const elementId = `#${category}_${suffix}`;
                            if (response[`${category}_${suffix}`] !== undefined) {
                                $(elementId).text(response[`${category}_${suffix}`]);
                            }
                        });
                    });

                    // Update other counts
                    $('#manager_approvals_count').text(response.manager_approvals_count);
                    $('#confirms_count').text(response.confirms_count);
                    $('#it_approvals_count').text(response.it_approvals_count);
                    $('#it_mgr_approvals_count').text(response.it_mgr_approvals_count);
                    $('#execution_count').text(response.execution_count);

                    // Show or hide badges based on counts
                    const badgeCounts = {
                        manager_approvals_count: response.manager_approvals_count,
                        confirms_count: response.confirms_count,
                        it_approvals_count: response.it_approvals_count,
                        it_mgr_approvals_count: response.it_mgr_approvals_count,
                        execution_count: response.execution_count
                    };

                    Object.keys(badgeCounts).forEach(key => {
                        if (badgeCounts[key] > 0) {
                            $(`#${key}`).show();
                        } else {
                            $(`#${key}`).hide();
                        }
                    });
                }
            });
        }
    </script>

    <script>
        document.getElementById('largeScreenToggle').addEventListener('click', function() {
            document.querySelector('.layout-container').classList.toggle('sidebar-collapsed');
        });
    </script>

    @stack('scripts')
</body>

</html>
