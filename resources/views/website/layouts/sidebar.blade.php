<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link collapsed" href="/">
                <i class="bi bi-speedometer"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->
        <li class="nav-heading">Pages</li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-text"></i><span>Forms</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('website.account.create') }}">
                        <i class="bi bi-record-circle-fill"></i><span>Form Account</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('website.folder-access.create') }}">
                        <i class="bi bi-record-circle-fill"></i><span>Form Folder Access</span>
                    </a>
                </li>
                <li>
                    <a href="forms-editors.html">
                        <i class="bi bi-record-circle-fill"></i><span>Form New Folder</span>
                    </a>
                </li>
                <li>
                    <a href="forms-validation.html">
                        <i class="bi bi-record-circle-fill"></i><span>Form S/W Installation</span>
                    </a>
                </li>
                <li>
                    <a href="forms-validation.html">
                        <i class="bi bi-record-circle-fill"></i><span>Form H/W Installation</span>
                    </a>
                </li>
                <li>
                    <a href="forms-validation.html">
                        <i class="bi bi-record-circle-fill"></i><span>Form VPN</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Forms Nav -->

        @can('can_approve_mgr')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-clipboard-check"></i><span>Manager Approvals</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_manager_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.folder-access.show_manager_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Folder Access</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.account.show_data_manager_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Tables</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->
        @endcan

        @can('can_approve_it')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#app_it_nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-clipboard-check"></i><span>ITD Approvals</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="app_it_nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_it_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.account.show_data_it_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Tables</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->
        @endcan

        {{-- @if (Auth::user()->hasPermissionTo('can_approve_it_mgr')) --}}
        @can('can_approve_it_mgr')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#app_it_mgr_nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-clipboard-check"></i><span>ITD Manager Approvals</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="app_it_mgr_nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_it_mgr_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.account.show_data_it_mgr_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Tables</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->
        @endcan
        {{-- @endif --}}

        @can('can_execution')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#app_execution_nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-clipboard-check"></i><span>Execution Approvals</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="app_execution_nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_execution_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.account.show_data_execution_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Tables</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->
        @endcan
        @can('can_master')
            <li class="nav-heading">Master</li>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#app_department_nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-bank"></i><span>Department</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="app_department_nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.department.create') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Add Department</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.department.show_data_department') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Department</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->
        @endcan



        <li class="nav-heading">ACTION</li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('website.auth.logout') }}">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </li><!-- End Login Page Nav -->
    </ul>

</aside>
