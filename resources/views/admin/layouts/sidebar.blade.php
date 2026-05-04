<!-- Sidebar -->
<aside class="layout-menu menu-vertical menu bg-menu-theme">

    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold ms-2">FIOLA</span>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <!-- Dashboard -->
        <li class="menu-item {{ request()->routeIs('dashboard*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div>Dashboard</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="menu-link">
                        <div>Dashboard Utama</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div>Dashboard Timeline</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- APPS & PAGES -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Apps & Pages</span>
        </li>

        <!-- ✅ AKTIF PROJECT -->
        <li class="menu-item {{ request()->routeIs('website.approved_project.*') ? 'active' : '' }}">
            <a href="{{ route('website.approved_project.index') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-check-circle-outline"></i>
                <div>Aktif Project</div>
            </a>
        </li>

        <!-- Tickets -->
        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-ticket-outline"></i>
                <div>Tickets</div>
            </a>
        </li>

        <!-- Reschedule -->
        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-calendar-clock-outline"></i>
                <div>Reschedule Requests</div>
            </a>
        </li>

        <!-- Forms -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-form-select"></i>
                <div>Forms</div>
            </a>
        </li>

        <!-- Track -->
        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-magnify"></i>
                <div>Track Forms</div>
            </a>
        </li>

        <!-- Manager Approval -->
        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-timer-sand"></i>
                <div>Manager Approval</div>
            </a>
        </li>

        <!-- Manager History -->
        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-history"></i>
                <div>Manager History</div>
            </a>
        </li>

    </ul>
</aside>
<!-- / Sidebar -->