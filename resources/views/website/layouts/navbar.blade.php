<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="mdi mdi-menu mdi-24px"></i>
        </a>
    </div>

    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-none d-xl-block">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)" id="largeScreenToggle">
            <i class="mdi mdi-menu mdi-24px"></i>
        </a>
    </div>
    <!-- Search Bar -->
    <div class="search-bar col-3">
        <input type="text" id="search-bar" placeholder="Search Form..." class="form-control">
        <!-- Dropdown untuk menampilkan hasil pencarian -->
        <div id="search-results" class="dropdown-menu col-3" style="display: none;">
            <!-- Hasil pencarian akan ditambahkan di sini menggunakan JavaScript -->
        </div>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Place this tag where you want the button to render. -->
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('vendor/materio/assets/img/avatars/1.png') }}" alt
                            class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end mt-3 py-2">
                    <li>
                        <a class="dropdown-item pb-2 mb-1" href="#">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-2 pe-1">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('vendor/materio/assets/img/avatars/1.png') }}" alt
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                    @if (Auth::user()->job_ranks->pluck('code')->implode(', ') == 'PRESDIR')
                                        <small class="text-muted">
                                            {{ Auth::user()->job_ranks->pluck('name')->implode(', ') }}
                                        </small>
                                    @elseif (Auth::user()->job_ranks->pluck('code')->implode(', ') == 'VP')
                                        <small class="text-muted">
                                            {{ Auth::user()->job_ranks->pluck('name')->implode(', ') }}
                                        </small>
                                    @elseif (Auth::user()->job_ranks->pluck('code')->implode(', ') == 'DIR')
                                        <small class="text-muted">
                                            {{ Auth::user()->job_ranks->pluck('name')->implode(', ') }}
                                        </small>
                                    @elseif (Auth::user()->job_ranks->pluck('code')->implode(', ') == 'GM')
                                        <small class="text-muted">
                                            {{ Auth::user()->divisions->pluck('name')->implode(', ') }}
                                        </small>
                                    @else
                                        <small class="text-muted">
                                            {{ Auth::user()->departments->pluck('code')->implode(', ') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('website.user.edit') }}">
                            <i class="mdi mdi-account-outline me-1 mdi-20px"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('website.auth.logout') }}">
                            <i class="mdi mdi-power me-1 mdi-20px"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>

@push('styles')
    <style>
        @media (min-width: 1200px) {
            #layout-menu {
                display: block;
                /* Initially visible on large screens */
            }
        }
    </style>
    <style>
        #search-results .dropdown-item {
            padding: 10px;
            cursor: pointer;
        }

        #search-results .dropdown-item:hover {
            background-color: #f8f9fa;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.getElementById('largeScreenToggle').addEventListener('click', function() {
            var layoutMenu = document.getElementById('layout-menu');
            if (layoutMenu.style.display === 'none') {
                layoutMenu.style.display = 'flex';
            } else {
                layoutMenu.style.display = 'none';
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            const pages = [{
                    title: 'Form Account',
                    url: '/account/create'
                },
                {
                    title: 'Form Folder Access',
                    url: '/folder-access/create'
                },
                {
                    title: 'Form New Folder',
                    url: '/new-folder/create'
                },
                {
                    title: 'Form Software Installation',
                    url: '/software/create'
                },
                {
                    title: 'Form Request Device',
                    url: '/hardware/create'
                },
                {
                    title: 'Form VPN',
                    url: '/vpn/create'
                },
                {
                    title: 'Form Request Project',
                    url: '/project/create'
                },
                {
                    title: 'Form Request Fitur',
                    url: '/fitur/create'
                },
                {
                    title: 'Form Relayout',
                    url: '/relayout/create'
                },
                {
                    title: 'Form Network Change',
                    url: '/network/create'
                },
                {
                    title: 'Form Akses Sistem',
                    url: '/akses_sistem/create'
                },
                {
                    title: 'Form Incident Report',
                    url: '/incident_report/create'
                },
                {
                    title: 'Form Izin Memasuki Area Level 3',
                    url: '/izin/create'
                },
            ];

            $('#search-bar').on('input', function() {
                const query = $(this).val().toLowerCase();
                const results = pages.filter(page => page.title.toLowerCase().includes(query));

                const resultsContainer = $('#search-results');
                resultsContainer.empty();

                if (results.length > 0) {
                    results.forEach(result => {
                        resultsContainer.append(`
                    <a class="dropdown-item" href="${result.url}">${result.title}</a>
                `);
                    });
                    resultsContainer.show();
                } else {
                    resultsContainer.hide();
                }
            });

            $(document).on('click', function(event) {
                if (!$(event.target).closest('#search-bar').length) {
                    $('#search-results').hide();
                }
            });
        });
    </script>
@endpush
