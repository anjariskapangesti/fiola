<ul class="menu-sub">
    <li class="menu-item {{ Route::is('website.account.' . $link) ? 'active' : '' }}">
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
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.folder-access.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.folder-access.' . $link) }}" class="menu-link">
            <div data-i18n="Folder Access">{{ $text }} Folder Access</div>
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.new-folder.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.new-folder.' . $link) }}" class="menu-link">
            <div data-i18n="New Folder">{{ $text }} New Folder</div>
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.software.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.software.' . $link) }}" class="menu-link">
            <div data-i18n="Software Installation">{{ $text }} Software Installation
            </div>
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.hardware.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.hardware.' . $link) }}" class="menu-link">
            <div data-i18n="Request Device">{{ $text }} Request Device
            </div>
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.vpn.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.vpn.' . $link) }}" class="menu-link">
            <div data-i18n="VPN">{{ $text }} VPN
            </div>
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.project.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.project.' . $link) }}" class="menu-link">
            <div data-i18n="Request Project">{{ $text }} Request Project
            </div>
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.fitur.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.fitur.' . $link) }}" class="menu-link">
            <div data-i18n="Request Fitur">{{ $text }} Request Fitur
            </div>
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.relayout.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.relayout.' . $link) }}" class="menu-link">
            <div data-i18n="Relayout">{{ $text }} Relayout
            </div>
        </a>
    </li>

    <li class="menu-item {{ Route::is('website.network.' . $link) ? 'active' : '' }}">
        <a href="{{ route('website.network.' . $link) }}" class="menu-link">
            <div data-i18n="Network Change">{{ $text }} Network Change
            </div>
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
