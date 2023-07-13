@extends('website.layouts.main', ['title' => 'ITD Approval Folder Access'])

@section('content')
    <div class="pagetitle">
        <h4>Approval Change Access of Folder Share Application</h4>
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
                <div class="card-body p-3">
                    <table class="display" width="100%" id="app_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Username</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!-- Approve Confirmation Modal -->

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
                        <td width="30%">Folder</td>
                        <td>${d.username} </td>
                    </tr>
                    <tr>
                        <td>Subfolder</td>
                        <td>${d.company} </td>
                    </tr>
                    <tr>
                        <td>Permission</td>
                        <td>${d.phone} </td>
                    </tr>                    
                </table>
                `
            );
        }

        $(document).ready(function() {
            var table = $('#app_table').DataTable({
                "lengthChange": false,
                'processing': true,
                'serverSide': true,
                ajax: {
                    url: "{{ route('website.folder-access.show_it_approval_ajax') }}",
                },
                columns: [{
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: '',
                        searchable: false,
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

            $('#btn-approve').on('click', function() {
                let id_folder_access = $('#id_folder_access').val();
                console.log(id_folder_access);
                // window.location.href = "{{ route('website.account.approve_it') }}";
                $.ajax({
                    url: "{{ route('website.folder-access.approve_it') }}",
                    type: "POST",
                    data: {
                        id: id_folder_access,
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

            // $('#confirmModal').on('shown.bs.modal', function() {
            //     $('#nama').text('Nama Requestor')
            // });

            $('#app_table').on('click', '.btn-table-approve', function() {
                var id_folder_access = $(this).data('id');
                var username_folder_access = $(this).data('username');
                $('#id_folder_access').val(id_folder_access)
                $('#username_folder_access').val(username_folder_access)
                // console.log(id_folder_access);
            })

            $('#app_table').on('click', '.btn-table-reject', function() {
                var id_folder_access_reject = $(this).data('id');
                $('#id_folder_access_reject').val(id_folder_access_reject)
                // console.log(id_folder_access_reject);
            })

        });
    </script>
@endpush
