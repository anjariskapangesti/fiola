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
    {{-- <div class="row">
        @if(Session::get('info'))
        <div class="alert alert-info">
          {{ Session::get('info') }}
        </div>
        @endif
    </div>

    <div class="card-body p-3 table table-responsive">
        <table class="display" width="100%" id="app_table">
            <thead>
                <tr>
                    <th>Fullname</th>
                    <th>Budget Type</th>
                    <th>Request Type</th>
                    <th>Status</th>
                </tr>
            </thead>
        </table>
    </div> --}}
    
    <section class="section">
        <div class="col-md-12">
            <div class="row ">
                {{-- @include('website.pages.dashboard') --}}
                @if (auth()->check() && (auth()->user()->can('can_create_form')))
                <div class="">
                    <div id="piechart"></div>
                </div>
                @endif
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
{{-- <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
        <script>
            function format(d) {
                // `d` is the original data object for the row
                return (
                    `
                <table class="table table-sm">

                    <tr>
                        <td width="30%">NPK / Full Name</td>
                        <td>${d.npk} / ${d.fullname} </td>
                    </tr>
                    <tr>
                        <td>Dept.</td>
                        <td>${d.department} </td>
                    </tr>
                    <tr>
                        <td>Company</td>
                        <td>${d.company} </td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td>${d.phone} </td>
                    </tr>
                    <tr>
                        <td>Expired Date</td>
                        <td>${d.expired_date ?? '-'} </td>
                    </tr>
                    <tr>
                        <td>Login Username</td>
                        <td>aiia\\${d.ad_name}</td>
                    </tr>
                    <tr>
                        <td>Email Address</td>
                        <td>${d.email_address == null ? '<i>Will be Informed Later after approved</i>' : d.email_address}</td>
                    </tr>
                    <tr>
                        <td>Manager Note</td>
                        <td>${d.manager_note}</td>
                    </tr>
                    <tr>
                        <td>ITD Note</td>
                        <td>${d.it_note}</td>
                    </tr>  
                    <tr>
                        <td>ITD Manager Note</td>
                        <td>${d.it_mgr_note}</td>
                    </tr>
                    <tr>
                        <td>Note</td>
                        <td>${d.finish_note}</td>
                    </tr>
                    <tfoot>
                    <tr>
                        <th>Created by</th>
                        <th>${d.user_name}</th>
                    </tr>
                    <tr>
                        <th>Purpose</th>
                        <th>${d.purpose}</th>
                    </tr>
                </tfoot>
                </table>
                `
                );
            }

            $(document).ready(function() {
                var table = $('#app_table').DataTable({
                    "lengthChange": false,
                    'processing': false,
                    'serverSide': false,
                    'searching': false,
                    'info': false,
                    'paging': false,
                    ajax: {
                        url: "{{ route('website.account.show_data_form_ajax') }}",
                    },
                    columns: [
                        {
                            data: 'fullname',
                            name: 'fullname',
                        },
                        {
                            data: 'budget_type',
                            name: 'budget_type',
                            render: function(data, type, row, meta) {
                                if (data == 'budget') {
                                    return `<span class="badge bg-success">Budget</span>`;
                                } else {
                                    return `<span class="badge bg-danger">UN-budget</span>`;
                                }
                            }
                        },
                        {
                            data: 'form_type',
                            name: 'form_type'
                        },
                        {
                            data: 'final_status',
                            name: 'final_status'
                        },
                    ],
                });

                $('#app_table tbody').on('click', 'td.dt-control', function() {
                    var tr = $(this).closest('tr');
                    var row = table.row(tr);

                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        row.child(format(row.data())).show();
                        tr.addClass('shown');
                    }
                });

                $('#reject_reason').on('keyup', function() {
                    if ($(this).val() != "")
                        $('#btn-reject').removeAttr('disabled');
                    else
                        $('#btn-reject').attr('disabled', 'disabled');
                });

                $('#btn-approve').on('clcik', function() {

                });

            });
        </script> --}}
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

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
        },  {
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
        },  {
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
        align: 'center'
    },
    
    xAxis: {
        categories: ['Account', 'Folder Access', 'New Folder', 'Software']
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
        data: [{{ $account_finished }}, {{ $folderaccess_finished }}, {{ $newfolder_finished }}, {{ $software_finished }}],
    }, {
        name: 'Rejected',
        color: '#fc544b',
        data: [{{ $account_rejected }}, {{ $folderaccess_rejected }}, {{ $newfolder_rejected }}, {{ $software_rejected }}],
    }]
    });

</script>
@endpush
