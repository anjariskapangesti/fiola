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
        <div class="row">
            @can('can_approve_mgr')
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <img src="{{ asset('img/aiia.jpg') }}" alt="">
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Waiting Approve Manager</h4>
                            </div>
                            <div class="card-body">
                                {{ $account_mgr_count }}
                            </div>
                        </div><a href="{{ route('website.account.show_manager_approval') }}" class="small-box-footer">More info
                            <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endcan

            @can('can_approve_it')
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <img src="{{ asset('img/aiia.jpg') }}" alt="">

                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Waiting Approve ITD Department</h4>
                            </div>
                            <div class="card-body">
                                {{ $account_it_count }}
                            </div>
                        </div><a href="{{ route('website.account.show_it_approval') }}" class="small-box-footer">More info
                            <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endcan

            @can('can_approve_it_mgr')
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <img src="{{ asset('img/aiia.jpg') }}" alt="">

                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Waiting Approve ITD Manager</h4>
                            </div>
                            <div class="card-body">
                                {{ $account_it_mgr_count }}
                            </div>
                        </div><a href="{{ route('website.account.show_it_mgr_approval') }}" class="small-box-footer">More info
                            <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endcan

            @can('can_execution')
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <img src="{{ asset('img/aiia.jpg') }}" alt="">

                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Waiting Execution</h4>
                            </div>
                            <div class="card-body">
                                {{ $account_execution_count }}
                            </div>
                        </div><a href="{{ route('website.account.show_execution_approval') }}" class="small-box-footer">More
                            info
                            <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endcan
        </div>
    </section>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
