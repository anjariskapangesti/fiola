@extends('website.layouts.main', ['title' => 'Manager Approval Project'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h5 class="card-header">Request Project for Application (FRM-ITD-S13-046-00)</h5>
            </div>

            <div class="row">
                @if (Session::get('info'))
                    <div class="alert alert-info">
                        {{ Session::get('info') }}
                    </div>
                @endif
            </div>

            <div class="table-responsive text-nowrap" style="padding: 0 1.25rem 0 1.25rem;">
                <table class="table table-bordered" id="app_table" width="100%">
                    <thead>
                        <tr>
                            <th width="50px">No</th>
                            <th>No. Reg</th>
                            <th>Requestor</th>
                            <th>Created Date</th>
                            <th>Status</th>
                            <th width="150px">Option</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="approveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    Are you sure want to approve this item?
                    <input type="text" readonly class="form-control-plaintext" id="no_reg_approve">
                    <input type="hidden" id="id_approve">

                    <div class="form-floating form-floating-outline">
                        <textarea class="form-control auto-resize" id="manager_note_approve" name="manager_note_approve"
                            placeholder="add note if there are additional">{{ old('manager_note_approve') }}</textarea>
                        <label for="manager_note_approve">Manager Note</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="btn-approve">Yes, Approve!</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    Are you sure want to reject this item?
                    <input type="text" readonly class="form-control-plaintext" id="no_reg_reject">
                    <input type="hidden" id="id_reject">

                    <div class="form-floating form-floating-outline">
                        <textarea class="form-control auto-resize" id="manager_note_reject" name="manager_note_reject"
                            placeholder="add note if there are additional">{{ old('manager_note_reject') }}</textarea>
                        <label for="manager_note_reject">Manager Note</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="btn-reject" disabled>Yes, Reject!</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="pdfModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>View PDF</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

            $('.auto-resize').on('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });

            var table = $('#app_table').DataTable({
                lengthChange: true,
                processing: true,
                serverSide: false,
                orderable: true,
                ajax: {
                    url: "{{ route('website.project.manager_approval_ajax') }}",
                },
                columns: [
                    {
                        data: null,
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        className: "text-center"
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
                        render: function(data) {
                            return moment(data).format('YYYY-MM-DD HH:mm:ss');
                        }
                    },
                    {
                        data: 'final_status',
                        name: 'final_status',
                        render: function(data) {
                            if (data == 'created') {
                                return `<span class="badge bg-warning">Menunggu Persetujuan Manager</span>`;
                            } else if (data == 'Waiting Director Approval' || data == 'Manager Approve') {
                                return `<span class="badge bg-warning">Menunggu Persetujuan Direktur</span>`;
                            } else if (data == 'Director Approve') {
                                return `<span class="badge bg-success">Disetujui Direktur</span>`;
                            } else if (data == 'On Progress') {
                                return `<span class="badge bg-info">On Progress</span>`;
                            } else if (data == 'Finished') {
                                return `<span class="badge bg-success">Finished</span>`;
                            } else if (data == 'Manager Reject') {
                                return `<span class="badge bg-danger">Ditolak Manager</span>`;
                            } else if (data == 'Director Reject') {
                                return `<span class="badge bg-danger">Ditolak Direktur</span>`;
                            } else if (data == 'Waiting Target Response') {
                                return `<span class="badge bg-info">Menunggu Respon Target</span>`;
                            } else {
                                return `<span class="badge bg-danger">${data}</span>`;
                            }
                        }
                    },
                    {
                        className: 'detail',
                        orderable: false,
                        data: null,
                        searchable: false,
                        render: function() {
                            return `<button class="badge bg-primary">Klik untuk Detail dan Approve</button>`;
                        }
                    },
                ],
            });

            function format(d) {
                let html = `
                    <table class="table table-bordered table-sm" style="background-color: #ebf1f2;">
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td style="background-color: #66a7e3; width: 260px; font-weight: bold;">Project Name</td>
                                <td>${d.nama_project ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">User</td>
                                <td>${d.npk ?? '-'} / ${d.fullname ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">Department</td>
                                <td>${d.department ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">No. HP</td>
                                <td>${d.phone ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">Lampiran</td>
                                <td>
                                    ${
                                        d.lampiran
                                            ? `<button type="button" class="btn btn-success btn-sm btn-lampiran"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#pdfModal"
                                                    data-lampiran="/storage/lampiran/${d.lampiran}">
                                                    <i class="mdi mdi-file-download"></i> View
                                               </button>`
                                            : '-'
                                    }
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">Kondisi Sebelum Improvement</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.kondisi_sebelum ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">Kondisi yang diharapkan</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.kondisi_target ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">Benefit yang didapat</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.benefit ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">Additional Support Device</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.alat ?? '-'}</td>
                            </tr>
                        </tbody>
                    </table>
                `;

                if (d.is_reschedule == 1 || d.is_reschedule === true || d.is_reschedule === '1') {
                    html += `
                        <div class="mt-3">
                            <h5 style="color:#856404;">Target Project</h5>

                            <table class="table table-bordered table-sm">
                                <tbody style="border: 2px solid #ffc107;">
                                    <tr style="background-color:#fff3cd;">
                                        <td style="width:260px; font-weight:bold;">Project Name</td>
                                        <td>${d.target_project_name ?? '-'}</td>
                                    </tr>
                                    <tr style="background-color:#fff3cd;">
                                        <td style="font-weight:bold;">User</td>
                                        <td>${d.target_fullname ?? '-'}</td>
                                    </tr>
                                    <tr style="background-color:#fff3cd;">
                                        <td style="font-weight:bold;">Department</td>
                                        <td>${d.target_department ?? '-'}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    `;
                }

                html += `
                    <div class="text-end mt-2">
                        <button class="btn btn-danger btn-sm btn-table-reject"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectModal"
                            data-id="${d.id}"
                            data-no_reg="${d.no_reg}">
                            Reject
                        </button>

                        <button class="btn btn-success btn-sm btn-table-approve"
                            data-bs-toggle="modal"
                            data-bs-target="#approveModal"
                            data-id="${d.id}"
                            data-no_reg="${d.no_reg}">
                            Approve
                        </button>
                    </div>
                `;

                return html;
            }

            $(document).on('click', '.btn-lampiran', function() {
                $('#pdfViewer').attr('src', $(this).data('lampiran'));
            });

            $('#app_table tbody').on('click', 'td.detail', function() {
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

            $('#app_table').on('click', '.btn-table-approve', function() {
                $('#btn-approve').removeAttr('disabled').html('Yes, Approve!');
                $('#id_approve').val($(this).data('id'));
                $('#no_reg_approve').val($(this).data('no_reg'));
                $('#manager_note_approve').val('');
            });

            $('#btn-approve').on('click', function() {
                let btn = $(this);
                let id_approve = $('#id_approve').val();

                btn.attr('disabled', true).html('<i class="mdi mdi-loading spin"></i> Approving...');

                $.ajax({
                    url: "{{ route('website.project.manager_approve') }}",
                    type: "POST",
                    data: {
                        id: id_approve,
                        manager_note: $('#manager_note_approve').val(),
                        type: 'approve',
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response);
                        table.ajax.reload();
                        getApprovalCount();
                        $('#approveModal').modal('hide');
                        btn.removeAttr('disabled').html('Yes, Approve!');
                    },
                    error: function(xhr) {
                        toastr['error'](xhr.responseJSON?.error || 'Something went wrong');
                        btn.removeAttr('disabled').html('Yes, Approve!');
                    }
                });
            });

            $('#manager_note_reject').on('keyup', function() {
                if ($(this).val() != "") {
                    $('#btn-reject').removeAttr('disabled');
                } else {
                    $('#btn-reject').attr('disabled', 'disabled');
                }
            });

            $('#app_table').on('click', '.btn-table-reject', function() {
                $('#btn-reject').html('Yes, Reject!');
                $('#id_reject').val($(this).data('id'));
                $('#no_reg_reject').val($(this).data('no_reg'));
                $('#manager_note_reject').val('');
                $('#btn-reject').attr('disabled', 'disabled');
            });

            $('#btn-reject').on('click', function() {
                let btn = $(this);
                let id_reject = $('#id_reject').val();

                btn.attr('disabled', true).html('<i class="mdi mdi-loading spin"></i> Rejecting...');

                $.ajax({
                    url: "{{ route('website.project.manager_approve') }}",
                    type: "POST",
                    data: {
                        id: id_reject,
                        manager_note: $('#manager_note_reject').val(),
                        type: 'reject',
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response);
                        table.ajax.reload();
                        getApprovalCount();
                        $('#rejectModal').modal('hide');
                        btn.removeAttr('disabled').html('Yes, Reject!');
                    },
                    error: function(xhr) {
                        toastr['error'](xhr.responseJSON?.error || 'Something went wrong');
                        btn.removeAttr('disabled').html('Yes, Reject!');
                    }
                });
            });
        });
    </script>
@endpush