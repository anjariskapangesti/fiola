@extends('website.layouts.main', ['title' => 'Dashboard'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row gy-4">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <small class="text-muted text-uppercase">Rata-rata Keseluruhan</small>
                                        <h3 id="overallAvg" class="mb-0">—</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <small class="text-muted text-uppercase">Total Review</small>
                                        <h3 id="totalReviews" class="mb-0">—</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <small class="text-muted text-uppercase d-block mb-2">Rentang Hari</small>
                                        <div class="input-group">
                                            <input id="daysInput" type="number" min="7" max="365" value="30" class="form-control">
                                            <button id="applyFilters" class="btn btn-primary">Terapkan</button>
                                        </div>
                                        <div class="form-text">Default 30 hari terakhir</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <small class="text-muted text-uppercase d-block mb-2">Min. Tiket (Top)</small>
                                        <div class="input-group">
                                            <input id="minTicketsInput" type="number" min="1" value="5" class="form-control">
                                            <button id="refreshBtn" class="btn btn-outline-secondary">Refresh</button>
                                        </div>
                                        <div class="form-text">Digunakan untuk Top Performers</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-lg-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Tren Rata-rata Harian</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="trendChart" style="height:280px"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Rata-rata per Person</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="byPersonChart" style="height:280px"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Distribusi Skor</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="distChart" style="height:280px"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Top Performers (≥ Min Ticket)</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width:60px">#</th>
                                                        <th>Nama</th>
                                                        <th>Avg</th>
                                                        <th>Total Ticket</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="topTableBody"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            @if (auth()->check())
                <div class="col-lg-12">
                    <div class="card">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-header">FORM QUEUE</h5>
                        </div>
                        <div class="table-responsive text-nowrap" style="padding: 0 1.25rem 0 1.25rem;">
                            <table class="table table-bordered" id="app_table" width="100%">
                                <thead>
                                    <tr>
                                        <th width="50px">Tanggal</th>
                                        <th>No Registrasi</th>
                                        <th>Form Name</th>
                                        <th>Requestor</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                        <th>Option</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0"></tbody>
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
                $currentYear = date('Y');

                if ($filter_month && $filter_year) {
                    $bulanIndonesia = [
                        '00' => '',
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

                if ($filter_year == '0000') {
                    $filter_year = '';
                }
            @endphp

            @if (auth()->check() && auth()->user()->hasDepartment('ITD'))
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <center>
                                <h5 style="color: black;">
                                    <b>TOTAL FORM {{ strtoupper($bulanIndonesia) }} {{ $filter_year }} : {{ $semua }} FORM</b>
                                </h5>
                            </center>

                            <form action="{{ route('website.home') }}" method="GET" id="dateFilterForm">
                                <div class="row">
                                    <div class="col-md-2 mb-3">
                                        <label for="filter_month"><b>Filter Bulan :</b></label>
                                        <select id="filter_month" name="filter_month" class="form-control" onchange="handleMonthChange()">
                                            <option value="00">Semua</option>
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
                                            <option value="0000" disabled>Semua</option>
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
    <link rel="stylesheet" href="{{ asset('vendor/datatables/css/datatables.min.css') }}">
    <style>

    </style>
@endpush

@push('scripts')
    <script src="{{ asset('vendor/datatables/js/datatables.min.js') }}"></script>
    <script src="{{ asset('vendor/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('vendor/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('vendor/highcharts/export-data.js') }}"></script>
    <script src="{{ asset('vendor/highcharts/accessibility.js') }}"></script>

    <script>
        function handleMonthChange() {
            const monthSelect = document.getElementById('filter_month');
            const yearSelect = document.getElementById('filter_year');
            const allYearsOption = yearSelect.querySelector('option[value="0000"]');

            if (monthSelect.value === "00") {
                allYearsOption.disabled = false;
            } else {
                allYearsOption.disabled = true;
                if (yearSelect.value === "0000") {
                    yearSelect.value = "<?php echo $currentYear; ?>";
                }
            }
        }

        function checkURLParams() {
            const urlParams = new URLSearchParams(window.location.search);
            const month = urlParams.get('filter_month');
            const year = urlParams.get('filter_year');
            const monthSelect = document.getElementById('filter_month');
            const yearSelect = document.getElementById('filter_year');
            const allYearsOption = yearSelect.querySelector('option[value="0000"]');

            if (month === '00') {
                allYearsOption.disabled = false;
            } else {
                allYearsOption.disabled = true;
                if (year === '0000') {
                    yearSelect.value = "<?php echo $currentYear; ?>";
                }
            }

            if (month) {
                monthSelect.value = month;
            }
            if (year) {
                yearSelect.value = year;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            checkURLParams();
            handleMonthChange();
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

    @if (auth()->check())
        <script>
            $(document).ready(function() {
                $('#app_table').DataTable({
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
                            data: 'form_name',
                            name: 'form_name',
                        },
                        {
                            data: 'created_name',
                            name: 'created_name',
                        },
                        {
                            data: 'created_dept',
                            name: 'created_dept',
                        },
                        {
                            data: 'final_status',
                            name: 'final_status',
                            render: function(data) {
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
                        {
                            data: null,
                            render: function(data, type, row) {
                                var userCanApprove = {{ Auth::user()->can('approve_mgr') ? 'true' : 'false' }};
                                var createdBy = {{ Auth::user()->id }};
                                var itMgr = {{ Auth::user()->hasDepartment('ITD') ? 'true' : 'false' }};

                                if (userCanApprove && itMgr && data.final_status == 'IT Approve') {
                                    return `
                                        <center>
                                            <a href="/${row.form_url}/it_mgr_approval" class="btn btn-primary">
                                                <span class="mdi mdi-open-in-new"></span>
                                            </a>
                                        </center>
                                    `;
                                } else if (userCanApprove && data.final_status == 'created' && !itMgr) {
                                    return `
                                        <center>
                                            <a href="/${row.form_url}/manager_approval" class="btn btn-primary">
                                                <span class="mdi mdi-open-in-new"></span>
                                            </a>
                                        </center>
                                    `;
                                } else if (data.created_by == createdBy) {
                                    return `
                                        <center>
                                            <a href="/${row.form_url}/list" class="btn btn-info">
                                                <span class="mdi mdi-eye"></span>
                                            </a>
                                        </center>
                                    `;
                                }
                                return '';
                            }
                        }
                    ],
                    "order": [0, 'desc'],
                });
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
                    categories: ['Account', 'Folder Access', 'New Folder', 'Software', 'Hardware', 'VPN', 'Project', 'Fitur', 'Relayout', 'Network', 'Akses Sistem', 'Incident Report', 'Izin Level 3']
                },
                yAxis: {
                    allowDecimals: false,
                    min: 0,
                    title: {
                        text: 'Total Forms'
                    }
                },
                tooltip: {
                    format: '<b>{key}</b><br/>{series.name}: {y}<br/>Total: {point.stackTotal}'
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
                    data: [{{ $account_finished }}, {{ $folderaccess_finished }}, {{ $newfolder_finished }}, {{ $software_finished }}, {{ $hardware_finished }}, {{ $vpn_finished }}, {{ $project_finished }}, {{ $fitur_finished }}, {{ $relayout_finished }}, {{ $network_finished }}, {{ $akses_sistem_finished }}, {{ $incident_report_finished }}, {{ $izin_finished }}]
                }, {
                    name: 'On Progress',
                    color: '#ffc107',
                    data: [
                        {{ $account_total - $account_finished - $account_rejected }},
                        {{ $folderaccess_total - $folderaccess_finished - $folderaccess_rejected }},
                        {{ $newfolder_total - $newfolder_finished - $newfolder_rejected }},
                        {{ $software_total - $software_finished - $software_rejected }},
                        {{ $hardware_total - $hardware_finished - $hardware_rejected }},
                        {{ $vpn_total - $vpn_finished - $vpn_rejected }},
                        {{ $project_total - $project_finished - $project_rejected }},
                        {{ $fitur_total - $fitur_finished - $fitur_rejected }},
                        {{ $relayout_total - $relayout_finished - $relayout_rejected }},
                        {{ $network_total - $network_finished - $network_rejected }},
                        {{ $akses_sistem_total - $akses_sistem_finished - $akses_sistem_rejected }},
                        {{ $incident_report_total - $incident_report_finished - $incident_report_rejected }},
                        {{ $izin_total - $izin_finished - $izin_rejected }}
                    ]
                }, {
                    name: 'Rejected',
                    color: '#fc544b',
                    data: [{{ $account_rejected }}, {{ $folderaccess_rejected }}, {{ $newfolder_rejected }}, {{ $software_rejected }}, {{ $hardware_rejected }}, {{ $vpn_rejected }}, {{ $project_rejected }}, {{ $fitur_rejected }}, {{ $relayout_rejected }}, {{ $network_rejected }}, {{ $akses_sistem_rejected }}, {{ $incident_report_rejected }}, {{ $izin_rejected }}]
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
                    text: 'PERSENTASE STATUS TICKET {{ strtoupper($bulanIndonesia) }} {{ $filter_year }}',
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
                        }, {
                            name: 'PENDING',
                            y: {{ $ticket_pending }},
                            color: 'yellow',
                        }, {
                            name: 'SOLVED',
                            y: {{ $ticket_finished }},
                            sliced: true,
                            selected: true,
                            color: '#198754',
                        }, {
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
                    text: 'TOTAL TICKET {{ $filter_year }}',
                    align: 'center'
                },
                xAxis: {
                    categories: ['Januari', 'Februari', 'Maret', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
                },
                yAxis: {
                    allowDecimals: false,
                    min: 0,
                    title: {
                        text: 'Count Ticket'
                    }
                },
                tooltip: {
                    format: '<b>{key}</b><br/>{series.name}: {y}<br/>Total: {point.stackTotal}'
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
                    document.getElementById('filter_month').value = (new Date().getMonth() + 1).toString().padStart(2, '0');
                    document.getElementById('filter_year').value = new Date().getFullYear();
                }
            });
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let trendChart, byPersonChart, distChart;

        function fmt(n) {
            return Number(n ?? 0).toFixed(2);
        }

        async function loadMetrics() {
            const days = document.getElementById('daysInput').value || 30;
            const minTickets = document.getElementById('minTicketsInput').value || 5;

            const url = new URL("{{ route('website.metrics') }}", window.location.origin);
            url.searchParams.set('days', days);
            url.searchParams.set('min_tickets', minTickets);

            const res = await fetch(url);
            const data = await res.json();

            document.getElementById('overallAvg').textContent = fmt(data.overall?.average);
            document.getElementById('totalReviews').textContent = data.overall?.total_reviews ?? 0;

            const labelsTrend = (data.trend ?? []).map(x => x.d);
            const valuesTrend = (data.trend ?? []).map(x => Number(x.avg_review));

            trendChart?.destroy();
            trendChart = new Chart(document.getElementById('trendChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: labelsTrend,
                    datasets: [{
                        label: 'Rata-rata',
                        data: valuesTrend,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${fmt(ctx.parsed.y)}`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: 100
                        }
                    }
                }
            });

            const byPerson = (data.by_person ?? []).slice(0, 12);
            const labelsPerson = byPerson.map(x => x.name);
            const valuesPerson = byPerson.map(x => Number(x.avg_review));

            byPersonChart?.destroy();
            byPersonChart = new Chart(document.getElementById('byPersonChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labelsPerson,
                    datasets: [{
                        label: 'Avg',
                        data: valuesPerson
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${fmt(ctx.parsed.x)}`
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            suggestedMax: 100
                        }
                    }
                }
            });

            const distLabels = Object.keys(data.distribution ?? {});
            const distValues = Object.values(data.distribution ?? {}).map(Number);

            distChart?.destroy();
            distChart = new Chart(document.getElementById('distChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: distLabels,
                    datasets: [{
                        data: distValues
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            const tb = document.getElementById('topTableBody');
            tb.innerHTML = '';
            (data.top_performers ?? []).forEach((r, i) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${i+1}</td>
                    <td>${r.name}</td>
                    <td>${fmt(r.avg_review)}</td>
                    <td>${r.total_ticket}</td>
                `;
                tb.appendChild(tr);
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('applyFilters').addEventListener('click', loadMetrics);
            document.getElementById('refreshBtn').addEventListener('click', loadMetrics);
            loadMetrics();
        });
    </script>
@endpush