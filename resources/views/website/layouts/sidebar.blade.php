<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" href="/">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Forms</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('website.account.create') }}">
              <i class="bi bi-circle"></i><span>Form Account</span>
            </a>
          </li>
          <li>
            <a href="{{ route('website.folder-access.create') }}">
              <i class="bi bi-circle"></i><span>Form Folder Access</span>
            </a>
          </li>
          <li>
            <a href="forms-editors.html">
              <i class="bi bi-circle"></i><span>Form New Folder</span>
            </a>
          </li>
          <li>
            <a href="forms-validation.html">
              <i class="bi bi-circle"></i><span>Form S/W Installation</span>
            </a>
          </li>
          <li>
            <a href="forms-validation.html">
              <i class="bi bi-circle"></i><span>Form H/W Installation</span>
            </a>
          </li>
          <li>
            <a href="forms-validation.html">
              <i class="bi bi-circle"></i><span>Form VPN</span>
            </a>
          </li>
        </ul>
      </li><!-- End Forms Nav -->

      @can('can_approve_mgr')
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Manager Approvals</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('website.account.show_manager_approval')}}">
              <i class="bi bi-circle"></i><span>Form Account</span>
            </a>
          </li>
          <li>
            <a href="{{ route('website.folder-access.show_manager_approval')}}">
              <i class="bi bi-circle"></i><span>Form Folder Access</span>
            </a>
          </li>
          <li>
            <a href="{{ route('website.account.show_data_manager_approval')}}">
              <i class="bi bi-circle"></i><span>Data Tables</span>
            </a>
          </li>
        </ul>
      </li><!-- End Tables Nav -->
      @endcan

      @can('can_approve_it')
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#app_it_nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>ITD Approvals</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="app_it_nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('website.account.show_it_approval')}}">
              <i class="bi bi-circle"></i><span>Form Account</span>
            </a>
          </li>
          <li>
            <a href="{{ route('website.account.show_data_it_approval')}}">
              <i class="bi bi-circle"></i><span>Data Tables</span>
            </a>
          </li>
        </ul>
      </li><!-- End Tables Nav -->
      @endcan

      {{-- @if(Auth::user()->hasPermissionTo('can_approve_mgr_it')) --}}
      @can('can_approve_mgr_it')
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#app_it_mgr_nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>ITD MGR Approvals</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="app_it_mgr_nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('website.account.show_mgr_it_approval')}}">
              <i class="bi bi-circle"></i><span>Form Account</span>
            </a>
          </li>
          <li>
            <a href="tables-data.html">
              <i class="bi bi-circle"></i><span>Data Tables</span>
            </a>
          </li>
        </ul>
      </li><!-- End Tables Nav -->
      @endcan
      {{-- @endif --}}



      <li class="nav-heading">Pages</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-login.html">
          <i class="bi bi-box-arrow-right"></i>
          <span>Logout</span>
        </a>
      </li><!-- End Login Page Nav -->
    </ul>

  </aside>