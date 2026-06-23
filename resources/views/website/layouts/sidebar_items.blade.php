<ul class="menu-sub">
    @if (!($only_reschedule ?? false) && !($is_director_project ?? false))
        @php $routeName = 'website.account.' . $link; @endphp
        @if (Route::has($routeName))
            <li class="menu-item {{ Route::is($routeName) || Route::is('website.account.edit') ? 'active' : '' }}">
                <a href="{{ route($routeName) }}" class="menu-link">
                    <div data-i18n="Account">{{ $text }} Account</div>
                    @if ($link == 'manager_approval' && App\Models\AppHelper::account_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="account_mgr_count">{{ App\Models\AppHelper::account_mgr_count() }}</span>
                    @endif

                    @if ($link == 'it_approval' && App\Models\AppHelper::account_it_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="account_it_count">{{ App\Models\AppHelper::account_it_count() }}</span>
                    @endif

                    @if ($link == 'it_mgr_approval' && App\Models\AppHelper::account_it_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="account_it_mgr_count">{{ App\Models\AppHelper::account_it_mgr_count() }}</span>
                    @endif

                    @if ($link == 'execution' && App\Models\AppHelper::account_execution_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="account_execution_count">{{ App\Models\AppHelper::account_execution_count() }}</span>
                    @endif

                    @if ($link == 'list' && App\Models\AppHelper::account_confirm_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="account_confirm_count">{{ App\Models\AppHelper::account_confirm_count() }}</span>
                    @endif
                </a>
            </li>
        @endif

        @php $routeName = 'website.folder-access.' . $link; @endphp
        @if (Route::has($routeName))
            <li class="menu-item {{ Route::is($routeName) ? 'active' : '' }}">
                <a href="{{ route($routeName) }}" class="menu-link">
                    <div data-i18n="Folder Access">{{ $text }} Folder Access</div>
                    @if ($link == 'manager_approval' && App\Models\AppHelper::folderaccess_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="folderaccess_mgr_count">{{ App\Models\AppHelper::folderaccess_mgr_count() }}</span>
                    @endif

                    @if ($link == 'it_approval' && App\Models\AppHelper::folderaccess_it_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="folderaccess_it_count">{{ App\Models\AppHelper::folderaccess_it_count() }}</span>
                    @endif

                    @if ($link == 'it_mgr_approval' && App\Models\AppHelper::folderaccess_it_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="folderaccess_it_mgr_count">{{ App\Models\AppHelper::folderaccess_it_mgr_count() }}</span>
                    @endif

                    @if ($link == 'execution' && App\Models\AppHelper::folderaccess_execution_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="folderaccess_execution_count">{{ App\Models\AppHelper::folderaccess_execution_count() }}</span>
                    @endif

                    @if ($link == 'list' && App\Models\AppHelper::folderaccess_confirm_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="folderaccess_confirm_count">{{ App\Models\AppHelper::folderaccess_confirm_count() }}</span>
                    @endif
                </a>
            </li>
        @endif

        @php $routeName = 'website.new-folder.' . $link; @endphp
        @if (Route::has($routeName))
            <li class="menu-item {{ Route::is($routeName) ? 'active' : '' }}">
                <a href="{{ route($routeName) }}" class="menu-link">
                    <div data-i18n="New Folder">{{ $text }} New Folder</div>
                    @if ($link == 'manager_approval' && App\Models\AppHelper::newfolder_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="newfolder_mgr_count">{{ App\Models\AppHelper::newfolder_mgr_count() }}</span>
                    @endif

                    @if ($link == 'it_approval' && App\Models\AppHelper::newfolder_it_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="newfolder_it_count">{{ App\Models\AppHelper::newfolder_it_count() }}</span>
                    @endif

                    @if ($link == 'it_mgr_approval' && App\Models\AppHelper::newfolder_it_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="newfolder_it_mgr_count">{{ App\Models\AppHelper::newfolder_it_mgr_count() }}</span>
                    @endif

                    @if ($link == 'execution' && App\Models\AppHelper::newfolder_execution_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="newfolder_execution_count">{{ App\Models\AppHelper::newfolder_execution_count() }}</span>
                    @endif

                    @if ($link == 'list' && App\Models\AppHelper::newfolder_confirm_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="newfolder_confirm_count">{{ App\Models\AppHelper::newfolder_confirm_count() }}</span>
                    @endif
                </a>
            </li>
        @endif

        @php $routeName = 'website.software.' . $link; @endphp
        @if (Route::has($routeName))
            <li class="menu-item {{ Route::is($routeName) ? 'active' : '' }}">
                <a href="{{ route($routeName) }}" class="menu-link">
                    <div data-i18n="Software Installation">{{ $text }} Software Installation</div>
                    @if ($link == 'manager_approval' && App\Models\AppHelper::software_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="software_mgr_count">{{ App\Models\AppHelper::software_mgr_count() }}</span>
                    @endif

                    @if ($link == 'it_approval' && App\Models\AppHelper::software_it_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="software_it_count">{{ App\Models\AppHelper::software_it_count() }}</span>
                    @endif

                    @if ($link == 'it_mgr_approval' && App\Models\AppHelper::software_it_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="software_it_mgr_count">{{ App\Models\AppHelper::software_it_mgr_count() }}</span>
                    @endif

                    @if ($link == 'execution' && App\Models\AppHelper::software_execution_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="software_execution_count">{{ App\Models\AppHelper::software_execution_count() }}</span>
                    @endif

                    @if ($link == 'list' && App\Models\AppHelper::software_confirm_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="software_confirm_count">{{ App\Models\AppHelper::software_confirm_count() }}</span>
                    @endif
                </a>
            </li>
        @endif

        @php $routeName = 'website.hardware.' . $link; @endphp
        @if (Route::has($routeName))
            <li class="menu-item {{ Route::is($routeName) ? 'active' : '' }}">
                <a href="{{ route($routeName) }}" class="menu-link">
                    <div data-i18n="Request Device">{{ $text }} Request Device</div>
                    @if ($link == 'manager_approval' && App\Models\AppHelper::hardware_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="hardware_mgr_count">{{ App\Models\AppHelper::hardware_mgr_count() }}</span>
                    @endif

                    @if ($link == 'it_approval' && App\Models\AppHelper::hardware_it_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="hardware_it_count">{{ App\Models\AppHelper::hardware_it_count() }}</span>
                    @endif

                    @if ($link == 'it_mgr_approval' && App\Models\AppHelper::hardware_it_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="hardware_it_mgr_count">{{ App\Models\AppHelper::hardware_it_mgr_count() }}</span>
                    @endif

                    @if ($link == 'execution' && App\Models\AppHelper::hardware_execution_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="hardware_execution_count">{{ App\Models\AppHelper::hardware_execution_count() }}</span>
                    @endif

                    @if ($link == 'list' && App\Models\AppHelper::hardware_confirm_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="hardware_confirm_count">{{ App\Models\AppHelper::hardware_confirm_count() }}</span>
                    @endif
                </a>
            </li>
        @endif

        @php $routeName = 'website.vpn.' . $link; @endphp
        @if (Route::has($routeName))
            <li class="menu-item {{ Route::is($routeName) ? 'active' : '' }}">
                <a href="{{ route($routeName) }}" class="menu-link">
                    <div data-i18n="VPN">{{ $text }} VPN</div>
                    @if ($link == 'manager_approval' && App\Models\AppHelper::vpn_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="vpn_mgr_count">{{ App\Models\AppHelper::vpn_mgr_count() }}</span>
                    @endif

                    @if ($link == 'it_approval' && App\Models\AppHelper::vpn_it_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="vpn_it_count">{{ App\Models\AppHelper::vpn_it_count() }}</span>
                    @endif

                    @if ($link == 'it_mgr_approval' && App\Models\AppHelper::vpn_it_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="vpn_it_mgr_count">{{ App\Models\AppHelper::vpn_it_mgr_count() }}</span>
                    @endif

                    @if ($link == 'execution' && App\Models\AppHelper::vpn_execution_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="vpn_execution_count">{{ App\Models\AppHelper::vpn_execution_count() }}</span>
                    @endif

                    @if ($link == 'list' && App\Models\AppHelper::vpn_confirm_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="vpn_confirm_count">{{ App\Models\AppHelper::vpn_confirm_count() }}</span>
                    @endif
                </a>
            </li>
        @endif
    @endif

    @if (!($only_reschedule ?? false) || ($is_director_project ?? false))
        @php $routeName = 'website.project.' . $link; @endphp
        @if (Route::has($routeName))
            <li class="menu-item {{ Route::is($routeName) ? 'active' : '' }}">
                <a href="{{ route($routeName) }}" class="menu-link">
                    <div data-i18n="Request Project">{{ $text }} Request Project</div>
                    @if ($link == 'manager_approval' && App\Models\AppHelper::project_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="project_mgr_count">{{ App\Models\AppHelper::project_mgr_count() }}</span>
                    @endif

                    @if ($link == 'dir_approval' && App\Models\AppHelper::project_dir_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="project_dir_count">{{ App\Models\AppHelper::project_dir_count() }}</span>
                    @endif

                    @if ($link == 'list' && App\Models\AppHelper::project_confirm_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="project_confirm_count">{{ App\Models\AppHelper::project_confirm_count() }}</span>
                    @endif
                </a>
            </li>
        @endif
    @endif

    @if (!($only_reschedule ?? false) && !($is_director_project ?? false))
        @php $routeName = 'website.fitur.' . $link; @endphp
        @if (Route::has($routeName))
            <li class="menu-item {{ Route::is($routeName) ? 'active' : '' }}">
                <a href="{{ route($routeName) }}" class="menu-link">
                    <div data-i18n="Request Fitur">{{ $text }} Request Fitur</div>
                    @if ($link == 'manager_approval' && App\Models\AppHelper::fitur_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="fitur_mgr_count">{{ App\Models\AppHelper::fitur_mgr_count() }}</span>
                    @endif

                    @if ($link == 'it_approval' && App\Models\AppHelper::fitur_it_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="fitur_it_count">{{ App\Models\AppHelper::fitur_it_count() }}</span>
                    @endif

                    @if ($link == 'it_mgr_approval' && App\Models\AppHelper::fitur_it_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="fitur_it_mgr_count">{{ App\Models\AppHelper::fitur_it_mgr_count() }}</span>
                    @endif

                    @if ($link == 'execution' && App\Models\AppHelper::fitur_execution_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="fitur_execution_count">{{ App\Models\AppHelper::fitur_execution_count() }}</span>
                    @endif

                    @if ($link == 'list' && App\Models\AppHelper::fitur_confirm_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="fitur_confirm_count">{{ App\Models\AppHelper::fitur_confirm_count() }}</span>
                    @endif
                </a>
            </li>
        @endif

        @php $routeName = 'website.relayout.' . $link; @endphp
        @if (Route::has($routeName))
            <li class="menu-item {{ Route::is($routeName) ? 'active' : '' }}">
                <a href="{{ route($routeName) }}" class="menu-link">
                    <div data-i18n="Relayout">{{ $text }} Relayout</div>
                    @if ($link == 'manager_approval' && App\Models\AppHelper::relayout_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="relayout_mgr_count">{{ App\Models\AppHelper::relayout_mgr_count() }}</span>
                    @endif

                    @if ($link == 'it_approval' && App\Models\AppHelper::relayout_it_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="relayout_it_count">{{ App\Models\AppHelper::relayout_it_count() }}</span>
                    @endif

                    @if ($link == 'it_mgr_approval' && App\Models\AppHelper::relayout_it_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="relayout_it_mgr_count">{{ App\Models\AppHelper::relayout_it_mgr_count() }}</span>
                    @endif

                    @if ($link == 'execution' && App\Models\AppHelper::relayout_execution_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="relayout_execution_count">{{ App\Models\AppHelper::relayout_execution_count() }}</span>
                    @endif

                    @if ($link == 'list' && App\Models\AppHelper::relayout_confirm_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="relayout_confirm_count">{{ App\Models\AppHelper::relayout_confirm_count() }}</span>
                    @endif
                </a>
            </li>
        @endif

        @if (auth()->user()->hasDepartment('ITD'))
            @php $routeName = 'website.network.' . $link; @endphp
            @if (Route::has($routeName))
                <li class="menu-item {{ Route::is($routeName) ? 'active' : '' }}">
                    <a href="{{ route($routeName) }}" class="menu-link">
                        <div data-i18n="Network Change">{{ $text }} Network Change</div>
                        @if ($link == 'manager_approval' && App\Models\AppHelper::network_mgr_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="network_mgr_count">{{ App\Models\AppHelper::network_mgr_count() }}</span>
                        @endif

                        @if ($link == 'list' && App\Models\AppHelper::network_confirm_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="network_confirm_count">{{ App\Models\AppHelper::network_confirm_count() }}</span>
                        @endif
                    </a>
                </li>
            @endif
        @endif

        @php $routeName = 'website.akses_sistem.' . $link; @endphp
        @if (Route::has($routeName))
            <li class="menu-item {{ Route::is($routeName) ? 'active' : '' }}">
                <a href="{{ route($routeName) }}" class="menu-link">
                    <div data-i18n="Akses Sistem">{{ $text }} Akses Sistem</div>
                    @if ($link == 'manager_approval' && App\Models\AppHelper::akses_sistem_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="akses_sistem_mgr_count">{{ App\Models\AppHelper::akses_sistem_mgr_count() }}</span>
                    @endif

                    @if ($link == 'it_approval' && App\Models\AppHelper::akses_sistem_it_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="akses_sistem_it_count">{{ App\Models\AppHelper::akses_sistem_it_count() }}</span>
                    @endif

                    @if ($link == 'it_mgr_approval' && App\Models\AppHelper::akses_sistem_it_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="akses_sistem_it_mgr_count">{{ App\Models\AppHelper::akses_sistem_it_mgr_count() }}</span>
                    @endif

                    @if ($link == 'execution' && App\Models\AppHelper::akses_sistem_execution_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="akses_sistem_execution_count">{{ App\Models\AppHelper::akses_sistem_execution_count() }}</span>
                    @endif

                    @if ($link == 'list' && App\Models\AppHelper::akses_sistem_confirm_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="akses_sistem_confirm_count">{{ App\Models\AppHelper::akses_sistem_confirm_count() }}</span>
                    @endif
                </a>
            </li>
        @endif

        @if (auth()->user()->hasDepartment('ITD'))
            @php $routeName = 'website.incident_report.' . $link; @endphp
            @if (Route::has($routeName))
                <li class="menu-item {{ Route::is($routeName) ? 'active' : '' }}">
                    <a href="{{ route($routeName) }}" class="menu-link">
                        <div data-i18n="Incident Report">{{ $text }} Incident Report</div>
                        @if ($link == 'manager_approval' && App\Models\AppHelper::incident_report_mgr_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="incident_report_mgr_count">{{ App\Models\AppHelper::incident_report_mgr_count() }}</span>
                        @endif

                        @if ($link == 'list' && App\Models\AppHelper::incident_report_confirm_count() > 0)
                            &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="incident_report_confirm_count">{{ App\Models\AppHelper::incident_report_confirm_count() }}</span>
                        @endif
                    </a>
                </li>
            @endif
        @endif

        @php $routeName = 'website.izin.' . $link; @endphp
        @if (Route::has($routeName))
            <li class="menu-item {{ Route::is($routeName) ? 'active' : '' }}">
                <a href="{{ route($routeName) }}" class="menu-link">
                    <div data-i18n="Izin Memasuki Area Level 3">{{ $text }} Izin Memasuki Area Level 3</div>
                    @if ($link == 'manager_approval' && App\Models\AppHelper::izin_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="izin_mgr_count">{{ App\Models\AppHelper::izin_mgr_count() }}</span>
                    @endif

                    @if ($link == 'it_approval' && App\Models\AppHelper::izin_it_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="izin_it_count">{{ App\Models\AppHelper::izin_it_count() }}</span>
                    @endif

                    @if ($link == 'it_mgr_approval' && App\Models\AppHelper::izin_it_mgr_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="izin_it_mgr_count">{{ App\Models\AppHelper::izin_it_mgr_count() }}</span>
                    @endif

                    @if ($link == 'execution' && App\Models\AppHelper::izin_execution_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="izin_execution_count">{{ App\Models\AppHelper::izin_execution_count() }}</span>
                    @endif

                    @if ($link == 'list' && App\Models\AppHelper::izin_confirm_count() > 0)
                        &nbsp&nbsp<span class="badge bg-danger rounded-pill" id="izin_confirm_count">{{ App\Models\AppHelper::izin_confirm_count() }}</span>
                    @endif
                </a>
            </li>
        @endif
    @endif
</ul>
