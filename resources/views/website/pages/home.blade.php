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
        <div class="col-md-12">
            <div class="row ">
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

                @can('can_approve_it')
                    <div class="col-xl-3 col-lg-6">
                        <div class="card l-bg-blue-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"></div>
                                <div class="mb-1">
                                    <h5 class="card-title mb-0" style="color: white">Form Account Waiting Approve ITD Dept.</h5>
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
                                <div class="card-icon card-icon-large"></div>
                                <div class="mb-1">
                                    <h5 class="card-title mb-0" style="color: white">Form Account Waiting Approve ITD Manager
                                    </h5>
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
                                <div class="card-icon card-icon-large"></div>
                                <div class="mb-1">
                                    <h5 class="card-title mb-0" style="color: white">Form Account Waiting Execution ITD</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            {{ $account_execution_count }}
                                        </h2>
                                    </div>
                                    <div class="text-left">
                                        <a href="{{ route('website.account.show_execution') }}" class="small-box-footer"
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
                                        <a href="{{ route('website.folder-access.show_manager_approval') }}"
                                            class="small-box-footer" style="color: #f6f9ff">More
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
                                <div class="card-icon card-icon-large"></div>
                                <div class="mb-1">
                                    <h5 class="card-title mb-0" style="color: white">Form Folder Access Waiting Approve ITD
                                        Dept.</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            {{ $folderaccess_it_count }}
                                        </h2>
                                    </div>
                                    <div class="text-left">
                                        <a href="{{ route('website.folder-access.show_it_approval') }}"
                                            class="small-box-footer" style="color: #f6f9ff">More
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
                                <div class="card-icon card-icon-large"></div>
                                <div class="mb-1">
                                    <h5 class="card-title mb-0" style="color: white">Form Folder Access Waiting Approve ITD
                                        Manager</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            {{ $folderaccess_it_mgr_count }}
                                        </h2>
                                    </div>
                                    <div class="text-left">
                                        <a href="{{ route('website.folder-access.show_it_mgr_approval') }}"
                                            class="small-box-footer" style="color: #f6f9ff">More
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
                                <div class="card-icon card-icon-large"></div>
                                <div class="mb-1">
                                    <h5 class="card-title mb-0" style="color: white">Form Folder Access Waiting Execution ITD
                                    </h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            {{ $folderaccess_execution_count }}
                                        </h2>
                                    </div>
                                    <div class="text-left">
                                        <a href="{{ route('website.folder-access.show_execution') }}"
                                            class="small-box-footer" style="color: #f6f9ff">More
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
                                        <a href="{{ route('website.new-folder.show_manager_approval') }}"
                                            class="small-box-footer" style="color: #f6f9ff">More
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
                                <div class="card-icon card-icon-large"></div>
                                <div class="mb-1">
                                    <h5 class="card-title mb-0" style="color: white">Form New Folder Waiting Approve ITD
                                        Dept.</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            {{ $newfolder_it_count }}
                                        </h2>
                                    </div>
                                    <div class="text-left">
                                        <a href="{{ route('website.new-folder.show_it_approval') }}" class="small-box-footer"
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
                                <div class="card-icon card-icon-large"></div>
                                <div class="mb-1">
                                    <h5 class="card-title mb-0" style="color: white">Form New Folder Waiting Approve ITD
                                        Manager</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            {{ $newfolder_it_mgr_count }}
                                        </h2>
                                    </div>
                                    <div class="text-left">
                                        <a href="{{ route('website.new-folder.show_it_mgr_approval') }}"
                                            class="small-box-footer" style="color: #f6f9ff">More
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
                                <div class="card-icon card-icon-large"></div>
                                <div class="mb-1">
                                    <h5 class="card-title mb-0" style="color: white">Form New Folder Waiting Execution ITD
                                    </h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            {{ $newfolder_execution_count }}
                                        </h2>
                                    </div>
                                    <div class="text-left">
                                        <a href="{{ route('website.new-folder.show_execution') }}" class="small-box-footer"
                                            style="color: #f6f9ff">More
                                            info
                                            <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
                @if (auth()->check() && (auth()->user()->can('can_master') || auth()->user()->can('can_approve_it') || auth()->user()->can('can_approve_it_mgr')))
                <div class="">
                    <div id="chart"></div>
                </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('styles')
{{-- <style>
    .highcharts-figure,
    .highcharts-data-table table {
        min-width: 310px;
        max-width: 800px;
        margin: 1em auto;
    }
    
    #container {
        height: 400px;
    }
    
    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #ebebeb;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }
    
    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }
    
    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }
    
    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
        padding: 0.5em;
    }
    
    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }
    
    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }
    </style> --}}
@endpush

@push('scripts')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
Highcharts.chart('chart', {

    chart: {
        type: 'column'
    },
    
    title: {
        text: 'Total Form',
        align: 'center'
    },
    
    xAxis: {
        categories: ['Account', 'Folder Access', 'New Folder']
    },
    
    yAxis: {
        allowDecimals: false,
        min: 0,
        title: {
            text: 'Count forms'
        }
    },
    
    tooltip: {
        format: '<b>{key}</b><br/>{series.name}: {y}<br/>' +
            'Total: {point.stackTotal}'
    },
    
    plotOptions: {
        column: {
            stacking: 'normal',
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    
    series: [{
        name: 'Finished',
        color: '#47c363',
        data: [{{ $account_finished }}, {{ $folderaccess_finished }}, {{ $newfolder_finished }}],
    }, {
        name: 'Rejected',
        color: '#fc544b',
        data: [{{ $account_rejected }}, {{ $folderaccess_rejected }}, {{ $newfolder_rejected }}],
    }]
    });

</script>
@endpush
