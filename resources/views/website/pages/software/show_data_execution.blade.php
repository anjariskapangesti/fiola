@extends('website.layouts.main', ['title' => 'Finished Software'])

@section('content')
    <div class="pagetitle">
        <h4>Standard Setting Change (Software Installation) Form (FRM-ITD-S13-005-00)</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Finished</a></li>
                <li class="breadcrumb-item active"><a href="#">Form Software</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body p-3 table table-responsive">

                    <table class="display" width="100%" id="app_table">
                        <thead>
                            <tr>
                                <th>Detail</th>
                                <th>App Name</th>
                                <th>Install on</th>
                                <th>Category</th>
                                <th>Final Status</th>
                                <th>Date Execution</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    @endsection

    @push('styles')
        <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet" />
    @endpush

    @push('scripts')
        <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
        <script>
            function format(d) {
                // `d` is the original data object for the row
                return (
                    `
                <table class="table table-sm">

                    <tr>
                        <td width="30%">App Name</td>
                        <td>${d.appname} </td>
                    </tr>
                    <tr>
                        <td>Install on</td>
                        <td>${d.installon} </td>
                    </tr>                    
                    <tr>
                        <td>Manager Note</td>
                        <td>${d.manager_note ?? '-'}</td>
                    </tr>
                    <tr>
                        <td>ITD Note</td>
                        <td>${d.it_note ?? '-'}</td>
                    </tr>  
                    <tr>
                        <td>ITD Manager Note</td>
                        <td>${d.it_mgr_note ?? '-'}</td>
                    </tr>
                    <tr>
                        <td>Note</td>
                        <td>${d.finish_note ?? '-'}</td>
                    </tr>
                    <tfoot>
                    <tr>
                        <th>Created by</th>
                        <th>${d.user_name}</th>
                    </tr>
                    <tr>
                        <th>Detail</th>
                        <th>${d.detail}</th>
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
                    "lengthChange": true,
                    'processing': true,
                    'serverSide': true,
                    ajax: {
                        url: "{{ route('website.software.show_data_execution_ajax') }}",
                    },
                    columns: [{
                            className: 'dt-control',
                            orderable: false,
                            data: null,
                            defaultContent: '',
                            searchable: false,
                        },
                        {
                        data: 'appname',
                        name: 'appname',
                        },
                        {
                            data: 'installon',
                            name: 'installon',
                        },

                        {
                            data: 'category',
                            name: 'category',
                            render: function(data, type, row, meta) {
                                if (data == 'software') {
                                    return `<span class="badge bg-success">Software</span>`;
                                } else {
                                    return `<span class="badge bg-danger">OS</span>`;
                                }
                            }
                        },
                        {
                            data: 'final_status',
                            name: 'final_status'
                        }, 
                        {
                            data: 'finish_date',
                            name: 'finish_date'
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
        </script>
    @endpush
