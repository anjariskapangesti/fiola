<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('website.home') }}" class="app-brand-link">
            <span class="app-brand-logo demo me-1">
                <span style="color: var(--bs-primary)">
                    <svg viewBox="0 0 159.41 159.41" xmlns="http://www.w3.org/2000/svg" xmlns:bx="https://boxy-svg.com">
                        <defs>
                            <radialGradient gradientUnits="userSpaceOnUse" cx="86.024" cy="65.421" r="45.997"
                                id="gradient-0"
                                gradientTransform="matrix(1.174419, 0, 0, 1.174419, -12.599426, -10.357308)">
                                <stop offset="0" style="stop-color: rgb(56.471% 33.333% 99.216%)" />
                                <stop offset="1" style="stop-color: rgb(32.907% 12.624% 76.34%)" />
                            </radialGradient>
                            <style bx:fonts="Agbalumo">
                                @import url('{{ asset('fonts/agbalumo/fonts.css') }}');
                            </style>
                        </defs>
                        <text
                            style="fill: url('#gradient-0'); font-family: Agbalumo; font-size: 143.7px; font-style: italic; white-space: pre;"
                            x="34.409" y="132.453"
                            transform="matrix(1, 0, 0, 1, 7.105427357601002e-15, 7.105427357601002e-15)">F</text>
                    </svg>
                </span>
            </span>
            <span class="app-brand-text demo menu-text fw-semibold ms-2">FIOLA</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="mdi menu-toggle-icon d-xl-block align-middle mdi-20px"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item {{ Route::is('website.home') ? 'active' : '' }}">
            <a href="{{ route('website.home') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div data-i18n="Basic">Dashboard</div>
            </a>
        </li>
        <li class="menu-header fw-medium mt-4">
            <span class="menu-header-text">Apps &amp; Pages</span>
        </li>
        <!-- Apps -->
        <!-- Pages -->
        @if (auth()->user()->hasDepartment('ITD'))
            <li class="menu-item {{ Route::is('website.ticket.it_approval') ? 'active' : '' }}">
                <a href="{{ route('website.ticket.it_approval') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-ticket"></i>
                    <div data-i18n="Tiket">Tickets</div>
                    @if (App\Models\AppHelper::ticket_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                            id="ticket_count">{{ App\Models\AppHelper::ticket_count() }}</span>
                    @endif
                </a>
            </li>
        @endif
        {{-- <li class="menu-item {{ Route::is('website.it_needs.create') ? 'active' : '' }}">
            <a href="{{ route('website.it_needs.create') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-note-text"></i>
                <div data-i18n="IT Needs">IT Needs</div>
                @if (App\Models\AppHelper::it_needs_count() > 0)
                    &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                        id="it_needs_count">{{ App\Models\AppHelper::it_needs_count() }}</span>
                @endif
            </a>
        </li> --}}
        @php
            $masterLink = [
                'account',
                'folder-access',
                'new-folder',
                'software',
                'hardware',
                'vpn',
                'project',
                'fitur',
                'relayout',
                'network',
                'akses_sistem',
                'incident_report',
                'izin',
                'it_needs',
            ];
            $createRoutes = [];
            $editRoutes = [];
            $listRoutes = [];

            foreach ($masterLink as $link) {
                $createRoutes[] = 'website.' . $link . '.create';
                $editRoutes[] = 'website.' . $link . '.edit';
                $listRoutes[] = 'website.' . $link . '.list';
                $manager_approval_routes[] = 'website.' . $link . '.manager_approval';
                $manager_approved_routes[] = 'website.' . $link . '.manager_approved';
                $it_approval_routes[] = 'website.' . $link . '.it_approval';
                $it_approved_routes[] = 'website.' . $link . '.it_approved';
                $it_mgr_approval_routes[] = 'website.' . $link . '.it_mgr_approval';
                $it_mgr_approved_routes[] = 'website.' . $link . '.it_mgr_approved';
                $execution_routes[] = 'website.' . $link . '.execution';
                $finished_routes[] = 'website.' . $link . '.finished';
            }

        @endphp
        <li
            class="menu-item {{ in_array(Route::currentRouteName(), $createRoutes) || in_array(Route::currentRouteName(), $editRoutes) ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-list-box-outline"></i>
                <div data-i18n="Forms">Forms</div>
            </a>
            @include('website.layouts.sidebar_items', ['link' => 'create', 'text' => 'Form'])
        </li>
        <li class="menu-item {{ in_array(Route::currentRouteName(), $listRoutes) ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-text-search"></i>
                <div data-i18n="Track Forms">Track Forms
                    @if (App\Models\AppHelper::confirms_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                            id="confirms_count">{{ App\Models\AppHelper::confirms_count() }}</span>
                    @endif
                </div>
            </a>
            @include('website.layouts.sidebar_items', ['link' => 'list', 'text' => 'Form'])
        </li>

        {{-- MANAGER --}}
        @can('approve_mgr')
            @if (!auth()->user()->hasDepartment('ITD'))
                <li
                    class="menu-item {{ in_array(Route::currentRouteName(), $manager_approval_routes) ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons mdi mdi-timer-sand"></i>
                        <div data-i18n="Manager Approval">Manager Approval
                            @if (App\Models\AppHelper::manager_approvals_count() > 0)
                                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                                    id="manager_approvals_count">{{ App\Models\AppHelper::manager_approvals_count() }}</span>
                            @endif
                        </div>
                    </a>
                    @include('website.layouts.sidebar_items', [
                        'link' => 'manager_approval',
                        'text' => 'Form',
                    ])
                </li>
                <li
                    class="menu-item {{ in_array(Route::currentRouteName(), $manager_approved_routes) ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons mdi mdi-history"></i>
                        <div data-i18n="Manager History">Manager History</div>
                    </a>
                    @include('website.layouts.sidebar_items', [
                        'link' => 'manager_approved',
                        'text' => 'Form',
                    ])
                </li>
            @endif
        @endcan

        {{-- GROUP MANAGER --}}
        @can('approve_gm')
            <li class="menu-item {{ in_array(Route::currentRouteName(), $manager_approval_routes) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-timer-sand"></i>
                    <div data-i18n="GM Approval">GM Approval
                        @if (App\Models\AppHelper::manager_approvals_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                                id="manager_approvals_count">{{ App\Models\AppHelper::manager_approvals_count() }}</span>
                        @endif
                    </div>
                </a>
                @include('website.layouts.sidebar_items', ['link' => 'manager_approval', 'text' => 'Form'])
            </li>
            <li class="menu-item {{ in_array(Route::currentRouteName(), $manager_approved_routes) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-history"></i>
                    <div data-i18n="GM History">GM History</div>
                </a>
                @include('website.layouts.sidebar_items', ['link' => 'manager_approved', 'text' => 'Form'])
            </li>
        @endcan

        {{-- DIRECTOR --}}
        @can('approve_dir')
            <li class="menu-item {{ in_array(Route::currentRouteName(), $manager_approval_routes) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-timer-sand"></i>
                    <div data-i18n="Director Approval">Director Approval
                        @if (App\Models\AppHelper::manager_approvals_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                                id="manager_approvals_count">{{ App\Models\AppHelper::manager_approvals_count() }}</span>
                        @endif
                    </div>
                </a>
                @include('website.layouts.sidebar_items', ['link' => 'manager_approval', 'text' => 'Form'])
            <li class="menu-item {{ in_array(Route::currentRouteName(), $manager_approved_routes) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-history"></i>
                    <div data-i18n="Director History">Director History</div>
                </a>
                @include('website.layouts.sidebar_items', ['link' => 'manager_approved', 'text' => 'Form'])
            </li>
            <li class="menu-item {{ Route::is('website.project.dir_approval') ? 'active' : '' }}">
                <a href="{{ route('website.project.dir_approval') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-calendar-sync"></i>
                    <div data-i18n="Project Reschedule">Project Reschedule Approval</div>
                    @if (App\Models\AppHelper::project_dir_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::project_dir_count() }}</span>
                    @endif
                </a>
            </li>
            <li class="menu-item {{ Route::is('website.project.dir_approved') ? 'active' : '' }}">
                <a href="{{ route('website.project.dir_approved') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-history"></i>
                    <div data-i18n="Reschedule History">Project Reschedule History</div>
                </a>
            </li>
        @endcan

        {{-- VP --}}
        @can('approve_vp')
            <li class="menu-item {{ in_array(Route::currentRouteName(), $manager_approval_routes) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-timer-sand"></i>
                    <div data-i18n="VP Approval">VP Approval
                        @if (App\Models\AppHelper::manager_approvals_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                                id="manager_approvals_count">{{ App\Models\AppHelper::manager_approvals_count() }}</span>
                        @endif
                    </div>
                </a>
                @include('website.layouts.sidebar_items', ['link' => 'manager_approval', 'text' => 'Form'])
            </li>
            <li class="menu-item {{ in_array(Route::currentRouteName(), $manager_approved_routes) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-history"></i>
                    <div data-i18n="VP History">VP History</div>
                </a>
                @include('website.layouts.sidebar_items', ['link' => 'manager_approved', 'text' => 'Form'])
            </li>
        @endcan

        {{-- PRESDIR --}}
        @can('approve_pres')
            <li
                class="menu-item {{ in_array(Route::currentRouteName(), $manager_approval_routes) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-timer-sand"></i>
                    <div data-i18n="PD Approval">PD Approval
                        @if (App\Models\AppHelper::manager_approvals_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                                id="manager_approvals_count">{{ App\Models\AppHelper::manager_approvals_count() }}</span>
                        @endif
                    </div>
                </a>
                @include('website.layouts.sidebar_items', [
                    'link' => 'manager_approval',
                    'text' => 'Form',
                ])
            </li>
            <li
                class="menu-item {{ in_array(Route::currentRouteName(), $manager_approved_routes) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-history"></i>
                    <div data-i18n="PD History">PD History</div>
                </a>
                @include('website.layouts.sidebar_items', [
                    'link' => 'manager_approved',
                    'text' => 'Form',
                ])
            </li>
            <li class="menu-item {{ Route::is('website.project.dir_approval') ? 'active' : '' }}">
                <a href="{{ route('website.project.dir_approval') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-calendar-sync"></i>
                    <div data-i18n="Project Reschedule">Project Reschedule Approval</div>
                    @if (App\Models\AppHelper::project_dir_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::project_dir_count() }}</span>
                    @endif
                </a>
            </li>
            <li class="menu-item {{ Route::is('website.project.dir_approved') ? 'active' : '' }}">
                <a href="{{ route('website.project.dir_approved') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-history"></i>
                    <div data-i18n="Reschedule History">Project Reschedule History</div>
                </a>
            </li>
        @endcan

        {{-- GM --}}
        {{-- @php
            $masterLink = ['akses_sistem'];
            $createRoutes = [];
            $editRoutes = [];
            $listRoutes = [];

            foreach ($masterLink as $link) {
                $createRoutes[] = 'website.' . $link . '.create';
                $editRoutes[] = 'website.' . $link . '.edit';
                $listRoutes[] = 'website.' . $link . '.list';
                $gm_approval_routes[] = 'website.' . $link . '.gm_approval';
                $gm_approved_routes[] = 'website.' . $link . '.gm_approved';
            }

        @endphp --}}
        {{-- @can('approve_gm')
            <li class="menu-item {{ in_array(Route::currentRouteName(), $gm_approval_routes) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-timer-sand"></i>
                    <div data-i18n="GM Approval">GM Approval
                        @if (App\Models\AppHelper::gm_approvals_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                                id="gm_approvals_count">{{ App\Models\AppHelper::gm_approvals_count() }}</span>
                        @endif
                    </div>
                </a>
                <ul class="menu-sub">
                    <li
                        class="menu-item {{ Route::is('website.akses_sistem.gm_approval') || Route::is('website.akses_sistem.edit') ? 'active' : '' }}">
                        <a href="{{ route('website.akses_sistem.gm_approval') }}" class="menu-link">
                            <div data-i18n="Akses Sistem">Form Akses Sistem</div>
                            @if (App\Models\AppHelper::akses_sistem_gm_count() > 0)
                                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                                    id="akses_sistem_gm_count">{{ App\Models\AppHelper::akses_sistem_gm_count() }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item {{ in_array(Route::currentRouteName(), $gm_approved_routes) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-history"></i>
                    <div data-i18n="GM History">GM History</div>
                </a>
            </li>
        @endcan --}}
        {{-- ITD --}}
        @if (auth()->user()->hasDepartment('ITD'))
            @if (auth()->user()->hasDepartment('ITD') && !auth()->user()->hasPermissionTo('approve_mgr'))
                <li
                    class="menu-item {{ in_array(Route::currentRouteName(), $it_approval_routes) ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons mdi mdi-timer-sand"></i>
                        <div data-i18n="ITD Approval">ITD Approval
                            @if (App\Models\AppHelper::it_approvals_count() > 0)
                                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                                    id="it_approvals_count">{{ App\Models\AppHelper::it_approvals_count() }}</span>
                            @endif
                        </div>
                    </a>
                    @include('website.layouts.sidebar_items', ['link' => 'it_approval', 'text' => 'Form'])
                </li>
                <li
                    class="menu-item {{ in_array(Route::currentRouteName(), $it_approved_routes) ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons mdi mdi-history"></i>
                        <div data-i18n="ITD History">ITD History</div>
                    </a>
                    @include('website.layouts.sidebar_items', ['link' => 'it_approved', 'text' => 'Form'])
                </li>
            @endif
            {{-- ITD MGR --}}
            @can('approve_mgr')
                <li
                    class="menu-item {{ in_array(Route::currentRouteName(), $it_mgr_approval_routes) ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons mdi mdi-timer-sand"></i>
                        <div data-i18n="ITD MGR Approval">ITD MGR Approval
                            @if (App\Models\AppHelper::it_mgr_approvals_count() > 0)
                                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                                    id="it_mgr_approvals_count">{{ App\Models\AppHelper::it_mgr_approvals_count() }}</span>
                            @endif
                        </div>
                    </a>
                    @include('website.layouts.sidebar_items', [
                        'link' => 'it_mgr_approval',
                        'text' => 'Form',
                    ])
                </li>
                <li
                    class="menu-item {{ in_array(Route::currentRouteName(), $it_mgr_approved_routes) ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons mdi mdi-history"></i>
                        <div data-i18n="ITD MGR History">ITD MGR History</div>
                    </a>
                    @include('website.layouts.sidebar_items', [
                        'link' => 'it_mgr_approved',
                        'text' => 'Form',
                    ])
                </li>
            @endcan
            {{-- Execution --}}
            @if (auth()->user()->hasDepartment('ITD') && !auth()->user()->hasPermissionTo('approve_mgr'))
                <li
                    class="menu-item {{ in_array(Route::currentRouteName(), $execution_routes) ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons mdi mdi-rocket-launch"></i>
                        <div data-i18n="Execution">Execution
                            @if (App\Models\AppHelper::execution_count() > 0)
                                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                                    id="execution_count">{{ App\Models\AppHelper::execution_count() }}</span>
                            @endif
                        </div>
                    </a>
                    @include('website.layouts.sidebar_items', ['link' => 'execution', 'text' => 'Form'])
                </li>
                <li
                    class="menu-item {{ in_array(Route::currentRouteName(), $finished_routes) ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons mdi mdi-clipboard-check"></i>
                        <div data-i18n="Finished">Finished</div>
                    </a>
                    @include('website.layouts.sidebar_items', ['link' => 'finished', 'text' => 'Form'])
                </li>
            @endif
            <!-- Master -->
            <li class="menu-header fw-medium mt-4"><span class="menu-header-text">Master</span></li>
            @php
                $alertRoutes = ['website.alert.list', 'website.alert.create', 'website.alert.edit'];
            @endphp
            <li class="menu-item {{ in_array(Route::currentRouteName(), $alertRoutes) ? 'active' : '' }}">
                <a href="{{ route('website.alert.list') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-message-alert"></i>
                    <div data-i18n="Alert">Alert</div>
                </a>
            </li>

            @php
                $appRoutes = ['website.app.list', 'website.app.create', 'website.app.edit'];
            @endphp
            <li class="menu-item {{ in_array(Route::currentRouteName(), $appRoutes) ? 'active' : '' }}">
                <a href="{{ route('website.app.list') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-apps"></i>
                    <div data-i18n="Apps / System">Apps / System</div>
                </a>
            </li>

            @php
                $deviceRoutes = ['website.device.list', 'website.device.create', 'website.device.edit'];
            @endphp
            <li class="menu-item {{ in_array(Route::currentRouteName(), $deviceRoutes) ? 'active' : '' }}">
                <a href="{{ route('website.device.list') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-harddisk"></i>
                    <div data-i18n="Device">Device</div>
                </a>
            </li>

            @php
                $folderRoutes = ['website.folder.list', 'website.folder.create', 'website.folder.edit'];
                $subfolderRoutes = ['website.subfolder.list', 'website.subfolder.create', 'website.subfolder.edit'];
            @endphp
            <li
                class="menu-item {{ in_array(Route::currentRouteName(), $folderRoutes) || in_array(Route::currentRouteName(), $subfolderRoutes) ? 'active open' : '' }}">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-folder"></i>
                    <div data-i18n="Folder">Folder</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ in_array(Route::currentRouteName(), $folderRoutes) ? 'active' : '' }}">
                        <a href="{{ route('website.folder.list') }}" class="menu-link">
                            <div data-i18n="Folder">Folder</div>
                        </a>
                    </li>
                    <li class="menu-item {{ in_array(Route::currentRouteName(), $subfolderRoutes) ? 'active' : '' }}">
                        <a href="{{ route('website.subfolder.list') }}" class="menu-link">
                            <div data-i18n="Sub Folder">Subfolder</div>
                        </a>
                    </li>
                </ul>
            </li>

            @php
                $guideRoutes = ['website.guide.list', 'website.guide.create', 'website.guide.edit'];
            @endphp
            <li class="menu-item {{ in_array(Route::currentRouteName(), $guideRoutes) ? 'active' : '' }}">
                <a href="{{ route('website.guide.list') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-book-information-variant"></i>
                    <div data-i18n="Guide">Guide</div>
                </a>
            </li>

            @php
                $supportRoutes = ['website.support.list', 'website.support.create', 'website.support.edit'];
            @endphp
            <li class="menu-item {{ in_array(Route::currentRouteName(), $supportRoutes) ? 'active' : '' }}">
                <a href="{{ route('website.support.list') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-face-agent"></i>
                    <div data-i18n="Alert">Tim Support</div>
                </a>
            </li>

            <!-- Users -->
            <li class="menu-item {{ Route::is('website.user.list') ? 'active' : '' }}">
                <a href="{{ route('website.user.list') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-account-multiple"></i>
                    <div data-i18n="Users">Users</div>
                </a>
            </li>
        @endcan
</ul>
</aside>
