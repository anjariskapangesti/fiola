@can('can_approve_mgr')
    <div class="col-xl-3 col-lg-6">
        <div class="card l-bg-red">
            <div class="card-statistic-3 p-4">
                <div class="card-icon card-icon-large"></div>
                <div class="mb-1">
                    <h5 class="card-title mb-0" style="color: white">Form Account Waiting Approve Manager</h5>
                </div>
                <div class="row align-items-center mb-2 d-flex">
                    <div class="col-8">
                        <h2 class="d-flex align-items-center mb-0">
                            {{ $account_mgr_count }}
                        </h2>
                    </div>
                    <div class="text-left">
                        <a href="{{ route('website.account.show_manager_approval') }}" class="small-box-footer"
                            style="color: #f6f9ff">More
                            info
                            <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endcan

{{-- FOLDER ACCESS --}}
@can('can_approve_mgr')
    <div class="col-xl-3 col-lg-6">
        <div class="card l-bg-red">
            <div class="card-statistic-3 p-4">
                <div class="card-icon card-icon-large"></div>
                <div class="mb-1">
                    <h5 class="card-title mb-0" style="color: white">Form Folder Access Waiting Approve Manager
                    </h5>
                </div>
                <div class="row align-items-center mb-2 d-flex">
                    <div class="col-8">
                        <h2 class="d-flex align-items-center mb-0">
                            {{ $folderaccess_mgr_count }}
                        </h2>
                    </div>
                    <div class="text-left">
                        <a href="{{ route('website.folder-access.show_manager_approval') }}" class="small-box-footer"
                            style="color: #f6f9ff">More
                            info
                            <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endcan

@can('can_approve_mgr')
    <div class="col-xl-3 col-lg-6">
        <div class="card l-bg-red">
            <div class="card-statistic-3 p-4">
                <div class="card-icon card-icon-large"></div>
                <div class="mb-1">
                    <h5 class="card-title mb-0" style="color: white">Form New Folder Waiting Approve
                        Manager
                    </h5>
                </div>
                <div class="row align-items-center mb-2 d-flex">
                    <div class="col-8">
                        <h2 class="d-flex align-items-center mb-0">
                            {{ $newfolder_mgr_count }}
                        </h2>
                    </div>
                    <div class="text-left">
                        <a href="{{ route('website.new-folder.show_manager_approval') }}" class="small-box-footer"
                            style="color: #f6f9ff">More
                            info
                            <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endcan

