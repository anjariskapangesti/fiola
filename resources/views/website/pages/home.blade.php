@extends('website.layouts.main', ['title' => 'Dashboard'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row gy-4">
            @if (auth()->check() && auth()->user()->hasDepartment('ITD'))
                <div class="col-lg-12">
                    <div class="card">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-header">Form Queue</h5>
                        </div>
                        <div class="table-responsive text-nowrap" style="padding: 0 1.25rem 0 1.25rem;">
                            <table class="table table-bordered" id="app_table" width="100%">
                                <thead>
                                    <tr>
                                        <th width="50px">Tanggal</th>
                                        <th>No Registrasi</th>
                                        <th>Requestor</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if (auth()->check() && auth()->user()->can('apps_fiola'))
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="piechart"></div>
                        </div>
                    </div>
                </div>
            @endif

            @php
                $filter_month = request()->get('filter_month');
                $filter_year = request()->get('filter_year');

                if ($filter_month && $filter_year) {
                    $bulanIndonesia = [
                        '01' => 'Januari',
                        '02' => 'Februari',
                        '03' => 'Maret',
                        '04' => 'April',
                        '05' => 'Mei',
                        '06' => 'Juni',
                        '07' => 'Juli',
                        '08' => 'Agustus',
                        '09' => 'September',
                        '10' => 'Oktober',
                        '11' => 'November',
                        '12' => 'Desember',
                    ];

                    $bulanIndonesia = $bulanIndonesia[$filter_month];
                } else {
                    $bulanInggris = now()->format('F');
                    $bulanIndonesia = [
                        'January' => 'Januari',
                        'February' => 'Februari',
                        'March' => 'Maret',
                        'April' => 'April',
                        'May' => 'Mei',
                        'June' => 'Juni',
                        'July' => 'Juli',
                        'August' => 'Agustus',
                        'September' => 'September',
                        'October' => 'Oktober',
                        'November' => 'November',
                        'December' => 'Desember',
                    ];
                    $filter_year = now()->format('Y');
                    $bulanIndonesia = $bulanIndonesia[$bulanInggris];
                }
            @endphp

            @if (auth()->check() && auth()->user()->hasDepartment('ITD'))
                @php
                    $startOfMonth = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
                    $endOfMonth = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');
                @endphp
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <center>
                                <h5 style="color: black;"><b>TOTAL FORM {{ strtoupper($bulanIndonesia) }}
                                        {{ $filter_year }}</b></h5>
                            </center>
                            <form action="{{ route('website.home') }}" method="GET" id="dateFilterForm">
                                <div class="row">
                                    <div class="col-md-2 mb-3">
                                        <label for="filter_month"><b>Filter Bulan :</b></label>
                                        <select id="filter_month" name="filter_month" class="form-control">
                                            <option value="01">Januari</option>
                                            <option value="02">Februari</option>
                                            <option value="03">Maret</option>
                                            <option value="04">April</option>
                                            <option value="05">Mei</option>
                                            <option value="06">Juni</option>
                                            <option value="07">Juli</option>
                                            <option value="08">Agustus</option>
                                            <option value="09">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="filter_year"><b>Filter Tahun :</b></label>
                                        <select id="filter_year" name="filter_year" class="form-control">
                                            <!-- Generate options for years from 2020 to current year -->
                                            <?php
                                            $currentYear = date('Y');
                                            for ($year = 2023; $year <= $currentYear; $year++) {
                                                echo '<option value="' . $year . '">' . $year . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3" style="padding-top: 24px;">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </form>
                            <div id="chart" class="mt-3"></div>
                        </div>
                    </div>
                </div>
            @endif

            @if (auth()->check() && auth()->user()->hasDepartment('ITD'))
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body mt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="pie_ticket"></div>
                                </div>
                                <div class="col-md-6">
                                    <div id="column_ticket"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
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
                columns: [{
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
                            } else if (data == 'On Progress') {
                                return `<span class="badge bg-info" style="font-size: 15px;">On Progress</span>`;
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
                text: 'PERSENTASE STATUS FORM BY {{ strtoupper(Auth::user()->name) }}',
                align: 'center'
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
                text: '',
                align: 'center'
            },

            xAxis: {
                categories: ['Account', 'Folder Access', 'New Folder', 'Software', 'Hardware', 'VPN', 'Project',
                    'Fitur', 'Relayout', 'Network',
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
                    {{ $project_finished }}, {{ $fitur_finished }}, {{ $relayout_finished }},
                    {{ $network_finished }}
                ],
            }, {
                name: 'Rejected',
                color: '#fc544b',
                data: [{{ $account_rejected }}, {{ $folderaccess_rejected }}, {{ $newfolder_rejected }},
                    {{ $software_rejected }}, {{ $hardware_rejected }}, {{ $vpn_rejected }},
                    {{ $project_rejected }}, {{ $fitur_rejected }}, {{ $relayout_rejected }},
                    {{ $network_rejected }}
                ],
            }]
        });
    </script>

    <script>
        Highcharts.chart('pie_ticket', {
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                text: 'PERSENTASE STATUS TICKET BULAN {{ strtoupper($bulanIndonesia) }} {{ $filter_year }}',
                align: 'center'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 35,
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}'
                    }
                },
            },
            series: [{
                type: 'pie',
                name: 'Share',
                data: [{
                        name: 'OPEN',
                        y: {{ $ticket_open }},
                        color: 'grey',
                    }, {
                        name: 'ON PROGRESS',
                        y: {{ $ticket_on_progress }},
                        color: '#3380FF',
                    },
                    {
                        name: 'PENDING',
                        y: {{ $ticket_pending }},
                        color: 'yellow',
                    },
                    {
                        name: 'SOLVED',
                        y: {{ $ticket_finished }},
                        sliced: true,
                        selected: true,
                        color: '#198754',
                    },
                    {
                        name: 'REJECTED',
                        y: {{ $ticket_rejected }},
                        color: 'red',
                    },
                ]
            }]
        });
    </script>

    <script>
        Highcharts.chart('column_ticket', {

            chart: {
                type: 'column'
            },

            title: {
                text: 'TOTAL TICKET TAHUN {{ $filter_year }}',
                align: 'center'
            },

            xAxis: {
                categories: ['Januari', 'Februari', 'Maret', 'April', 'May', 'June', 'July', 'August', 'September',
                    'October', 'November', 'December'
                ]
            },

            yAxis: {
                allowDecimals: false,
                min: 0,
                title: {
                    text: 'Count WOS'
                }
            },

            tooltip: {
                format: '<b>{key}</b><br/>{series.name}: {y}<br/>' +
                    'Total: {point.stackTotal}'
            },

            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            },

            series: [{
                name: 'Total Ticket',
                data: [
                    {{ $ticket_total[1] }},
                    {{ $ticket_total[2] }},
                    {{ $ticket_total[3] }},
                    {{ $ticket_total[4] }},
                    {{ $ticket_total[5] }},
                    {{ $ticket_total[6] }},
                    {{ $ticket_total[7] }},
                    {{ $ticket_total[8] }},
                    {{ $ticket_total[9] }},
                    {{ $ticket_total[10] }},
                    {{ $ticket_total[11] }},
                    {{ $ticket_total[12] }},
                ],
                stack: 'Plan',
                color: '#012970'
            }, {
                name: 'Total Solved',
                data: [
                    {{ $ticket_solved[1] }},
                    {{ $ticket_solved[2] }},
                    {{ $ticket_solved[3] }},
                    {{ $ticket_solved[4] }},
                    {{ $ticket_solved[5] }},
                    {{ $ticket_solved[6] }},
                    {{ $ticket_solved[7] }},
                    {{ $ticket_solved[8] }},
                    {{ $ticket_solved[9] }},
                    {{ $ticket_solved[10] }},
                    {{ $ticket_solved[11] }},
                    {{ $ticket_solved[12] }},
                ],
                stack: 'Progress',
                color: 'green'
            }]
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var urlParams = new URLSearchParams(window.location.search);
            var filter_month = urlParams.get('filter_month');
            var filter_year = urlParams.get('filter_year');

            $('#filter_year').val(filter_year);
            $('#filter_month').val(filter_month);

            if (!filter_month && !filter_year) {
                document.getElementById('filter_month').value = (new Date().getMonth() + 1).toString().padStart(2,
                    '0');
                document.getElementById('filter_year').value = new Date().getFullYear();
            }
        });
    </script>
@endpush
