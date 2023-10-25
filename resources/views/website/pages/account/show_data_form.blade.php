@extends('website.layouts.main', ['title' => 'Track Forms Account'])

@section('content')
    <div class="pagetitle">
        <h4>Account Registration/Change/Deletion Form (FRM-ITD-S13-001-00)</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Track Forms</a></li>
                <li class="breadcrumb-item active"><a href="#">Form Account</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="row">
        @if (Session::get('info'))
            <div class="alert alert-info">
                {{ Session::get('info') }}
            </div>
        @endif
    </div>
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
                                <th>Status</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="confirmModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure want to confirm this request?
                        <input type="text" readonly class="form-control-plaintext" id="fullname_form_account">
                        <input type="hidden" id="id_form_account">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="btn-approve">Yes, Confirm!</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure want to delete this request?
                        <input type="text" readonly class="form-control-plaintext" id="fullname_delete">
                        <input type="hidden" id="id_delete">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="btn-delete">Yes, Delete!</button>
                    </div>
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
            $(document).ready(function() {
                @if (session()->has('success'))
                    toastr['success']("{{ Session('success') }}")
                @endif
            })

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
                <td>Department</td>
                <td>${d.department} </td>
            </tr>
            <tr>
                <td>Company</td>
                <td>${d.company ?? 'PT. Aisin Indonesia Automotive'} </td>
            </tr>
            <tr>
                <td>Phone Number</td>
                <td>${d.phone} </td>
            </tr>
            <tr>
                <td>Login Username</td>
                <td>${d.ad_name}@aiia.co.id</td>
            </tr>
            <tr>
                <td>User Lisensi Microsoft Office</td>
                <td>
                    ${d.is_email === 0 ? 'Tidak butuh lisensi' : (d.is_email === 1 ? (d.email_address == null ? 'Akan diinformasikan nanti setelah disetujui' : d.ad_name + '@aisinaiia.onmicrosoft.com') : '')}
                </td>
            </tr>
            <tr>
                <td>Email Address</td>
                <td>
                    ${d.is_email === 0 ? 'Tidak butuh email' : (d.is_email === 1 ? (d.email_address == null ? 'Akan diinformasikan nanti setelah disetujui' : d.email_address) : '')}
                </td>
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
                    'lengthChange': true,
                    'processing': true,
                    'serverSide': true,
                    ajax: {
                        url: "{{ route('website.account.show_data_form_ajax') }}",
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
                        },
                        {
                            data: 'form_type',
                            name: 'form_type'
                        },
                        {
                            data: 'final_status',
                            name: 'final_status',
                            render: function(data, type, row, meta) {
                                if (data == 'created') {
                                    return `<span class="badge bg-warning">Waiting Manager Approve</span>`;
                                } else if (data == 'Manager Approve') {
                                    return `<span class="badge bg-warning">Waiting ITD Approve</span>`;
                                } else if (data == 'IT Approve') {
                                    return `<span class="badge bg-warning">Waiting ITD MGR Approve</span>`;
                                } else if (data == 'IT MGR Approve') {
                                    return `<span class="badge bg-warning">Waiting Execution</span>`;
                                } else if (data == 'Delay') {
                                    return `<span class="badge bg-primary">Progress Create by ITD</span>`;
                                } else if (data == 'Finished') {
                                    return `<span class="badge bg-success">Finished</span>`;
                                } else {
                                    return `<span class="badge bg-danger">${data}</span>`;
                                }
                            }
                        },
                        {
                            orderable: false,
                            searchable: false,
                            data: null,
                            render: function(data, type, row, meta) {
                                if (data.is_confirm == '0') {
                                    return `<button class="btn btn-success btn-sm btn-table-approve" data-bs-toggle="modal" data-bs-target="#confirmModal" data-id="${data.id}" data-fullname="${data.fullname}" data-ad_name="${data.ad_name}">Confirm</button>
                                `;
                                } else if (data.is_confirm == '1') {
                                    return `Confirmed`
                                } else if (data.final_status == 'created') {
                                    return `<a href="/account/edit/${row.id}" class="btn btn-primary btn-sm">
                                                Edit
                                            </a>
                                            <button class="btn btn-danger btn-sm btn-table-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="${data.id}" data-fullname="${data.fullname}" data-ad_name="${data.ad_name}">Delete</button>`;
                                } else {
                                    return `Not yet`;
                                }
                            }
                        },
                    ],
                });

                $('#btn-approve').on('click', function() {
                    let id_form_account = $('#id_form_account').val();
                    $.ajax({
                        url: "{{ route('website.account.approve_form') }}",
                        type: "POST",
                        data: {
                            id: id_form_account,
                            type: 'ok',
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function(response) {

                            toastr['success'](response)
                            table.ajax.reload();
                            $('#confirmModal').modal('hide')
                        },
                        error: function(xhr, status, error) {
                            alert(error);
                        }
                    });
                });

                $('#btn-delete').on('click', function() {
                    let id_delete = $('#id_delete').val();
                    $.ajax({
                        url: "{{ route('website.account.delete_form') }}",
                        type: "POST",
                        data: {
                            id: id_delete,
                            type: 'ok',
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function(response) {

                            toastr['success'](response)
                            table.ajax.reload();
                            $('#deleteModal').modal('hide')
                        },
                        error: function(xhr, status, error) {
                            alert(error);
                        }
                    });
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

                $('#app_table').on('click', '.btn-table-approve', function() {
                    var id_form_account = $(this).data('id');
                    var fullname_form_account = $(this).data('fullname');
                    var ad_name = $(this).data('ad_name');

                    $('#id_form_account').val(id_form_account)
                    $('#fullname_form_account').val(fullname_form_account)
                    $('#ad_name').val(ad_name)
                })

                $('#app_table').on('click', '.btn-table-delete', function() {
                    var id_delete = $(this).data('id');
                    var fullname_delete = $(this).data('fullname');
                    var ad_name = $(this).data('ad_name');

                    $('#id_delete').val(id_delete)
                    $('#fullname_delete').val(fullname_delete)
                    $('#ad_name').val(ad_name)
                })

            });
        </script>
    @endpush
