@extends('website.layouts.main', ['title' => 'Manager History Hardware'])

@section('content')
    <div class="pagetitle">
        <h4>Device Request/Transfer/Scrap Form (FRM-ITD-S13-002-00)</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Manager History</a></li>
                <li class="breadcrumb-item active"><a href="#">Form Hardware</a></li>
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
                                <th>Fullname</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Date Approved</th>
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
                        <td width="30%">NPK / Full Name</td>
                        <td>${d.npk} / ${d.fullname} </td>
                    </tr>
                    <tr>
                        <td>Dept.</td>
                        <td>${d.department} </td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td>${d.phone} </td>
                    </tr>
                    <tr>
                        <td>Due Date</td>
                        <td>${d.due_date ?? '-'} </td>
                    </tr>  
                    <tr>
                        <td>Manager Note</td>
                        <td>${d.manager_note ?? '-'}</td>
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
                    "lengthChange": true,
                    'processing': true,
                    'serverSide': true,
                    ajax: {
                        url: "{{ route('website.hardware.show_data_manager_approval_ajax') }}",
                    },
                    columns: [{
                            className: 'dt-control',
                            orderable: false,
                            data: null,
                            defaultContent: '',
                            searchable: false,
                        },
                        {
                            data: 'fullname',
                            name: 'fullname',
                        },
                        {
                            data: 'category',
                            name: 'category',
                            render: function(data, type, row, meta) {
                                if (data == 'request') {
                                    return `<span class="badge bg-success">Request</span>`;
                                } else {
                                    return `<span class="badge bg-primary">Change</span>`;
                                }
                            }
                        },
                        {
                            data: 'type',
                            name: 'type'
                        },
                        {
                            data: 'manager_approval_date',
                            name: 'manager_approval_date'
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
