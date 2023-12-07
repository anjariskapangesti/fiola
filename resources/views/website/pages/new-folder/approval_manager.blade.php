@extends('website.layouts.main', ['title' => 'Manager Approval New Folder'])

@section('content')
    <div class="pagetitle">
        <h4>File Server Folder Add/Change/Delete Form (FRM-ITD-S13-003-00)</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Manager Approval</a></li>
                <li class="breadcrumb-item active"><a href="#">Form New Folder</a></li>
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
                                <th>New Folder Name</th>
                                <th>Main Path</th>
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
                        <input type="text" readonly class="form-control-plaintext" id="foldername_folder_access">
                        <input type="hidden" id="id_new_folder">
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
                        <input type="hidden" id="id_new_folder_reject">
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
    <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet" />
@endpush
@push('scripts')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script lang="text/javascript">
        $(function() {


            var table = $('.table').DataTable({
                bLengthChange: true,
                processing: true,
                ordering: true,
                serverSide: true,
                ajax: {
                    'url': "{{ route('website.new-folder.show_manager_approval_ajax') }}",
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
                        data: 'creator_foldername',
                        name: 'creator_foldername',
                    },
                    {
                        data: 'creator_mainpath',
                        name: 'creator_mainpath',
                    },
                    {
                        orderable: false,
                        searchable: false,
                        data: null,
                        render: function(data, type, row, meta) {
                            return `
                            <button class="btn btn-success btn-sm btn-table-approve" data-bs-toggle="modal" data-bs-target="#confirmModal" data-id="${data.id}" data-foldername="${data.foldername}">Approve</button>
                            <button class="btn btn-danger btn-sm btn-table-reject" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="${data.id}" data-foldername="${data.foldername}">Reject</button>`;
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
                    <table class = "table table-sm">
                                                <tr class = "bg-light">
                                                <td> Username </td>
                                                <td> Department </td>
                                                <td> Permission </td>
                                                </tr>
                                                `
                console.log(d)
                for (let i = 0; i < d.form_new_folder_access.length; i++) {
                    html += `<tr>
                                    <td>${d.form_new_folder_access[i].username}</td>
                                    <td>${d.form_new_folder_access[i].department}</td>
                                    <td>${d.form_new_folder_access[i].permission}</td>`
                    html += `</tr>
                    `
                }

                html += `
                        <tfoot>
                            <tr>
                                <th>Purpose</th>
                                <th colspan="2">${d.creator_purpose}</th>
                            </tr>
                            <tr>
                                <th>Created by</th>
                                <th colspan="2">${d.creator_created_by}</th>
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
                let id_new_folder = $('#id_new_folder').val();
                console.log(id_new_folder);
                $.ajax({
                    url: "{{ route('website.new-folder.approve_manager') }}",
                    type: "POST",
                    data: {
                        id: id_new_folder,
                        manager_note: $('#note').val(),
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
                let id_new_folder_reject = $('#id_new_folder_reject').val();
                console.log(id_new_folder_reject);
                $.ajax({
                    url: "{{ route('website.new-folder.approve_manager') }}",
                    type: "POST",
                    data: {
                        id: id_new_folder_reject,
                        type: 'reject',
                        manager_note: $('#reject_reason').val(),
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

            $('.table').on('click', '.btn-table-approve', function() {
                var id_new_folder = $(this).data('id');
                var foldername_folder_access = $(this).data('foldername');
                $('#id_new_folder').val(id_new_folder)
                $('#foldername_folder_access').val(foldername_folder_access)
            })

            $('.table').on('click', '.btn-table-reject', function() {
                var id_new_folder_reject = $(this).data('id');
                $('#id_new_folder_reject').val(id_new_folder_reject)
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
