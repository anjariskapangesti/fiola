<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('website.auth.home') }}"
            class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.auth.home') ? 'active' : '') }}">
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
                <ul id="forms-nav" class="nav-content collapse 
                {{ (Route::is('website.account.create') || 
                    Route::is('website.folder-access.create') || 
                    Route::is('website.new-folder.create') || 
                    Route::is('website.software.create') || 
                    Route::is('website.hardware.create') || 
                    Route::is('website.vpn.create') || 
                    Route::is('website.project.create') || 
                    Route::is('website.fitur.create') || 
                    Route::is('website.relayout.create') ? 'show' : '') }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.create') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.account.create') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.folder-access.create') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.folder-access.create') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Folder Access</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.new-folder.create') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.new-folder.create') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.create') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.software.create') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Software Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.create') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.hardware.create') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Request Device</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.create') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.vpn.create') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form VPN</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.project.create') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.project.create') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Request Project</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.fitur.create') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.fitur.create') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Request Fitur</span>
                        </a>
                    </li>
                    {{-- @can('can_approve_it')
                    <li>
                        <a href="{{ route('website.network.create') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.network.create') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Network Change</span>
                        </a>
                    </li>
                    @endcan
                    <li>
                        <a href="{{ route('website.relayout.create') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.relayout.create') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Relayout</span>
                        </a>
                    </li> --}}
                </ul>
            </li><!-- End Forms Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#track_forms" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-search"></i><span>Track Forms</span>
                        @if(App\Models\AppHelper::confirms_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="confirms_count">{{ App\Models\AppHelper::confirms_count() }}</span>
                        @endif
                        <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="track_forms" class="nav-content collapse 
                {{ (Route::is('website.account.show_data_form') || 
                    Route::is('website.folder-access.show_data_form') || 
                    Route::is('website.new-folder.show_data_form') || 
                    Route::is('website.software.show_data_form') || 
                    Route::is('website.hardware.show_data_form') || 
                    Route::is('website.vpn.show_data_form') || 
                    Route::is('website.project.show_data_form') || 
                    Route::is('website.fitur.show_data_form') ? 'show' : '') }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_data_form') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.account.show_data_form') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Account</span>
                            @if(App\Models\AppHelper::account_confirm_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::account_confirm_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.folder-access.show_data_form') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.folder-access.show_data_form') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Folder Access</span>
                            @if(App\Models\AppHelper::folderaccess_confirm_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::folderaccess_confirm_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.new-folder.show_data_form') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.new-folder.show_data_form') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form New Folder</span>
                            @if(App\Models\AppHelper::newfolder_confirm_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::newfolder_confirm_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.show_data_form') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.software.show_data_form') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Software Installation</span>
                            @if(App\Models\AppHelper::software_confirm_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::software_confirm_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.show_data_form') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.hardware.show_data_form') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Request Device</span>
                            @if(App\Models\AppHelper::hardware_confirm_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::hardware_confirm_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.show_data_form') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.vpn.show_data_form') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form VPN</span>
                            @if(App\Models\AppHelper::vpn_confirm_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::vpn_confirm_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.project.show_data_form') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.project.show_data_form') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Request Project</span>
                            @if(App\Models\AppHelper::project_confirm_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::project_confirm_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.fitur.show_data_form') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.fitur.show_data_form') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Request Fitur</span>
                            @if(App\Models\AppHelper::fitur_confirm_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::fitur_confirm_count() }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->
        @endcan

        @can('can_approve_mgr')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#approval_mgr" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-hourglass"></i><span>Manager Approvals</span>
                        @if(App\Models\AppHelper::manager_approvals_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="manager_approvals_count">{{ App\Models\AppHelper::manager_approvals_count() }}</span>
                        @endif
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="approval_mgr" class="nav-content collapse 
                {{ (Route::is('website.account.show_manager_approval') || 
                    Route::is('website.folder-access.show_manager_approval') || 
                    Route::is('website.new-folder.show_manager_approval') || 
                    Route::is('website.software.show_manager_approval') || 
                    Route::is('website.hardware.show_manager_approval') || 
                    Route::is('website.vpn.show_manager_approval') || 
                    Route::is('website.project.show_manager_approval') || 
                    Route::is('website.fitur.show_manager_approval') ? 'show' : '') }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_manager_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.account.show_manager_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Account</span>
                            @if(App\Models\AppHelper::account_mgr_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="account_mgr_count">{{ App\Models\AppHelper::account_mgr_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.folder-access.show_manager_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.folder-access.show_manager_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Folder Access</span>
                            @if(App\Models\AppHelper::folderaccess_mgr_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::folderaccess_mgr_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.new-folder.show_manager_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.new-folder.show_manager_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form New Folder</span>
                            @if(App\Models\AppHelper::newfolder_mgr_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::newfolder_mgr_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.show_manager_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.software.show_manager_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Software Installation</span>
                            @if(App\Models\AppHelper::software_mgr_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::software_mgr_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.show_manager_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.hardware.show_manager_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Request Device</span>
                            @if(App\Models\AppHelper::hardware_mgr_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::hardware_mgr_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.show_manager_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.vpn.show_manager_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form VPN</span>
                            @if(App\Models\AppHelper::vpn_mgr_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::vpn_mgr_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.project.show_manager_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.project.show_manager_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Request Project</span>
                            @if(App\Models\AppHelper::project_mgr_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::project_mgr_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.fitur.show_manager_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.fitur.show_manager_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Request Fitur</span>
                            @if(App\Models\AppHelper::fitur_mgr_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::fitur_mgr_count() }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#approved_mgr" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-clock-history"></i><span>Manager History</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="approved_mgr" class="nav-content collapse 
                {{ (Route::is('website.account.show_data_manager_approval') || 
                    Route::is('website.folder-access.show_data_manager_approval') || 
                    Route::is('website.new-folder.show_data_manager_approval') || 
                    Route::is('website.software.show_data_manager_approval') || 
                    Route::is('website.hardware.show_data_manager_approval') || 
                    Route::is('website.vpn.show_data_manager_approval') || 
                    Route::is('website.project.show_data_manager_approval') || 
                    Route::is('website.fitur.show_data_manager_approval') ? 'show' : '') }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_data_manager_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.account.show_data_manager_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.folder-access.show_data_manager_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.folder-access.show_data_manager_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Folder Access</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.new-folder.show_data_manager_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.new-folder.show_data_manager_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.show_data_manager_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.software.show_data_manager_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Software Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.show_data_manager_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.hardware.show_data_manager_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Request Device</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.show_data_manager_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.vpn.show_data_manager_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form VPN</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.project.show_data_manager_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.project.show_data_manager_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Request Project</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.fitur.show_data_manager_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.fitur.show_data_manager_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Request Fitur</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->
        @endcan

        @can('can_approve_it')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#approval_it" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-hourglass"></i><span>ITD Approvals</span>
                        @if(App\Models\AppHelper::it_approvals_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="it_approvals_count">{{ App\Models\AppHelper::it_approvals_count() }}</span>
                        @endif
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="approval_it" class="nav-content collapse 
                {{ (Route::is('website.account.show_it_approval') || 
                    Route::is('website.folder-access.show_it_approval') || 
                    Route::is('website.new-folder.show_it_approval') || 
                    Route::is('website.software.show_it_approval') || 
                    Route::is('website.hardware.show_it_approval') || 
                    Route::is('website.vpn.show_it_approval') || 
                    Route::is('website.project.show_it_approval') || 
                    Route::is('website.fitur.show_it_approval') ? 'show' : '') }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_it_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.account.show_it_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Account</span>
                            @if(App\Models\AppHelper::account_it_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="account_it_count">{{ App\Models\AppHelper::account_it_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.folder-access.show_it_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.folder-access.show_it_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Folder Access</span>
                            @if(App\Models\AppHelper::folderaccess_it_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::folderaccess_it_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.new-folder.show_it_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.new-folder.show_it_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form New Folder</span>
                            @if(App\Models\AppHelper::newfolder_it_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::newfolder_it_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.show_it_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.software.show_it_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Software Installation</span>
                            @if(App\Models\AppHelper::software_it_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::software_it_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.show_it_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.hardware.show_it_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Request Device</span>
                            @if(App\Models\AppHelper::hardware_it_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::hardware_it_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.show_it_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.vpn.show_it_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form VPN</span>
                            @if(App\Models\AppHelper::vpn_it_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::vpn_it_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.project.show_it_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.project.show_it_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Request Project</span>
                            @if(App\Models\AppHelper::project_it_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::project_it_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.fitur.show_it_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.fitur.show_it_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Request Fitur</span>
                            @if(App\Models\AppHelper::fitur_it_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::fitur_it_count() }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#approved_it" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-clock-history"></i><span>ITD History</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="approved_it" class="nav-content collapse 
                {{ (Route::is('website.account.show_data_it_approval') || 
                    Route::is('website.folder-access.show_data_it_approval') || 
                    Route::is('website.new-folder.show_data_it_approval') || 
                    Route::is('website.software.show_data_it_approval') || 
                    Route::is('website.hardware.show_data_it_approval') || 
                    Route::is('website.vpn.show_data_it_approval') || 
                    Route::is('website.project.show_data_it_approval') || 
                    Route::is('website.fitur.show_data_it_approval') ? 'show' : '') }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_data_it_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.account.show_data_it_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.folder-access.show_data_it_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.folder-access.show_data_it_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Folder Access</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.new-folder.show_data_it_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.new-folder.show_data_it_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.show_data_it_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.software.show_data_it_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Software Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.show_data_it_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.hardware.show_data_it_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Request Device</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.show_data_it_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.vpn.show_data_it_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form VPN</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.project.show_data_it_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.project.show_data_it_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Request Project</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.fitur.show_data_it_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.fitur.show_data_it_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Request Fitur</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->
        @endcan

        @can('can_approve_it_mgr')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#approval_it_mgr" data-bs-toggle="collapse"
                    href="#">
                    <i class="bi bi-hourglass"></i><span>ITD MGR Approvals</span>
                        @if(App\Models\AppHelper::it_mgr_approvals_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::it_mgr_approvals_count() }}</span>
                        @endif
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="approval_it_mgr" class="nav-content collapse 
                {{ (Route::is('website.account.show_it_mgr_approval') || 
                    Route::is('website.folder-access.show_it_mgr_approval') || 
                    Route::is('website.new-folder.show_it_mgr_approval') || 
                    Route::is('website.software.show_it_mgr_approval') || 
                    Route::is('website.hardware.show_it_mgr_approval') || 
                    Route::is('website.vpn.show_it_mgr_approval') || 
                    Route::is('website.project.show_it_mgr_approval') || 
                    Route::is('website.fitur.show_it_mgr_approval') ? 'show' : '') }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_it_mgr_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.account.show_it_mgr_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Account</span>
                            @if(App\Models\AppHelper::account_it_mgr_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::account_it_mgr_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.folder-access.show_it_mgr_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.folder-access.show_it_mgr_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Folder Access</span>
                            @if(App\Models\AppHelper::folderaccess_it_mgr_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::folderaccess_it_mgr_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.new-folder.show_it_mgr_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.new-folder.show_it_mgr_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form New Folder</span>
                            @if(App\Models\AppHelper::newfolder_it_mgr_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::newfolder_it_mgr_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.show_it_mgr_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.software.show_it_mgr_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Software Installation</span>
                            @if(App\Models\AppHelper::software_it_mgr_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::software_it_mgr_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.show_it_mgr_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.hardware.show_it_mgr_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Request Device</span>
                            @if(App\Models\AppHelper::hardware_it_mgr_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::hardware_it_mgr_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.show_it_mgr_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.vpn.show_it_mgr_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form VPN</span>
                            @if(App\Models\AppHelper::vpn_it_mgr_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::vpn_it_mgr_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.project.show_it_mgr_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.project.show_it_mgr_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Request Project</span>
                            @if(App\Models\AppHelper::project_it_mgr_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::project_it_mgr_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.fitur.show_it_mgr_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.fitur.show_it_mgr_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Request Fitur</span>
                            @if(App\Models\AppHelper::fitur_it_mgr_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::fitur_it_mgr_count() }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#approved_it_mgr" data-bs-toggle="collapse"
                    href="#">
                    <i class="bi bi-clock-history"></i><span>ITD MGR History</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="approved_it_mgr" class="nav-content collapse 
                {{ (Route::is('website.account.show_data_it_mgr_approval') || 
                    Route::is('website.folder-access.show_data_it_mgr_approval') || 
                    Route::is('website.new-folder.show_data_it_mgr_approval') || 
                    Route::is('website.software.show_data_it_mgr_approval') || 
                    Route::is('website.hardware.show_data_it_mgr_approval') || 
                    Route::is('website.vpn.show_data_it_mgr_approval') || 
                    Route::is('website.project.show_data_it_mgr_approval') || 
                    Route::is('website.fitur.show_data_it_mgr_approval') ? 'show' : '') }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_data_it_mgr_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.account.show_data_it_mgr_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.folder-access.show_data_it_mgr_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.folder-access.show_data_it_mgr_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Folder Access</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.new-folder.show_data_it_mgr_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.new-folder.show_data_it_mgr_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.show_data_it_mgr_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.software.show_data_it_mgr_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Software Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.show_data_it_mgr_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.hardware.show_data_it_mgr_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Request Device</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.show_data_it_mgr_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.vpn.show_data_it_mgr_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form VPN</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.project.show_data_it_mgr_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.project.show_data_it_mgr_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Request Project</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.fitur.show_data_it_mgr_approval') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.fitur.show_data_it_mgr_approval') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Request Fitur</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->
        @endcan

        @can('can_execution')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#execution" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-rocket-takeoff"></i></i><span>Execution</span>
                        @if(App\Models\AppHelper::execution_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::execution_count() }}</span>
                        @endif
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="execution" class="nav-content collapse 
                {{ (Route::is('website.account.show_execution') || 
                    Route::is('website.folder-access.show_execution') || 
                    Route::is('website.new-folder.show_execution') || 
                    Route::is('website.software.show_execution') || 
                    Route::is('website.hardware.show_execution') || 
                    Route::is('website.vpn.show_execution') || 
                    Route::is('website.project.show_execution') || 
                    Route::is('website.fitur.show_execution') ? 'show' : '') }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_execution') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.account.show_execution') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Account</span>
                            @if(App\Models\AppHelper::account_execution_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::account_execution_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.folder-access.show_execution') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.folder-access.show_execution') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Folder Access</span>
                            @if(App\Models\AppHelper::folderaccess_execution_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::folderaccess_execution_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.new-folder.show_execution') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.new-folder.show_execution') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form New Folder</span>
                            @if(App\Models\AppHelper::newfolder_execution_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::newfolder_execution_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.show_execution') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.software.show_execution') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Software Installation</span>
                            @if(App\Models\AppHelper::software_execution_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::software_execution_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.show_execution') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.hardware.show_execution') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Request Device</span>
                            @if(App\Models\AppHelper::hardware_execution_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::hardware_execution_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.show_execution') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.vpn.show_execution') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form VPN</span>
                            @if(App\Models\AppHelper::vpn_execution_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::vpn_execution_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.project.show_execution') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.project.show_execution') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Request Project</span>
                            @if(App\Models\AppHelper::project_execution_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::project_execution_count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.fitur.show_execution') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.fitur.show_execution') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Form Request Fitur</span>
                            @if(App\Models\AppHelper::fitur_execution_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::fitur_execution_count() }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#finished" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-clipboard-check"></i><span>Finished</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="finished" class="nav-content collapse 
                {{ (Route::is('website.account.show_data_execution') || 
                    Route::is('website.folder-access.show_data_execution') || 
                    Route::is('website.new-folder.show_data_execution') || 
                    Route::is('website.software.show_data_execution') || 
                    Route::is('website.hardware.show_data_execution') || 
                    Route::is('website.vpn.show_data_execution') || 
                    Route::is('website.project.show_data_execution') || 
                    Route::is('website.fitur.show_data_execution') ? 'show' : '') }}" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('website.account.show_data_execution') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.account.show_data_execution') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.folder-access.show_data_execution') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.folder-access.show_data_execution') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Folder Access</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.new-folder.show_data_execution') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.new-folder.show_data_execution') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form New Folder</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.software.show_data_execution') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.software.show_data_execution') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Software Installation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.hardware.show_data_execution') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.hardware.show_data_execution') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Request Device</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.vpn.show_data_execution') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.vpn.show_data_execution') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form VPN</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.project.show_data_execution') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.project.show_data_execution') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Request Project</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('website.fitur.show_data_execution') }}"
                        class="list-group-item list-group-item-action py-2 ripple {{ (Route::is('website.fitur.show_data_execution') ? 'active' : '') }}">
                            <i class="bi bi-record-circle-fill"></i><span>Data Form Request Fitur</span>
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
