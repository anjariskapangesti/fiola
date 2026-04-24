@extends('website.layouts.main', ['title' => 'Reschedule Notifications'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h5 class="card-header">Project Reschedule Requests Targeting Your Projects</h5>
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
                            <th>New Requestor</th>
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
                    <h5 class="modal-title">Confirm Reschedule (Yes)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to <b>reschedule</b> your project to make room for this new request? 
                    Your project will be moved to the next available vacant slot in the timeline.
                    <input type="text" readonly class="form-control-plaintext" id="no_reg_approve">
                    <input type="hidden" id="id_approve">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="btn-approve">Yes, Reschedule!</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Decline Reschedule (No)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to <b>decline</b> this reschedule request? 
                    The request will still proceed to the Director for a final decision.
                    <input type="text" readonly class="form-control-plaintext" id="no_reg_reject">
                    <input type="hidden" id="id_reject">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="btn-reject">No, Keep My Schedule!</button>
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
                'orderable': true,
                ajax: {
                    url: "{{ route('website.project.reschedule_notifications_ajax') }}",
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
                            return `<span class="badge bg-warning">${data}</span>`;
                        }
                    },
                    {
                        className: 'detail',
                        orderable: false,
                        data: null,
                        content: '',
                        searchable: false,
                        render: function(data) {
                            return `<button class="badge bg-primary">View Detail & Respond</button>`;
                        }
                    },
                ],
            });

            function format(d) {
                return (
                    `
                    <table class="table table-bordered table-sm" style="background-color: #f8f9fa;">
                        <tbody>
                            <tr>
                                <td style="width: 200px; font-weight: bold;">New Project Name</td>
                                <td>${d.nama_project} </td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Requester Dept</td>
                                <td>${d.department} </td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Proposed Start Date</td>
                                <td>${d.start_date} </td>
                            </tr>
                             <tr>
                                <td style="font-weight: bold;">Lampiran (New Project)</td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm btn-lampiran" data-bs-toggle="modal" data-bs-target="#pdfModal" data-lampiran="{{ asset('storage/lampiran/${d.lampiran}') }}">
                                        <i class="mdi mdi-file-download"></i> View
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Reason for Project</td>
                                <td style="white-space: pre-wrap;">${d.kondisi_target} </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-end">
                                    <button class="btn btn-danger btn-sm btn-table-reject" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="${d.id}" data-no_reg="${d.no_reg}">No (Keep Schedule)</button>
                                    <button class="btn btn-success btn-sm btn-table-approve" data-bs-toggle="modal" data-bs-target="#approveModal" data-id="${d.id}" data-no_reg="${d.no_reg}">Yes (Reschedule)</button>
                                </th>
                            </tr>    
                        </tfoot>
                    </table>
                    `
                );
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

            // YES
            $('#app_table').on('click', '.btn-table-approve', function() {
                $('#id_approve').val($(this).data('id'))
                $('#no_reg_approve').val($(this).data('no_reg'))
            })

            $('#btn-approve').on('click', function() {
                let btn = $(this);
                btn.attr('disabled', 'true').html('<i class="mdi mdi-loading spin"></i> Processing...');
                $.ajax({
                    url: "{{ route('website.project.target_respond') }}",
                    type: "POST",
                    data: {
                        id: $('#id_approve').val(),
                        type: 'yes',
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response)
                        table.ajax.reload();
                        $('#approveModal').modal('hide')
                        btn.removeAttr('disabled').html('Yes, Reschedule!');
                    },
                    error: function(xhr) {
                        toastr['error'](xhr.responseJSON.error || 'Something went wrong');
                        btn.removeAttr('disabled').html('Yes, Reschedule!');
                    }
                });
            });

            // NO
            $('#app_table').on('click', '.btn-table-reject', function() {
                $('#id_reject').val($(this).data('id'))
                $('#no_reg_reject').val($(this).data('no_reg'))
            })

            $('#btn-reject').on('click', function() {
                let btn = $(this);
                btn.attr('disabled', 'true').html('<i class="mdi mdi-loading spin"></i> Processing...');
                $.ajax({
                    url: "{{ route('website.project.target_respond') }}",
                    type: "POST",
                    data: {
                        id: $('#id_reject').val(),
                        type: 'no',
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response)
                        table.ajax.reload();
                        $('#rejectModal').modal('hide')
                        btn.removeAttr('disabled').html('No, Keep My Schedule!');
                    },
                    error: function(xhr) {
                        toastr['error'](xhr.responseJSON.error || 'Something went wrong');
                        btn.removeAttr('disabled').html('No, Keep My Schedule!');
                    }
                });
            });
        });
    </script>
@endpush
