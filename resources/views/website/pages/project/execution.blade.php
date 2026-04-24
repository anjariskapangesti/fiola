@extends('website.layouts.main', ['title' => 'Execution Project'])

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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure want to approve this item?
                    <input type="text" readonly class="form-control-plaintext" id="no_reg_approve">
                    <input type="hidden" id="id_approve">
                    <div class="form-floating form-floating-outline">
                        <textarea class="form-control auto-resize" id="finish_note_approve" name="finish_note_approve"
                            placeholder="add note if there are additional" style="height: 115px;">{{ old('finish_note_approve') }}</textarea>
                        <label for="finish_note_approve">Finish Note</label>
                    </div>
                    <label class="col-sm-6 col-form-label" for="notifikasi_approve">
                        <small class="text-light fw-medium d-block">Kirim Notifikasi Whatsapp?</small>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="notifikasi_approve"
                                name="notifikasi_approve" {{ old('notifikasi_approve') ? 'checked' : '' }} checked />
                            <label class="form-check-label" for="notifikasi_approve">(Tidak/Ya)</label>
                        </div>
                    </label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="btn-approve">Yes, Approve!</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="progressModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Progress Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure want to progress this item?
                    <input type="text" readonly class="form-control-plaintext" id="no_reg_progress">
                    <input type="hidden" id="id_progress">
                    <div class="form-floating form-floating-outline">
                        <textarea class="form-control auto-resize" id="on_progress_note_progress" name="on_progress_note_progress"
                            placeholder="add note if there are additional">{{ old('on_progress_note_progress') }}</textarea>
                        <label for="on_progress_note_progress">Progress Note</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-info" id="btn-progress" disabled>Yes, Progress!</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure want to reject this item?
                    <input type="text" readonly class="form-control-plaintext" id="no_reg_reject">
                    <input type="hidden" id="id_reject">
                    <div class="form-floating form-floating-outline">
                        <textarea class="form-control auto-resize" id="finish_note_reject" name="finish_note_reject"
                            placeholder="add note if there are additional">{{ old('finish_note_reject') }}</textarea>
                        <label for="finish_note_reject">Reject Note</label>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function getApprovalCount() {
            // kosongkan kalau function global ini tidak ada di project kamu
            // kalau memang sudah ada di layout / file lain, biarkan saja
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.auto-resize').forEach(function(textarea) {
                const resize = function () {
                    this.style.height = 'auto';
                    this.style.height = this.scrollHeight + 'px';
                };

                textarea.addEventListener('input', resize);
                resize.call(textarea);
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            @if (session()->has('success'))
                toastr['success']("{{ Session('success') }}");
            @endif
        });
    </script>

    <script>
        $(document).ready(function() {
            var table = $('#app_table').DataTable({
                lengthChange: true,
                processing: true,
                serverSide: false,
                orderable: true,
                ajax: {
                    url: "{{ route('website.project.execution_ajax') }}",
                },
                columns: [{
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
                        className: 'detail',
                        orderable: false,
                        data: null,
                        content: '',
                        searchable: false,
                        render: function() {
                            return `<button class="badge bg-primary">Klik untuk Detail dan Approve</button>`;
                        }
                    },
                ],
            });

            function format(d) {
                return `
                    <table class="table table-bordered table-sm" style="background-color: #ebf1f2;">
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Project Name</td>
                                <td>${d.nama_project ?? '-'} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">User</td>
                                <td>${d.npk ?? '-'} / ${d.fullname ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Department</td>
                                <td>${d.department ?? '-'} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Start Date</td>
                                <td>${d.start_date ?? '-'} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">End Date</td>
                                <td>${d.end_date ?? '-'} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">No. HP</td>
                                <td>${d.phone ?? '-'}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Lampiran</td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm btn-lampiran"
                                        data-bs-toggle="modal"
                                        data-bs-target="#pdfModal"
                                        data-lampiran="{{ asset('storage/lampiran') }}/${d.lampiran}">
                                        <i class="mdi mdi-file-download"></i> View
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Kondisi Sebelum Improvement</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.kondisi_sebelum ?? '-'} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Kondisi yang diharapkan</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.kondisi_target ?? '-'} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Benefit yang didapat</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.benefit ?? '-'} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Additional Support Device</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.alat ?? '-'} </td>
                            </tr>
                        </tbody>

                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>Manager Approval Date</td>
                                <td>${d.manager_approval_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Manager Approval By</td>
                                <td>${d.manager_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Manager Note</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.manager_note ?? '-'}</td>
                            </tr>
                        </tbody>

                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>ITD Approval Date</td>
                                <td>${d.it_approval_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Approval By</td>
                                <td>${d.it_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Note</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.it_note ?? '-'}</td>
                            </tr>
                        </tbody>

                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>ITD Manager Approval Date</td>
                                <td>${d.it_mgr_approval_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Manager Approval By</td>
                                <td>${d.it_mgr_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Manager Note</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.it_mgr_note ?? '-'}</td>
                            </tr>
                        </tbody>

                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>On Progress Date</td>
                                <td>${d.on_progress_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>On Progress By</td>
                                <td>${d.on_progress_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>On Progress Note</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.on_progress_note ?? '-'}</td>
                            </tr>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-end">
                                    <button class="btn btn-danger btn-sm btn-table-reject"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectModal"
                                        data-id="${d.id}"
                                        data-no_reg="${d.no_reg}">
                                        Reject
                                    </button>
                                    <button class="btn btn-info btn-sm btn-table-progress"
                                        data-bs-toggle="modal"
                                        data-bs-target="#progressModal"
                                        data-id="${d.id}"
                                        data-no_reg="${d.no_reg}">
                                        Progress
                                    </button>
                                    <button class="btn btn-success btn-sm btn-table-approve"
                                        data-bs-toggle="modal"
                                        data-bs-target="#approveModal"
                                        data-id="${d.id}"
                                        data-no_reg="${d.no_reg}">
                                        Approve
                                    </button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                `;
            }

            $(document).on('click', '.btn-lampiran', function() {
                var lampiranUrl = $(this).data('lampiran');
                $('#pdfViewer').attr('src', lampiranUrl);
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

            // APPROVE
            $('#app_table').on('click', '.btn-table-approve', function() {
                var id_approve = $(this).data('id');
                var no_reg_approve = $(this).data('no_reg');
                var approveButton = document.getElementById('btn-approve');

                approveButton.removeAttribute('disabled');
                approveButton.innerHTML = 'Yes, Approve!';

                $('#id_approve').val(id_approve);
                $('#no_reg_approve').val(no_reg_approve);

                var noteText = 'Form Project telah selesai.\n\n';
                noteText += 'Jika ada yang kurang dimengerti, harap hubungi Tim ITD\nTerima Kasih';
                $('#finish_note_approve').val(noteText);
            });

            $('#btn-approve').on('click', function() {
                let id_approve = $('#id_approve').val();
                let notifikasi_approve = $('#notifikasi_approve').is(':checked') ? 'Ya' : 'Tidak';

                $.ajax({
                    url: "{{ route('website.project.execution_approve') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        id: id_approve,
                        finish_note: $('#finish_note_approve').val(),
                        notifikasi: notifikasi_approve,
                        type: 'approve',
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        if (response.status === 'choose_replace') {
                            $('#approveModal').modal('hide');

                            Swal.fire({
                                title: 'Timeline Penuh',
                                text: response.message,
                                icon: 'warning',
                                confirmButtonText: 'Pilih Project'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = response.redirect_url;
                                }
                            });

                        } else if (response.status === 'success') {
                            toastr['success'](response.message);
                            table.ajax.reload(null, false);

                            if (typeof getApprovalCount === 'function') {
                                getApprovalCount();
                            }

                            $('#approveModal').modal('hide');
                        } else {
                            toastr['info'](response.message ?? 'Proses selesai');
                            table.ajax.reload(null, false);
                            $('#approveModal').modal('hide');
                        }
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat memproses data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire('Error', message, 'error');
                    }
                });
            });

            // ON PROGRESS
            $('#on_progress_note_progress').on('keyup', function() {
                if ($(this).val() != "") {
                    $('#btn-progress').removeAttr('disabled');
                } else {
                    $('#btn-progress').attr('disabled', 'disabled');
                }
            });

            $('#app_table').on('click', '.btn-table-progress', function() {
                var id_progress = $(this).data('id');
                var no_reg_progress = $(this).data('no_reg');
                var progressButton = document.getElementById('btn-progress');

                progressButton.removeAttribute('disabled');
                progressButton.innerHTML = 'Yes, Progress!';

                $('#id_progress').val(id_progress);
                $('#no_reg_progress').val(no_reg_progress);
                $('#on_progress_note_progress').val('');
            });

            $('#btn-progress').on('click', function() {
                let id_progress = $('#id_progress').val();

                $.ajax({
                    url: "{{ route('website.project.execution_approve') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        id: id_progress,
                        on_progress_note: $('#on_progress_note_progress').val(),
                        type: 'progress',
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response.message ?? 'Progress Successfully');
                        table.ajax.reload(null, false);

                        if (typeof getApprovalCount === 'function') {
                            getApprovalCount();
                        }

                        $('#progressModal').modal('hide');
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat memproses data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire('Error', message, 'error');
                    }
                });
            });

            // REJECT
            $('#finish_note_reject').on('keyup', function() {
                if ($(this).val() != "") {
                    $('#btn-reject').removeAttr('disabled');
                } else {
                    $('#btn-reject').attr('disabled', 'disabled');
                }
            });

            $('#app_table').on('click', '.btn-table-reject', function() {
                var id_reject = $(this).data('id');
                var no_reg_reject = $(this).data('no_reg');
                var rejectButton = document.getElementById('btn-reject');

                rejectButton.innerHTML = 'Yes, Reject!';
                rejectButton.setAttribute('disabled', 'disabled');

                $('#id_reject').val(id_reject);
                $('#no_reg_reject').val(no_reg_reject);
                $('#finish_note_reject').val('');
            });

            $('#btn-reject').on('click', function() {
                let id_reject = $('#id_reject').val();

                $.ajax({
                    url: "{{ route('website.project.execution_approve') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        id: id_reject,
                        finish_note: $('#finish_note_reject').val(),
                        type: 'reject',
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response.message ?? 'Reject Successfully');
                        table.ajax.reload(null, false);

                        if (typeof getApprovalCount === 'function') {
                            getApprovalCount();
                        }

                        $('#rejectModal').modal('hide');
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat memproses data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire('Error', message, 'error');
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var approveButton = document.getElementById('btn-approve');
            var spinner = '<i class="mdi mdi-loading spin"></i>';

            approveButton.addEventListener('click', function() {
                approveButton.setAttribute('disabled', 'true');
                approveButton.innerHTML = spinner + ' Approving...';
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var progressButton = document.getElementById('btn-progress');
            var spinner = '<i class="mdi mdi-loading spin"></i>';

            progressButton.addEventListener('click', function() {
                progressButton.setAttribute('disabled', 'true');
                progressButton.innerHTML = spinner + ' Progressing...';
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var rejectButton = document.getElementById('btn-reject');
            var spinner = '<i class="mdi mdi-loading spin"></i>';

            rejectButton.addEventListener('click', function() {
                rejectButton.setAttribute('disabled', 'true');
                rejectButton.innerHTML = spinner + ' Rejecting...';
            });
        });
    </script>
@endpush