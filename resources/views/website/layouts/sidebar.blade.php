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
                        <a href="{{ route('website.new-folder.create') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.create') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form S/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.create') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form H/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.create') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form VPN</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Forms Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#track_forms" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-search"></i><span>Track Forms</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="track_forms" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_data_form') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.folder-access.show_data_form') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Folder Access</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.new-folder.show_data_form') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.show_data_form') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form S/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.show_data_form') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form H/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.show_data_form') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form VPN</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->
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
                        <a href="{{ route('website.new-folder.show_manager_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.show_manager_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form S/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.show_manager_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form H/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.show_manager_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form VPN</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#approved_mgr" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-clock-history"></i><span>Manager History</span><i
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
                        <a href="{{ route('website.new-folder.show_data_manager_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.show_data_manager_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form S/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.show_data_manager_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form H/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.show_data_manager_approval') }}">
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
                        <a href="{{ route('website.new-folder.show_it_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.show_it_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form S/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.show_it_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form H/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.show_it_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form VPN</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#approved_it" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-clock-history"></i><span>ITD History</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="approved_it" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_data_it_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.folder-access.show_data_it_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Folder Access</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.new-folder.show_data_it_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.show_data_it_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form S/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.show_data_it_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form H/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.show_data_it_approval') }}">
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
                        <a href="{{ route('website.new-folder.show_it_mgr_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.show_it_mgr_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form S/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.show_it_mgr_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form H/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.show_it_mgr_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form VPN</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#approved_it_mgr" data-bs-toggle="collapse"
                    href="#">
                    <i class="bi bi-clock-history"></i><span>ITD Manager History</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="approved_it_mgr" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_data_it_mgr_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.folder-access.show_data_it_mgr_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Folder Access</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.new-folder.show_data_it_mgr_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.show_data_it_mgr_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form S/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.show_data_it_mgr_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form H/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.show_data_it_mgr_approval') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form VPN</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->
        @endcan

        @can('can_execution')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#execution" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-rocket-takeoff"></i></i><span>Execution</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="execution" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_execution') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.folder-access.show_execution') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Folder Access</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.new-folder.show_execution') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.show_execution') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form S/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.show_execution') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form H/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.show_execution') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form VPN</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#finished" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-clipboard-check"></i><span>Finished</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="finished" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_data_execution') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.folder-access.show_data_execution') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Folder Access</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.new-folder.show_data_execution') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.show_data_execution') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form S/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.show_data_execution') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form H/W Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.show_data_execution') }}">
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
                        <a href="{{ route('website.folder.show_data_folder') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.subfolder.show_data_subfolder') }}">
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
