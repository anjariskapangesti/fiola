@extends('website.layouts.main', ['title' => 'IT MGR Approval Relayout'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h5 class="card-header">Form Permohonan IT Re-layout (FRM-ITD-S13-010-00)</h5>
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
                    <tbody class="table-border-bottom-0">
                    </tbody>
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
                        <textarea class="form-control auto-resize" id="it_mgr_note_approve" name="it_mgr_note_approve"
                            placeholder="add note if there are additional">{{ old('it_mgr_note_approve') }}</textarea>
                        <label for="it_mgr_note_approve">ITD MGR Note</label>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure want to reject this item?
                    <input type="text" readonly class="form-control-plaintext" id="no_reg_reject">
                    <input type="hidden" id="id_reject">
                    <div class="form-floating form-floating-outline">
                        <textarea class="form-control auto-resize" id="it_mgr_note_reject" name="it_mgr_note_reject"
                            placeholder="add note if there are additional">{{ old('it_mgr_note_reject') }}</textarea>
                        <label for="it_mgr_note_reject">ITD MGR Note</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="btn-reject" disabled>Yes, Reject!</button>
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
        const textarea = document.querySelector('.auto-resize');

        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    </script>
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
                    url: "{{ route('website.relayout.it_mgr_approval_ajax') }}",
                },
                columns: [{
                        data: null,
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row, meta) {
                            var rowIndex = meta.row + meta.settings._iDisplayStart + 1;
                            return rowIndex;
                        },
                        className: "text-center" // Menetapkan kelas CSS 'text-center'
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
                                return `<span class="badge bg-warning">Waiting Manager Approve</span>`;
                            } else if (data == 'Manager Approve') {
                                return `<span class="badge bg-warning">Waiting ITD Approve</span>`;
                            } else if (data == 'IT Approve') {
                                return `<span class="badge bg-warning">Waiting ITD MGR Approve</span>`;
                            } else if (data == 'IT MGR Approve') {
                                return `<span class="badge bg-warning">Waiting Execution</span>`;
                            } else if (data == 'On Progress') {
                                return `<span class="badge bg-info">On Progress</span>`;
                            } else if (data == 'Finished') {
                                return `<span class="badge bg-success">Finished</span>`;
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
                        render: function(data, type, row, meta) {
                            return `<button class="badge bg-primary">Klik untuk Detail dan Approve</button>`;
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
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Budget Type</td>
                                <td>${d.budget_type} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Request Type</td>
                                <td>${d.request_type} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Project Name</td>
                                <td>${d.project_name} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Date Finish Plan</td>
                                <td>${d.date_finish_plan}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Location</td>
                                <td>${d.location} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Relayout Type</td>
                                <td>${d.relayout_type}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Description</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.description} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Purpose</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.purpose} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Lampiran</td>
                                <td style="max-width: 250px; white-space: pre-wrap;"><a href="{{ asset('storage/lampiran/${d.lampiran}') }}" target="_blank" class="btn btn-success btn-sm"><i class="mdi mdi-file-download"></i> Lihat / Download</a></td>
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
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-end">
                                    <button class="btn btn-danger btn-sm btn-table-reject" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="${d.id}" data-no_reg="${d.no_reg}">Reject</button>
                                    <button class="btn btn-success btn-sm btn-table-approve" data-bs-toggle="modal" data-bs-target="#approveModal" data-id="${d.id}" data-no_reg="${d.no_reg}">Approve</button>
                                </th>
                            </tr>    
                        </tfoot>
                    </table>
                    `
                );
            }

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
                $('#id_approve').val(id_approve)
                $('#no_reg_approve').val(no_reg_approve)
                $('#it_mgr_note_approve').val('');
            })

            $('#btn-approve').on('click', function() {
                let id_approve = $('#id_approve').val();
                $.ajax({
                    url: "{{ route('website.relayout.it_mgr_approve') }}",
                    type: "POST",
                    data: {
                        id: id_approve,
                        it_mgr_note: $('#it_mgr_note_approve').val(),
                        type: 'approve',
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response)
                        table.ajax.reload();
                        getApprovalCount();
                        $('#approveModal').modal('hide')
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                    }
                });
            });
            // REJECT
            $('#it_mgr_note_reject').on('keyup', function() {
                if ($(this).val() != "")
                    $('#btn-reject').removeAttr('disabled');
                else
                    $('#btn-reject').attr('disabled', 'disabled');
            });

            $('#app_table').on('click', '.btn-table-reject', function() {
                var id_reject = $(this).data('id');
                var no_reg_reject = $(this).data('no_reg');
                var approveButton = document.getElementById('btn-reject');

                approveButton.innerHTML = 'Yes, Reject!';
                $('#id_reject').val(id_reject)
                $('#no_reg_reject').val(no_reg_reject)
                $('#it_mgr_note_reject').val('');
            })

            $('#btn-reject').on('click', function() {
                let id_reject = $('#id_reject').val();
                $.ajax({
                    url: "{{ route('website.relayout.it_mgr_approve') }}",
                    type: "POST",
                    data: {
                        id: id_reject,
                        it_mgr_note: $('#it_mgr_note_reject').val(),
                        type: 'reject',
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response)
                        table.ajax.reload();
                        getApprovalCount();
                        $('#rejectModal').modal('hide')
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
            var rejectButton = document.getElementById('btn-reject');
            var spinner = '<i class="mdi mdi-loading spin"></i>';

            rejectButton.addEventListener('click', function() {
                rejectButton.setAttribute('disabled', 'true');
                rejectButton.innerHTML = spinner + ' Rejecting...';
            });
        });
    </script>
@endpush
