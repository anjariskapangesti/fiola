<ul class="menu-sub">
    @if (!($only_reschedule ?? false) || ($is_director_project ?? false))
        <li class="menu-item {{ Route::is('website.project.' . $link) ? 'active' : '' }}">
            <a href="{{ route('website.project.' . $link) }}" class="menu-link">
                <div data-i18n="Request Project">{{ $text }} Request Project
                </div>
                @if ($link == 'manager_approval' && App\Models\AppHelper::project_mgr_count() > 0)
                    &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                        id="project_mgr_count">{{ App\Models\AppHelper::project_mgr_count() }}</span>
                @endif

                @if ($link == 'dir_approval' && App\Models\AppHelper::project_dir_count() > 0)
                    &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                        id="project_dir_count">{{ App\Models\AppHelper::project_dir_count() }}</span>
                @endif

                @if ($link == 'list' && App\Models\AppHelper::project_confirm_count() > 0)
                    &nbsp&nbsp<span class="badge bg-danger rounded-pill"
                        id="project_confirm_count">{{ App\Models\AppHelper::project_confirm_count() }}</span>
                @endif
            </a>
        </li>
    @endif

    @if ($link == 'manager_approval' && (auth()->user()->can('approve_dir') || auth()->user()->can('approve_pres')))
        <li class="menu-item {{ Route::is('website.project.dir_approval') ? 'active' : '' }}">
            <a href="{{ route('website.project.dir_approval') }}" class="menu-link">
                <div data-i18n="Project Reschedule">Project Reschedule Approval</div>
                @if (App\Models\AppHelper::project_dir_count() > 0)
                    &nbsp&nbsp<span class="badge bg-danger rounded-pill">{{ App\Models\AppHelper::project_dir_count() }}</span>
                @endif
            </a>
        </li>
        <li class="menu-item {{ Route::is('website.project.dir_approved') ? 'active' : '' }}">
            <a href="{{ route('website.project.dir_approved') }}" class="menu-link">
                <div data-i18n="Reschedule History">Project Reschedule History</div>
            </a>
        </li>
    @endif
</ul>
