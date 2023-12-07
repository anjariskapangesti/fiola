@extends('website.layouts.main', ['title' => 'ITD Approval Folder Access'])

@section('content')
    <div class="pagetitle">
        <h4>Change Access of Folder Share Application (FRM-ITD-S13-009-00)</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">ITD Approval</a></li>
                <li class="breadcrumb-item active"><a href="#">Form Folder Access</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body p-3 table-responsive">
                    <table class="table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>Detail</th>
                                <th>Email</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody></tbody>

                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="confirmModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Approve Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure want to approve this request?
                        <input type="text" readonly class="form-control-plaintext" id="username_folder_access">
                        <input type="hidden" id="id_folder_access">
                        <textarea class="form-control" id="note" placeholder="add note if there are additional"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="btn-approve">Yes, Approve!</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Confirmation Modal -->
        <!-- Confirmation Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Please share the reason why you're rejecting<br /><br />
                        <textarea class="form-control" id="reject_reason"></textarea>
                        <input type="hidden" id="id_folder_access_reject">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="btn-reject" class="btn btn-danger" disabled
                            id="btn-reject">Reject!</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Confirmation Modal -->
    </section>
@endsection
@push('styles')
    {{-- <link href="{{ asset('vendor/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" /> --}}
    {{-- <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        tbody tr td.dt-control {
            background: url("{{ asset('img/details_open.png') }}") no-repeat center center;
            cursor: pointer;
        }

        tr.details td.dt-control {
            background: url("{{ asset('img/details_close.png') }}") no-repeat center center;
        }
    </style> --}}
    <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet" />
@endpush
@push('scripts')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script lang="text/javascript">
        $(function() {


            var table = $('.table').DataTable({
                'bLengthChange': true,
                processing: true,
                ordering: true,
                serverSide: true,
                ajax: {
                    'url': "{{ route('website.folder-access.show_it_approval_ajax') }}",
                },
                columns: [{
                        data: null,
                        className: 'dt-control',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return ''
                        },
                    },
                    {
                        data: 'username',
                        name: 'username',
                    },
                    {
                        orderable: false,
                        searchable: false,
                        data: null,
                        render: function(data, type, row, meta) {
                            return `
                            <button class="btn btn-success btn-sm btn-table-approve" data-bs-toggle="modal" data-bs-target="#confirmModal" data-id="${data.id}" data-username="${data.username}">Approve</button>
                            <button class="btn btn-danger btn-sm btn-table-reject" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="${data.id}" data-username="${data.username}">Reject</button>`;
                        }
                    },
                ]

            })

            var detailsRow = [];

            $('.table tbody').on('click', 'tr td.dt-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var idx = $.inArray(tr.attr('id'), detailsRow);

                if (row.child.isShown()) {
                    tr.removeClass('details')
                    row.child.hide()
                    detailsRow.splice(idx, 1)
                } else {
                    tr.addClass('details')
                    row.child(format(row.data())).show()
                    if (idx === -1) {
                        detailsRow.push(tr.attr('id'))
                    }
                }
            })

            table.on('draw', function() {
                $.each(detailsRow, function(i, id) {
                    $('#' + id + ' td.dt-control').trigger('click')
                })
            })

            function format(d) {
                var html = `
                    <table class = "table table-sms">
                                                <tr class = "bg-light">
                                                <td> Main Path </td>
                                                <td> Folder </td>
                                                <td> Subfolder </td>
                                                <td> Permission </td>
                                                </tr>
                                                `
                console.log(d)
                for (let i = 0; i < d.form_folder_access_path.length; i++) {
                    html += `<tr>
                                    <td>${d.form_folder_access_path[i].folder}</td>
                                    <td>${d.form_folder_access_path[i].subfolder}</td>
                                    <td>${d.form_folder_access_path[i].subsubfolder ?? '-'}</td>
                                    <td>${d.form_folder_access_path[i].permission}</td>`
                    html += `</tr>
                    `
                }

                html += `
                        <tr>
                            <td>Manager Note</td>
                            <td colspan="3">${d.manager_note ?? '-'}</td>
                        </tr>               
                        <tfoot>
                            <tr>
                                <th>Purpose</th>
                                <th colspan="3">${d.purpose}</th>
                            </tr>
                            <tr>
                                <th>Created by</th>
                                <th colspan="3">${d.creator_created_by}</th>
                            </tr>
                        </tfoot>                      
                        </table>`

                return html
            }

            $('#reject_reason').on('keyup', function() {
                if ($(this).val() != "")
                    $('#btn-reject').removeAttr('disabled');
                else
                    $('#btn-reject').attr('disabled', 'disabled');
            });

            $('#btn-approve').on('click', function() {
                let id_folder_access = $('#id_folder_access').val();
                console.log(id_folder_access);
                // window.location.href = "{{ route('website.account.approve_it') }}";
                $.ajax({
                    url: "{{ route('website.folder-access.approve_it') }}",
                    type: "POST",
                    data: {
                        id: id_folder_access,
                        it_note: $('#note').val(),
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

            $('#btn-reject').on('click', function() {
                let id_folder_access_reject = $('#id_folder_access_reject').val();
                console.log(id_folder_access_reject);
                // window.location.href = "{{ route('website.account.approve_it') }}";
                $.ajax({
                    url: "{{ route('website.folder-access.approve_it') }}",
                    type: "POST",
                    data: {
                        id: id_folder_access_reject,
                        type: 'reject',
                        it_note: $('#reject_reason').val(),
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {

                        toastr['success'](response)
                        table.ajax.reload();
                        $('#rejectModal').modal('hide')
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                    }
                });
            });

            // $('#confirmModal').on('shown.bs.modal', function() {
            //     $('#nama').text('Nama Requestor')
            // });

            $('.table').on('click', '.btn-table-approve', function() {
                var id_folder_access = $(this).data('id');
                var username_folder_access = $(this).data('username');
                $('#id_folder_access').val(id_folder_access)
                $('#username_folder_access').val(username_folder_access)
                // console.log(id_folder_access);
            })

            $('.table').on('click', '.btn-table-reject', function() {
                var id_folder_access_reject = $(this).data('id');
                $('#id_folder_access_reject').val(id_folder_access_reject)
                // console.log(id_folder_access_reject);
            })

        })
    </script>
    <script>
        const btnApprove = document.getElementById('btn-approve');

        btnApprove.addEventListener('click', function() {
            btnApprove.disabled = true;
        });
    </script>
@endpush
