@extends('website.layouts.main', ['title' => 'Dashboard'])

@section('content')
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="#">Dashboard</a></li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="col-md-12">
            <div class="row ">
                @can('can_master')
                    <div class="card">
                        <div class="card-body table table-responsive">
                            <h5 class="card-title">Form Queue</h5>
                            <table class="display " width="100%" id="app_table">
                                <thead>
                                    <tr>
                                        <th style="max-width: 100px;">Created at</th>
                                        <th style="max-width: 100px;">No Reg</th>
                                        <th>Creator</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                @endcan
                {{-- @include('website.pages.dashboard') --}}
                @if (auth()->check() &&
                        auth()->user()->can('can_create_form'))
                    <div class="card">
                        <div id="piechart"></div>
                    </div>
                @endif
                @if (auth()->check() &&
                        (auth()->user()->can('can_master') ||
                            auth()->user()->can('can_approve_it') ||
                            auth()->user()->can('can_approve_it_mgr')))
                    <div class="card">
                        <div id="chart"></div>
                    </div>
                @endif


            </div>
        </div>
    </section>
@endsection

@push('styles')
    <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#app_table').DataTable({
                'lengthChange': true,
                'processing': true,
                'serverSide': false,
                'orderable': true,
                ajax: {
                    url: "{{ route('website.home_ajax') }}",
                },
                columns: [
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'no_reg',
                        name: 'no_reg',
                    },
                    {
                        data: 'created_by',
                        name: 'created_by',
                    },
                    {
                        data: 'created_dept',
                        name: 'created_dept',
                    },
                    {
                        data: 'final_status',
                        name: 'final_status',
                        render: function(data, type, row, meta) {
                            if (data == 'created') {
                                return `<span class="badge bg-warning" style="font-size: 15px;">Waiting Manager Approve</span>`;
                            } else if (data == 'Manager Approve') {
                                return `<span class="badge bg-warning" style="font-size: 15px;">Waiting ITD Approve</span>`;
                            } else if (data == 'IT Approve') {
                                return `<span class="badge bg-warning" style="font-size: 15px;">Waiting ITD MGR Approve</span>`;
                            } else if (data == 'IT MGR Approve') {
                                return `<span class="badge bg-warning" style="font-size: 15px;">Waiting Execution</span>`;
                            } else if (data == 'Finished') {
                                return `<span class="badge bg-success" style="font-size: 15px;">Finished</span>`;
                            } else {
                                return `<span class="badge bg-danger" style="font-size: 15px;">${data}</span>`;
                            }
                        }
                    },
                ],
                "order": [0, 'desc'],
            });
        });
    </script>



    <script>
        Highcharts.chart('piechart', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Total Created Form',
                align: 'left'
            },
            tooltip: {
                pointFormat: 'Total: <b>{point.y} Form</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                name: 'Form',
                colorByPoint: true,
                data: [{
                    name: 'Finished',
                    color: '#47c363',
                    y: {{ $total_form_finished }},
                    sliced: true,
                    selected: true
                }, {
                    name: 'Rejected',
                    color: '#fc544b',
                    y: {{ $total_form_rejected }}
                }, {
                    name: 'Waiting Manager',
                    color: '#ffc107',
                    y: {{ $total_form_mgr }}
                }, {
                    name: 'Waiting ITD',
                    color: '#ffc61c',
                    y: {{ $total_form_it }}
                }, {
                    name: 'Waiting ITD Manager',
                    color: '#ffca2d',
                    y: {{ $total_form_it_mgr }}
                }, {
                    name: 'Waiting Execution',
                    color: '#fd7e14',
                    y: {{ $total_form_execution }}
                }]
            }]
        });
    </script>

    <script>
        Highcharts.chart('chart', {

            chart: {
                type: 'column'
            },

            title: {
                text: 'Total Form',
                align: 'left'
            },

            xAxis: {
                categories: ['Account', 'Folder Access', 'New Folder', 'Software', 'Hardware', 'VPN', 'Project',
                    'Fitur'
                ]
            },

            yAxis: {
                allowDecimals: false,
                min: 0,
                title: {
                    text: 'Total Forms'
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
                data: [{{ $account_finished }}, {{ $folderaccess_finished }}, {{ $newfolder_finished }},
                    {{ $software_finished }}, {{ $hardware_finished }}, {{ $vpn_finished }},
                    {{ $project_finished }}, {{ $fitur_finished }}
                ],
            }, {
                name: 'Rejected',
                color: '#fc544b',
                data: [{{ $account_rejected }}, {{ $folderaccess_rejected }}, {{ $newfolder_rejected }},
                    {{ $software_rejected }}, {{ $hardware_rejected }}, {{ $vpn_rejected }},
                    {{ $project_rejected }}, {{ $fitur_rejected }}
                ],
            }]
        });
    </script>
@endpush
