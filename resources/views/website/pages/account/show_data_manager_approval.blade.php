@extends('website.layouts.main', ['title' => 'Manager History Account'])

@section('content')
    <div class="pagetitle">
        <h4>Account Registration/Change/Deletion Form (FRM-ITD-S13-001-00)</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Manager History</a></li>
                <li class="breadcrumb-item active"><a href="#">Form Account</a></li>
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
                                <th>Budget Type</th>
                                <th>Request Type</th>
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
                        <td>${ d.is_email == 1 ? '<i>Will be Informed Later after approved</i>' : 'User did not Request'}</td>
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
                        url: "{{ route('website.account.show_data_manager_approval_ajax') }}",
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
