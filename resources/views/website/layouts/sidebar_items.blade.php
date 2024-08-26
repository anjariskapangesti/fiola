<ul class="menu-sub">
    <li
        class="menu-item {{ Route::is('website.account.' . $link) || Route::is('website.account.edit') ? 'active' : '' }}">
        <a href="{{ route('website.account.' . $link) }}" class="menu-link">
            <div data-i18n="Account">{{ $text }} Account</div>
            @if ($link == 'manager_approval' && App\Models\AppHelper::account_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="account_mgr_count">{{ App\Models\AppHelper::account_mgr_count() }}</span>
            @endif

            @if ($link == 'it_approval' && App\Models\AppHelper::account_it_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="account_it_count">{{ App\Models\AppHelper::account_it_count() }}</span>
            @endif

            @if ($link == 'it_mgr_approval' && App\Models\AppHelper::account_it_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="account_it_mgr_count">{{ App\Models\AppHelper::account_it_mgr_count() }}</span>
            @endif

            @if ($link == 'execution' && App\Models\AppHelper::account_execution_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="account_execution_count">{{ App\Models\AppHelper::account_execution_count() }}</span>
            @endif

            @if ($link == 'list' && App\Models\AppHelper::account_confirm_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="account_confirm_count">{{ App\Models\AppHelper::account_confirm_count() }}</span>
            @endif
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.folder-access.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.folder-access.' . $link) }}" class="menu-link">
            <div data-i18n="Folder Access">{{ $text }} Folder Access</div>
            @if ($link == 'manager_approval' && App\Models\AppHelper::folderaccess_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="folderaccess_mgr_count">{{ App\Models\AppHelper::folderaccess_mgr_count() }}</span>
            @endif

            @if ($link == 'it_approval' && App\Models\AppHelper::folderaccess_it_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="folderaccess_it_count">{{ App\Models\AppHelper::folderaccess_it_count() }}</span>
            @endif

            @if ($link == 'it_mgr_approval' && App\Models\AppHelper::folderaccess_it_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="folderaccess_it_mgr_count">{{ App\Models\AppHelper::folderaccess_it_mgr_count() }}</span>
            @endif

            @if ($link == 'execution' && App\Models\AppHelper::folderaccess_execution_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="folderaccess_execution_count">{{ App\Models\AppHelper::folderaccess_execution_count() }}</span>
            @endif

            @if ($link == 'list' && App\Models\AppHelper::folderaccess_confirm_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="folderaccess_confirm_count">{{ App\Models\AppHelper::folderaccess_confirm_count() }}</span>
            @endif
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.new-folder.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.new-folder.' . $link) }}" class="menu-link">
            <div data-i18n="New Folder">{{ $text }} New Folder</div>
            @if ($link == 'manager_approval' && App\Models\AppHelper::newfolder_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="newfolder_mgr_count">{{ App\Models\AppHelper::newfolder_mgr_count() }}</span>
            @endif

            @if ($link == 'it_approval' && App\Models\AppHelper::newfolder_it_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="newfolder_it_count">{{ App\Models\AppHelper::newfolder_it_count() }}</span>
            @endif

            @if ($link == 'it_mgr_approval' && App\Models\AppHelper::newfolder_it_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="newfolder_it_mgr_count">{{ App\Models\AppHelper::newfolder_it_mgr_count() }}</span>
            @endif

            @if ($link == 'execution' && App\Models\AppHelper::newfolder_execution_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="newfolder_execution_count">{{ App\Models\AppHelper::newfolder_execution_count() }}</span>
            @endif

            @if ($link == 'list' && App\Models\AppHelper::newfolder_confirm_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="newfolder_confirm_count">{{ App\Models\AppHelper::newfolder_confirm_count() }}</span>
            @endif
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.software.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.software.' . $link) }}" class="menu-link">
            <div data-i18n="Software Installation">{{ $text }} Software Installation</div>
            @if ($link == 'manager_approval' && App\Models\AppHelper::software_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="software_mgr_count">{{ App\Models\AppHelper::software_mgr_count() }}</span>
            @endif

            @if ($link == 'it_approval' && App\Models\AppHelper::software_it_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="software_it_count">{{ App\Models\AppHelper::software_it_count() }}</span>
            @endif

            @if ($link == 'it_mgr_approval' && App\Models\AppHelper::software_it_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="software_it_mgr_count">{{ App\Models\AppHelper::software_it_mgr_count() }}</span>
            @endif

            @if ($link == 'execution' && App\Models\AppHelper::software_execution_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="software_execution_count">{{ App\Models\AppHelper::software_execution_count() }}</span>
            @endif

            @if ($link == 'list' && App\Models\AppHelper::software_confirm_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="software_confirm_count">{{ App\Models\AppHelper::software_confirm_count() }}</span>
            @endif
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.hardware.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.hardware.' . $link) }}" class="menu-link">
            <div data-i18n="Request Device">{{ $text }} Request Device
            </div>
            @if ($link == 'manager_approval' && App\Models\AppHelper::hardware_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="hardware_mgr_count">{{ App\Models\AppHelper::hardware_mgr_count() }}</span>
            @endif

            @if ($link == 'it_approval' && App\Models\AppHelper::hardware_it_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="hardware_it_count">{{ App\Models\AppHelper::hardware_it_count() }}</span>
            @endif

            @if ($link == 'it_mgr_approval' && App\Models\AppHelper::hardware_it_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="hardware_it_mgr_count">{{ App\Models\AppHelper::hardware_it_mgr_count() }}</span>
            @endif

            @if ($link == 'execution' && App\Models\AppHelper::hardware_execution_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="hardware_execution_count">{{ App\Models\AppHelper::hardware_execution_count() }}</span>
            @endif

            @if ($link == 'list' && App\Models\AppHelper::hardware_confirm_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="hardware_confirm_count">{{ App\Models\AppHelper::hardware_confirm_count() }}</span>
            @endif
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.vpn.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.vpn.' . $link) }}" class="menu-link">
            <div data-i18n="VPN">{{ $text }} VPN
            </div>
            @if ($link == 'manager_approval' && App\Models\AppHelper::vpn_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="vpn_mgr_count">{{ App\Models\AppHelper::vpn_mgr_count() }}</span>
            @endif

            @if ($link == 'it_approval' && App\Models\AppHelper::vpn_it_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="vpn_it_count">{{ App\Models\AppHelper::vpn_it_count() }}</span>
            @endif

            @if ($link == 'it_mgr_approval' && App\Models\AppHelper::vpn_it_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="vpn_it_mgr_count">{{ App\Models\AppHelper::vpn_it_mgr_count() }}</span>
            @endif

            @if ($link == 'execution' && App\Models\AppHelper::vpn_execution_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="vpn_execution_count">{{ App\Models\AppHelper::vpn_execution_count() }}</span>
            @endif

            @if ($link == 'list' && App\Models\AppHelper::vpn_confirm_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="vpn_confirm_count">{{ App\Models\AppHelper::vpn_confirm_count() }}</span>
            @endif
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.project.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.project.' . $link) }}" class="menu-link">
            <div data-i18n="Request Project">{{ $text }} Request Project
            </div>
            @if ($link == 'manager_approval' && App\Models\AppHelper::project_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="project_mgr_count">{{ App\Models\AppHelper::project_mgr_count() }}</span>
            @endif

            @if ($link == 'it_approval' && App\Models\AppHelper::project_it_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="project_it_count">{{ App\Models\AppHelper::project_it_count() }}</span>
            @endif

            @if ($link == 'it_mgr_approval' && App\Models\AppHelper::project_it_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="project_it_mgr_count">{{ App\Models\AppHelper::project_it_mgr_count() }}</span>
            @endif

            @if ($link == 'execution' && App\Models\AppHelper::project_execution_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="project_execution_count">{{ App\Models\AppHelper::project_execution_count() }}</span>
            @endif

            @if ($link == 'list' && App\Models\AppHelper::project_confirm_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="project_confirm_count">{{ App\Models\AppHelper::project_confirm_count() }}</span>
            @endif
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.fitur.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.fitur.' . $link) }}" class="menu-link">
            <div data-i18n="Request Fitur">{{ $text }} Request Fitur
            </div>
            @if ($link == 'manager_approval' && App\Models\AppHelper::fitur_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="fitur_mgr_count">{{ App\Models\AppHelper::fitur_mgr_count() }}</span>
            @endif

            @if ($link == 'it_approval' && App\Models\AppHelper::fitur_it_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="fitur_it_count">{{ App\Models\AppHelper::fitur_it_count() }}</span>
            @endif

            @if ($link == 'it_mgr_approval' && App\Models\AppHelper::fitur_it_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="fitur_it_mgr_count">{{ App\Models\AppHelper::fitur_it_mgr_count() }}</span>
            @endif

            @if ($link == 'execution' && App\Models\AppHelper::fitur_execution_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="fitur_execution_count">{{ App\Models\AppHelper::fitur_execution_count() }}</span>
            @endif

            @if ($link == 'list' && App\Models\AppHelper::fitur_confirm_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="fitur_confirm_count">{{ App\Models\AppHelper::fitur_confirm_count() }}</span>
            @endif
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.relayout.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.relayout.' . $link) }}" class="menu-link">
            <div data-i18n="Relayout">{{ $text }} Relayout
            </div>
            @if ($link == 'manager_approval' && App\Models\AppHelper::relayout_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="relayout_mgr_count">{{ App\Models\AppHelper::relayout_mgr_count() }}</span>
            @endif

            @if ($link == 'it_approval' && App\Models\AppHelper::relayout_it_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="relayout_it_count">{{ App\Models\AppHelper::relayout_it_count() }}</span>
            @endif

            @if ($link == 'it_mgr_approval' && App\Models\AppHelper::relayout_it_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="relayout_it_mgr_count">{{ App\Models\AppHelper::relayout_it_mgr_count() }}</span>
            @endif

            @if ($link == 'execution' && App\Models\AppHelper::relayout_execution_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="relayout_execution_count">{{ App\Models\AppHelper::relayout_execution_count() }}</span>
            @endif

            @if ($link == 'list' && App\Models\AppHelper::relayout_confirm_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="relayout_confirm_count">{{ App\Models\AppHelper::relayout_confirm_count() }}</span>
            @endif
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.network.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.network.' . $link) }}" class="menu-link">
            <div data-i18n="Network Change">{{ $text }} Network Change
            </div>
            @if ($link == 'manager_approval' && App\Models\AppHelper::network_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="network_mgr_count">{{ App\Models\AppHelper::network_mgr_count() }}</span>
            @endif

            @if ($link == 'it_approval' && App\Models\AppHelper::network_it_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="network_it_count">{{ App\Models\AppHelper::network_it_count() }}</span>
            @endif

            @if ($link == 'it_mgr_approval' && App\Models\AppHelper::network_it_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="network_it_mgr_count">{{ App\Models\AppHelper::network_it_mgr_count() }}</span>
            @endif

            @if ($link == 'execution' && App\Models\AppHelper::network_execution_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="network_execution_count">{{ App\Models\AppHelper::network_execution_count() }}</span>
            @endif

            @if ($link == 'list' && App\Models\AppHelper::network_confirm_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="network_confirm_count">{{ App\Models\AppHelper::network_confirm_count() }}</span>
            @endif
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.akses_sistem.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.akses_sistem.' . $link) }}" class="menu-link">
            <div data-i18n="Akses Sistem">{{ $text }} Akses Sistem
            </div>
            @if ($link == 'manager_approval' && App\Models\AppHelper::akses_sistem_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="akses_sistem_mgr_count">{{ App\Models\AppHelper::akses_sistem_mgr_count() }}</span>
            @endif

            @if ($link == 'it_approval' && App\Models\AppHelper::akses_sistem_it_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="akses_sistem_it_count">{{ App\Models\AppHelper::akses_sistem_it_count() }}</span>
            @endif

            @if ($link == 'it_mgr_approval' && App\Models\AppHelper::akses_sistem_it_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="akses_sistem_it_mgr_count">{{ App\Models\AppHelper::akses_sistem_it_mgr_count() }}</span>
            @endif

            @if ($link == 'execution' && App\Models\AppHelper::akses_sistem_execution_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="akses_sistem_execution_count">{{ App\Models\AppHelper::akses_sistem_execution_count() }}</span>
            @endif

            @if ($link == 'list' && App\Models\AppHelper::akses_sistem_confirm_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="akses_sistem_confirm_count">{{ App\Models\AppHelper::akses_sistem_confirm_count() }}</span>
            @endif
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.incident_report.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.incident_report.' . $link) }}" class="menu-link">
            <div data-i18n="Incident Report">{{ $text }} Incident Report
            </div>
            @if ($link == 'manager_approval' && App\Models\AppHelper::incident_report_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="incident_report_mgr_count">{{ App\Models\AppHelper::incident_report_mgr_count() }}</span>
            @endif

            @if ($link == 'it_approval' && App\Models\AppHelper::incident_report_it_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="incident_report_it_count">{{ App\Models\AppHelper::incident_report_it_count() }}</span>
            @endif

            @if ($link == 'it_mgr_approval' && App\Models\AppHelper::incident_report_it_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="incident_report_it_mgr_count">{{ App\Models\AppHelper::incident_report_it_mgr_count() }}</span>
            @endif

            @if ($link == 'execution' && App\Models\AppHelper::incident_report_execution_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="incident_report_execution_count">{{ App\Models\AppHelper::incident_report_execution_count() }}</span>
            @endif

            @if ($link == 'list' && App\Models\AppHelper::incident_report_confirm_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="incident_report_confirm_count">{{ App\Models\AppHelper::incident_report_confirm_count() }}</span>
            @endif
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.izin.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.izin.' . $link) }}" class="menu-link">
            <div data-i18n="Izin Memasuki Area Level 3">{{ $text }} Izin Memasuki Area Level 3
            </div>
            @if ($link == 'manager_approval' && App\Models\AppHelper::izin_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="izin_mgr_count">{{ App\Models\AppHelper::izin_mgr_count() }}</span>
            @endif

            @if ($link == 'it_approval' && App\Models\AppHelper::izin_it_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="izin_it_count">{{ App\Models\AppHelper::izin_it_count() }}</span>
            @endif

            @if ($link == 'it_mgr_approval' && App\Models\AppHelper::izin_it_mgr_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="izin_it_mgr_count">{{ App\Models\AppHelper::izin_it_mgr_count() }}</span>
            @endif

            @if ($link == 'execution' && App\Models\AppHelper::izin_execution_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="izin_execution_count">{{ App\Models\AppHelper::izin_execution_count() }}</span>
            @endif

            @if ($link == 'list' && App\Models\AppHelper::izin_confirm_count() > 0)
                &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                    id="izin_confirm_count">{{ App\Models\AppHelper::izin_confirm_count() }}</span>
            @endif
        </a>
    </li>
</ul>

{{-- <ul id="forms-nav"
    class="nav-content collapse 
                {{ Route::is('website.account.create') ||
                Route::is('website.folder-access.create') ||
                Route::is('website.new-folder.create') ||
                Route::is('website.software.create') ||
                Route::is('website.hardware.create') ||
                Route::is('website.vpn.create') ||
                Route::is('website.project.create') ||
                Route::is('website.fitur.create') ||
                Route::is('website.relayout.create')
                    ? 'show'
                    : '' }}"
    data-bs-parent="#sidebar-nav">
    <li>
        <a href="{{ route('website.account.create') }}"
            class="list-group-item list-group-item-action py-2 ripple {{ Route::is('website.account.create') ? 'active' : '' }}">
            <i class="bi bi-record-circle-fill"></i><span>Form Account</span>
        </a>
    </li>
    <li>
        <a href="{{ route('website.folder-access.create') }}"
            class="list-group-item list-group-item-action py-2 ripple {{ Route::is('website.folder-access.create') ? 'active' : '' }}">
            <i class="bi bi-record-circle-fill"></i><span>Form Folder Access</span>
        </a>
    </li>
    <li>
        <a href="{{ route('website.new-folder.create') }}"
            class="list-group-item list-group-item-action py-2 ripple {{ Route::is('website.new-folder.create') ? 'active' : '' }}">
            <i class="bi bi-record-circle-fill"></i><span>Form New Folder</span>
        </a>
    </li>
    <li>
        <a href="{{ route('website.software.create') }}"
            class="list-group-item list-group-item-action py-2 ripple {{ Route::is('website.software.create') ? 'active' : '' }}">
            <i class="bi bi-record-circle-fill"></i><span>Form Software Installation</span>
        </a>
    </li>
    <li>
        <a href="{{ route('website.hardware.create') }}"
            class="list-group-item list-group-item-action py-2 ripple {{ Route::is('website.hardware.create') ? 'active' : '' }}">
            <i class="bi bi-record-circle-fill"></i><span>Form Request Device</span>
        </a>
    </li>
    <li>
        <a href="{{ route('website.vpn.create') }}"
            class="list-group-item list-group-item-action py-2 ripple {{ Route::is('website.vpn.create') ? 'active' : '' }}">
            <i class="bi bi-record-circle-fill"></i><span>Form VPN</span>
        </a>
    </li>
    <li>
        <a href="{{ route('website.project.create') }}"
            class="list-group-item list-group-item-action py-2 ripple {{ Route::is('website.project.create') ? 'active' : '' }}">
            <i class="bi bi-record-circle-fill"></i><span>Form Request Project</span>
        </a>
    </li>
    <li>
        <a href="{{ route('website.fitur.create') }}"
            class="list-group-item list-group-item-action py-2 ripple {{ Route::is('website.fitur.create') ? 'active' : '' }}">
            <i class="bi bi-record-circle-fill"></i><span>Form Request Fitur</span>
        </a>
    </li>
    <li>
        <a href="{{ route('website.relayout.create') }}"
            class="list-group-item list-group-item-action py-2 ripple {{ Route::is('website.relayout.create') ? 'active' : '' }}">
            <i class="bi bi-record-circle-fill"></i><span>Form Relayout</span>
        </a>
    </li>
    @if (Auth::user()->hasDepartment('ITD'))
        <li>
            <a href="{{ route('website.network.create') }}"
                class="list-group-item list-group-item-action py-2 ripple {{ Route::is('website.network.create') ? 'active' : '' }}">
                <i class="bi bi-record-circle-fill"></i><span>Form Network Change</span>
            </a>
        </li>
    @endif

</ul> --}}
