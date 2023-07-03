@extends('website.layouts.main', ['title' => 'Dashboard'])

@section('content')
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="#">Dashboard</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->


    <section class="section">
        <div class="col-md-12 ">
            <div class="row ">
                @can('can_approve_mgr')
                    <div class="col-xl-3 col-lg-6">
                        <div class="card l-bg-cherry">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-check"></i></div>
                                <div class="mb-1">
                                    <h5 class="card-title mb-0" style="color: white">Waiting Approve Manager</h5>
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

                @can('can_approve_it')
                    <div class="col-xl-3 col-lg-6">
                        <div class="card l-bg-blue-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-check"></i></div>
                                <div class="mb-1">
                                    <h5 class="card-title mb-0" style="color: white">Waiting Approve ITD Dept.</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            {{ $account_it_count }}
                                        </h2>
                                    </div>
                                    <div class="text-left">
                                        <a href="{{ route('website.account.show_it_approval') }}" class="small-box-footer"
                                            style="color: #f6f9ff">More
                                            info
                                            <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('can_approve_it_mgr')
                    <div class="col-xl-3 col-lg-6">
                        <div class="card l-bg-green-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-check"></i></div>
                                <div class="mb-1">
                                    <h5 class="card-title mb-0" style="color: white">Waiting Approve ITD Manager</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            {{ $account_it_mgr_count }}
                                        </h2>
                                    </div>
                                    <div class="text-left">
                                        <a href="{{ route('website.account.show_it_mgr_approval') }}" class="small-box-footer"
                                            style="color: #f6f9ff">More
                                            info
                                            <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('can_execution')
                    <div class="col-xl-3 col-lg-6">
                        <div class="card l-bg-orange-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-check"></i></div>
                                <div class="mb-1">
                                    <h5 class="card-title mb-0" style="color: white">Waiting Execution</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            {{ $account_execution_count }}
                                        </h2>
                                    </div>
                                    <div class="text-left">
                                        <a href="{{ route('website.account.show_execution_approval') }}"
                                            class="small-box-footer" style="color: #f6f9ff">More
                                            info
                                            <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

            </div>
        </div>
    </section>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
