@extends('website.layouts.main', ['title' => 'Track Forms Project'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h5 class="card-header">Request Project for Application (FRM-ITD-S13-046-00)</h5>
            </div>
            <div class="table-responsive text-nowrap" style="padding: 0 1.25rem 0 1.25rem;">
                @if (Session::get('info'))
                    <div class="alert alert-info">
                        {{ Session::get('info') }}
                    </div>
                @endif
                <table class="table table-bordered" id="app_table" width="100%">
                    <thead>
                        <tr>
                            <th width="50px">No</th>
                            <th style="max-width: 50px;">Detail</th>
                            <th>No. Reg</th>
                            <th>Requestor</th>
                            <th>Created Date</th>
                            <th>Status</th>
                            <th width="150px">Option</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Finish Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure want to confirm this item?
                    <input type="text" readonly class="form-control-plaintext" id="no_reg_confirm">
                    <input type="hidden" id="id_confirm">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="btn-confirm">Yes, Confirm!</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure want to delete this item?
                    <input type="text" readonly class="form-control-plaintext" id="no_reg_delete">
                    <input type="hidden" id="id_delete">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="btn-delete">Yes, Delete!</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="pdfModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>View PDF</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="pdfViewer" src="" width="100%" height="600px"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/datatables/css/datatables.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('vendor/datatables/js/datatables.min.js') }}"></script>
    <script src="{{ asset('vendor/moment/moment.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            @if (session()->has('success'))
                toastr['success']("{{ Session('success') }}")
            @endif
        })
    </script>

    <script>
        $(document).ready(function() {
            var table = $('#app_table').DataTable({
                'lengthChange': true,
                'processing': true,
                'serverSide': false,
                'orderable': true,
                ajax: {
                    url: "{{ route('website.project.list_ajax') }}",
                },
                columns: [{
                        data: null,
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row, meta) {
                            var rowIndex = meta.row + meta.settings._iDisplayStart + 1;
                            return rowIndex;
                        },
                        className: "text-center"
                    },
                    {
                        className: 'dt-control text-center',
                        orderable: false,
                        data: null,
                        defaultContent: '',
                        searchable: false,
                    },
                    {
                        data: 'no_reg',
                        name: 'no_reg',
                    },
                    {
                        data: 'requestor',
                        name: 'requestor',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type, row, meta) {
                            return moment(data).format('YYYY-MM-DD HH:mm:ss');
                        }
                    },
                    {
                        data: 'final_status',
                        name: 'final_status',
                        render: function(data, type, row, meta) {
                            if (data == 'created') {
                                return `<span class="badge bg-warning">Waiting Manager Approval</span>`;
                            } else if (data == 'Waiting Director Approval' || data == 'Manager Approve') {
                                return `<span class="badge bg-warning">Waiting Director Approval</span>`;
                            } else if (data == 'Director Approve' || data == 'Finished') {
                                return `<span class="badge bg-success">Finished</span>`;
                            } else if (data == 'Waiting Target Response') {
                                return `<span class="badge bg-info">Waiting Target Response</span>`;
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
                            if (data.is_confirm == '0' && data.created_by == '{{ Auth::user()->id }}') {
                                return `
                                <center>
                                    <button class="btn btn-success btn-sm btn-table-confirm" data-bs-toggle="modal" data-bs-target="#confirmModal" data-id="${data.id}" data-no_reg="${data.no_reg}">Confirm</button>
                                </center>
                                `;
                            } else if (data.is_confirm == '1') {
                                return `<center><span class="badge bg-success">Confrimed</span></center>`
                            } else if (data.final_status == 'created' && data.created_by == '{{ Auth::user()->id }}') {
                                return `
                                <center>
                                    <button class="btn btn-danger btn-sm btn-table-delete mt-1" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="${data.id}" data-no_reg="${data.no_reg}">Delete</button>
                                </center>
                                `;
                            } else {
                                return `<center></center>`;
                            }
                        }
                    },
                ],
            });

            function format(d) {
                return (
                    `
                    <table class="table table-bordered table-sm" style="background-color: #ebf1f2;">
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Project Name</td>
                                <td>${d.nama_project ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">User</td>
                                <td>${d.npk ?? '-'} / ${d.fullname ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Department</td>
                                <td>${d.department ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Start Date</td>
                                <td>${d.start_date ? moment(d.start_date).format('YYYY-MM-DD') : '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">End Date</td>
                                <td>${d.end_date ? moment(d.end_date).format('YYYY-MM-DD') : '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">No. HP</td>
                                <td>${d.phone ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Lampiran</td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm btn-lampiran" data-bs-toggle="modal" data-bs-target="#pdfModal" data-lampiran="{{ asset('storage/lampiran/${d.lampiran}') }}">
                                        <i class="mdi mdi-file-download"></i> View
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Kondisi Sebelum Improvement</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.kondisi_sebelum ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Kondisi yang diharapkan</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.kondisi_target ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Benefit yang didapat</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.benefit ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Additional Support Device</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.alat ?? '-'}</td>
                            </tr>
                            ${d.is_reschedule ? `
                            <tr class="table-warning">
                                <td style="background-color: #ffc107; font-weight: bold;">Reschedule Target</td>
                                <td style="font-weight: bold; color: #856404;">${d.target_project_name ?? d.reschedule_target_id}</td>
                            </tr>
                            <tr class="table-warning">
                                <td style="background-color: #ffc107; font-weight: bold;">Alasan Reschedule</td>
                                <td style="font-weight: bold; color: #856404; white-space: pre-wrap;">${d.reschedule_reason ?? '-'}</td>
                            </tr>
                            <tr class="table-warning">
                                <td style="background-color: #ffc107; font-weight: bold;">Respon Target</td>
                                <td style="font-weight: bold; color: #856404;">
                                    <strong>${d.target_response ? (d.target_response == 'yes' ? 'SETUJU (YES)' : 'MENOLAK (NO)') : 'PENDING'}</strong>
                                </td>
                            </tr>
                            ` : ''}
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">Manager Approval Date</td>
                                <td>${d.manager_approval_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">Manager Approval By</td>
                                <td>${d.manager_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">Manager Note</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.manager_note ?? '-'}</td>
                            </tr>
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">Director Approval Date</td>
                                <td>${d.dir_approval_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">Director Approval By</td>
                                <td>${d.dir_approve_by_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">Director Note</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.dir_note ?? '-'}</td>
                            </tr>
                        </tbody>
                    </table>
                    `
                );
            }

            $(document).on('click', '.btn-lampiran', function() {
                var lampiranUrl = $(this).data('lampiran');
                $('#pdfViewer').attr('src', lampiranUrl);
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

            $('#app_table').on('click', '.btn-table-delete', function() {
                var id_delete = $(this).data('id');
                var no_reg_delete = $(this).data('no_reg');

                $('#id_delete').val(id_delete)
                $('#no_reg_delete').val(no_reg_delete)
            })

            $('#btn-delete').on('click', function() {
                let id_delete = $('#id_delete').val();
                $.ajax({
                    url: "{{ route('website.project.delete_form') }}",
                    type: "POST",
                    data: {
                        id: id_delete,
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

            $('#app_table').on('click', '.btn-table-confirm', function() {
                var id_confirm = $(this).data('id');
                var no_reg_confirm = $(this).data('no_reg');
                var confirmButton = document.getElementById('btn-confirm');

                confirmButton.removeAttribute('disabled');
                confirmButton.innerHTML = 'Yes, Confirm!';
                $('#id_confirm').val(id_confirm)
                $('#no_reg_confirm').val(no_reg_confirm)
            })

            $('#btn-confirm').on('click', function() {
                let id_confirm = $('#id_confirm').val();
                $.ajax({
                    url: "{{ route('website.project.approve_form') }}",
                    type: "POST",
                    data: {
                        id: id_confirm,
                        type: 'confirm',
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response)
                        table.ajax.reload();
                        getApprovalCount();
                        $('#confirmModal').modal('hide')
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var confirmButton = document.getElementById('btn-confirm');
            var spinner = '<i class="mdi mdi-loading spin"></i>';

            confirmButton.addEventListener('click', function() {
                confirmButton.setAttribute('disabled', 'true');
                confirmButton.innerHTML = spinner + ' Confirming...';
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var deleteButton = document.getElementById('btn-delete');
            var spinner = '<i class="mdi mdi-loading spin"></i>';

            deleteButton.addEventListener('click', function() {
                deleteButton.setAttribute('disabled', 'true');
                deleteButton.innerHTML = spinner + ' Deleting...';
            });
        });
    </script>
@endpush