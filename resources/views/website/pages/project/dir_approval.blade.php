@extends('website.layouts.main', ['title' => 'Director Approval Project'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h5 class="card-header">Reschedule Request Approval - Director (FRM-ITD-S13-046-00)</h5>
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
                    Are you sure want to approve this reschedule request?
                    <p class="text-danger">This will reschedule the selected project and approve the new project for this slot.</p>
                    <input type="text" readonly class="form-control-plaintext" id="no_reg_approve">
                    <input type="hidden" id="id_approve">
                    <div class="form-floating form-floating-outline">
                        <textarea class="form-control auto-resize" id="dir_note_approve" name="dir_note_approve"
                            placeholder="add note if there are additional"></textarea>
                        <label for="dir_note_approve">Director Note</label>
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
                    Are you sure want to reject this reschedule request?
                    <p class="text-danger">This will reject the new project request.</p>
                    <input type="text" readonly class="form-control-plaintext" id="no_reg_reject">
                    <input type="hidden" id="id_reject">
                    <div class="form-floating form-floating-outline">
                        <textarea class="form-control auto-resize" id="dir_note_reject" name="dir_note_reject"
                            placeholder="add note if there are additional"></textarea>
                        <label for="dir_note_reject">Director Note</label>
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
    <script>
        $(document).ready(function() {
            var table = $('#app_table').DataTable({
                'lengthChange': true,
                'processing': true,
                'serverSide': false,
                ajax: {
                    url: "{{ route('website.project.dir_approval_ajax') }}",
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
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
                            return `<span class="badge bg-warning">${data}</span>`;
                        }
                    },
                    {
                        className: 'detail',
                        orderable: false,
                        data: null,
                        render: function() {
                            return `<button class="btn btn-sm btn-info"><i class="mdi mdi-eye"></i></button>`;
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
                                <td style="background-color: #66a7e3; width: 200px; font-weight: bold;">Project Name</td>
                                <td>${d.nama_project} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">Requestor</td>
                                <td>${d.npk} / ${d.fullname}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">Reschedule Target ID</td>
                                <td>${d.reschedule_target_id} (Project to be replaced)</td>
                            </tr>
                             <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">Lampiran</td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm btn-lampiran" data-bs-toggle="modal" data-bs-target="#pdfModal" data-lampiran="{{ asset('storage/lampiran/${d.lampiran}') }}">
                                        <i class="mdi mdi-file-download"></i> View
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">Benefit</td>
                                <td style="white-space: pre-wrap;">${d.benefit}</td>
                            </tr>
                        </tbody>
                            <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">Target Response</td>
                                <td>
                                    <strong>${d.target_response ? d.target_response.toUpperCase() : 'PENDING'}</strong>
                                    ${d.target_response == 'yes' ? `<br><small class="text-success">Reschedule Proposed: ${d.target_reschedule_start_date} s/d ${d.target_reschedule_end_date}</small>` : ''}
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; font-weight: bold;">Manager Approval</td>
                                <td>${d.is_manager_approve ? 'Approved' : 'Rejected'} by ${d.manager_name ?? '-'} (${d.manager_note ?? '-'})</td>
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

            $(document).on('click', '.btn-lampiran', function() {
                $('#pdfViewer').attr('src', $(this).data('lampiran'));
            });

            // APPROVE
            $('#app_table').on('click', '.btn-table-approve', function() {
                $('#id_approve').val($(this).data('id'));
                $('#no_reg_approve').val($(this).data('no_reg'));
            });

            $('#btn-approve').on('click', function() {
                let btn = $(this);
                btn.attr('disabled', 'disabled').html('<i class="mdi mdi-loading spin"></i> Approving...');
                $.ajax({
                    url: "{{ route('website.project.dir_approve') }}",
                    type: "POST",
                    data: {
                        id: $('#id_approve').val(),
                        dir_note: $('#dir_note_approve').val(),
                        type: 'approve',
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response);
                        table.ajax.reload();
                        $('#approveModal').modal('hide');
                        btn.removeAttr('disabled').html('Yes, Approve!');
                    }
                });
            });

            // REJECT
            $('#dir_note_reject').on('keyup', function() {
                if ($(this).val() != "") $('#btn-reject').removeAttr('disabled');
                else $('#btn-reject').attr('disabled', 'disabled');
            });

            $('#app_table').on('click', '.btn-table-reject', function() {
                $('#id_reject').val($(this).data('id'));
                $('#no_reg_reject').val($(this).data('no_reg'));
            });

            $('#btn-reject').on('click', function() {
                let btn = $(this);
                btn.attr('disabled', 'disabled').html('<i class="mdi mdi-loading spin"></i> Rejecting...');
                $.ajax({
                    url: "{{ route('website.project.dir_approve') }}",
                    type: "POST",
                    data: {
                        id: $('#id_reject').val(),
                        dir_note: $('#dir_note_reject').val(),
                        type: 'reject',
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response);
                        table.ajax.reload();
                        $('#rejectModal').modal('hide');
                        btn.removeAttr('disabled').html('Yes, Reject!');
                    }
                });
            });
        });
    </script>
@endpush
