<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('website.auth.home') }}">
                <i class="bi bi-speedometer"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->
        <li class="nav-heading">Pages</li>
        @can('can_create_form')
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
                    <a href="#">
                        <i class="bi bi-record-circle-fill"></i><span>Form New Folder</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="bi bi-record-circle-fill"></i><span>Form S/W Installation</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="bi bi-record-circle-fill"></i><span>Form H/W Installation</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="bi bi-record-circle-fill"></i><span>Form VPN</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Forms Nav -->
        @endcan

        @can('can_approve_mgr')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#approval_mgr" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-hourglass"></i><span>Manager Approvals</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="approval_mgr" class="nav-content collapse " data-bs-parent="#sidebar-nav">
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
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Form S/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Form H/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Form VPN</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#approved_mgr" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-clipboard-check"></i><span>Manager Approved</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="approved_mgr" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_data_manager_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.folder-access.show_data_manager_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Folder Access</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form S/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form H/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form VPN</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->
        @endcan

        @can('can_approve_it')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#approval_it" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-hourglass"></i><span>ITD Approvals</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="approval_it" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_it_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.folder-access.show_it_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Folder Access</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Form S/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Form H/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Form VPN</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#approved_it" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-clipboard-check"></i><span>ITD Approved</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="approved_it" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_data_it_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Folder Access</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form S/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form H/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form VPN</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->
        @endcan

        @can('can_approve_it_mgr')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#approval_it_mgr" data-bs-toggle="collapse"
                    href="#">
                    <i class="bi bi-hourglass"></i><span>ITD Manager Approvals</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="approval_it_mgr" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_it_mgr_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.folder-access.show_it_mgr_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Folder Access</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Form S/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Form H/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Form VPN</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#approved_it_mgr" data-bs-toggle="collapse"
                    href="#">
                    <i class="bi bi-clipboard-check"></i><span>ITD Manager Approved</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="approved_it_mgr" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_data_it_mgr_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Folder Access</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form S/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form H/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form VPN</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->
        @endcan

        @can('can_execution')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#execution" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-hand-index-thumb"></i></i><span>Execution</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="execution" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_execution') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Form Folder Access</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Form S/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Form H/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Form VPN</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#finished" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-list-check"></i><span>Finished</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="finished" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Folder Access</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form S/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form H/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form VPN</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->
        @endcan

        @can('can_master')
            <li class="nav-heading">Master</li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('website.department.show_data_department') }}">
                    <i class="bi bi-bank"></i><span>Department</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#app_folder_path_nav" data-bs-toggle="collapse"
                    href="#">
                    <i class="bi bi-folder"></i><span>Folder Path</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="app_folder_path_nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-record-circle-fill"></i><span>Sub Folder</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('website.user.show_data_user') }}">
                    <i class="bi bi-people"></i><span>Users</span>
                </a>
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
