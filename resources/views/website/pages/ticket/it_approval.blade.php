@extends('website.layouts.main', ['title' => 'IT Approval Ticket'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h5 class="card-header">List Ticket</h5>
            </div>
            <div class="row">
                @if (Session::get('info'))
                    <div class="alert alert-info">
                        {{ Session::get('info') }}
                    </div>
                @endif
            </div>
            <div class="table-responsive text-nowrap" style="padding: 0 1.25rem 0 1.25rem;">
                <table class="table table-bordered" id="app_table" width="100%" style="vertical-align: top;">
                    <thead>
                        <tr>
                            <th width="50px">No</th>
                            <th>Status</th>
                            {{-- <th width="150px">Option</th> --}}
                            <th width="200px">Requestor</th>
                            <th>Ticket</th>
                            <th>Support</th>
                            <th>Attachment</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="guideModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>GUIDE</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="" alt="Ticket Image" id="zoomable-image">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="finishModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Finish Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure want to finish this item?
                    <input type="text" readonly class="form-control-plaintext" id="no_reg_finish">
                    <input type="hidden" id="id_finish">
                    <div class="form-floating form-floating-outline">
                        <textarea class="form-control auto-resize" id="it_note_finish" name="it_note_finish"
                            placeholder="add note if there are additional" required>{{ old('it_note_finish') }}</textarea>
                        <label for="it_note_finish">Solution <span class="text-danger">*</span></label>
                    </div>
                    <label class="col-sm-6 col-form-label" for="notifikasi_finish">
                        <small class="text-light fw-medium d-block">Kirim Notifikasi Whatsapp?</small>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="notifikasi_finish" name="notifikasi_finish"
                                {{ old('notifikasi_finish') ? 'checked' : '' }} checked />
                            <label class="form-check-label" for="notifikasi_finish">(Tidak/Ya)</label>
                        </div>
                    </label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="btn-finish">Yes, Finish!</button>
                </div>
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
                        <textarea class="form-control auto-resize" id="it_note_approve" name="it_note_approve"
                            placeholder="add note if there are additional">{{ old('it_note_approve') }}</textarea>
                        <label for="it_note_approve">ITD Note</label>
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
                        <textarea class="form-control auto-resize" id="it_note_reject" name="it_note_reject"
                            placeholder="add note if there are additional">{{ old('it_note_reject') }}</textarea>
                        <label for="it_note_reject">ITD Note</label>
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
    <link rel="stylesheet" href="{{ asset('vendor/viewer/viewer.min.css') }}">

    <style>
        .detail_case_container {
            max-width: 200px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            box-sizing: border-box;
        }
    </style>

    <style>
        #zoomable-image {
            max-width: 100%;
            max-height: 100%;
            cursor: pointer;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('vendor/viewer/viewer.min.js') }}"></script>
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
                    url: "{{ route('website.ticket.list_ajax') }}",
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
                        data: null,
                        render: function(data, type, row, meta) {
                            let text = '';
                            let className = '';

                            switch (row.final_status) {
                                case 'created':
                                    text = 'Waiting for ITD to receive';
                                    className = 'alert-warning';
                                    break;
                                case 'IT Approve':
                                    text = 'Accepted by ITD, On Progress';
                                    className = 'alert-info';
                                    break;
                                case 'Pending':
                                    text = 'Pending';
                                    className = 'alert-info';
                                    break;
                                case 'Finished':
                                    text = 'Finished';
                                    className = 'alert-success';
                                    break;
                                default:
                                    text = row.final_status;
                                    className = 'alert-danger';
                            }

                            let buttons = '';
                            if (data.final_status == 'created') {
                                buttons = `
                                    <div>
                                        <button class="btn btn-danger btn-sm btn-table-reject" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="${data.id}" data-no_reg="${data.no_reg}">Reject</button>
                                        <button class="btn btn-success btn-sm btn-table-approve" data-bs-toggle="modal" data-bs-target="#approveModal" data-id="${data.id}" data-no_reg="${data.no_reg}">Approve</button>
                                    </div>
                                `;
                            } else if (data.final_status == 'IT Approve' || data.final_status ==
                                'Pending') {
                                buttons = `
                                    <div>
                                        <button class="btn btn-warning btn-sm btn-table-progress" data-bs-toggle="modal" data-bs-target="#progressModal" data-id="${data.id}" data-no_reg="${data.no_reg}">Pending</button>
                                        <button class="btn btn-success btn-sm btn-table-finish" data-bs-toggle="modal" data-bs-target="#finishModal" data-id="${data.id}" data-no_reg="${data.no_reg}">Finish</button>
                                    </div>
                                `;
                            } else {
                                buttons = `<div></div>`;
                            }

                            return `
                                <div>
                                    <button class="alert ${className}">${text}</button>
                                </div>
                                ${buttons}
                            `;
                        },
                        orderable: false,
                        searchable: false
                    },
                    // {
                    //     orderable: false,
                    //     searchable: false,
                    //     data: null,
                    //     render: function(data, type, row, meta) {
                    //         if (data.final_status == 'created') {
                    //             return `
                //             <center>
                //                 <button class="btn btn-danger btn-sm btn-table-reject" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="${data.id}" data-no_reg="${data.no_reg}">Reject</button>
                //                 <button class="btn btn-success btn-sm btn-table-approve" data-bs-toggle="modal" data-bs-target="#approveModal" data-id="${data.id}" data-no_reg="${data.no_reg}">Approve</button>
                //             </center>
                //             `;
                    //         } else if (data.final_status == 'IT Approve' || data.final_status ==
                    //             'Pending') {
                    //             return `
                //             <center>
                //                 <button class="btn btn-warning btn-sm btn-table-progress" data-bs-toggle="modal" data-bs-target="#progressModal" data-id="${data.id}" data-no_reg="${data.no_reg}">Pending</button>
                //                 <button class="btn btn-success btn-sm btn-table-finish" data-bs-toggle="modal" data-bs-target="#finishModal" data-id="${data.id}" data-no_reg="${data.no_reg}">Finish</button>
                //             </center>
                //             `;
                    //         } else {
                    //             return `<center>-</center>`;
                    //         }
                    //     }
                    // },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return `
                            <div class="label-container">
                                <div class="mb-1">
                                    <b>Name : </b><br>
                                    <span class="detail_case_container">${row.requestor_name}</span>
                                </div>
                                    <b>Phone : </b><br>
                                    <span class="detail_case_container">${row.requestor_phone}</span>
                                </div>
                                </div>
                                    <b>Department : </b><br>
                                    <span class="detail_case_container">${row.requestor_department}</span>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            // Format tanggal 'created_at' ke 'YYYY-MM-DD HH:mm'
                            let created_at = row.created_at ? moment(row.created_at).format(
                                'YYYY-MM-DD HH:mm') : '';

                            return `
                            <div>
                                <div class="mb-3">
                                    <span class="alert alert-info p-1">${row.no_reg}</span>
                                </div>
                                <div class="mb-3">
                                    <b>Category : </b><br>
                                    <span class="detail_case_container">${row.category}</span>
                                </div>
                                <div class="mb-3">
                                    <b>Detail Problem : </b><br>
                                    <span class="detail_case_container">${row.detail_case}</span>
                                </div>
                                <div>
                                    <b>Location&emsp;: </b>${row.location}
                                </div>
                                <div class="mb-3">
                                    <b>Priority&emsp;: </b>${row.sla}
                                </div>
                                <div class="mb-3">
                                    <b>Solution&emsp;: </b><br>
                                    <span class="detail_case_container">${row.it_name ? (row.solution ? row.solution : '-') : '<span class="badge btn-primary">Tunggu Approve</span>'}</span>
                                </div>
                                <div>
                                    <b>Reported Date&emsp;: </b>${created_at}
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return `
                            <div>
                                <div class="mb-1">
                                    <b>PIC&emsp;: </b>${row.it_name ? row.it_name : '<span class="badge btn-primary">Tunggu Approve</span>'}
                                </div>
                                <div class="mb-1">
                                    <b>Phone&emsp;: </b>${row.it_phone ? row.it_phone : '<span class="badge btn-primary">Tunggu Approve</span>'}
                                </div>
                                <div class="mb-3">
                                    <b>IT Note&emsp;: </b><br>
                                    <span class="detail_case_container">${row.it_name ? (row.it_note ? row.it_note : '-') : '<span class="badge btn-primary">Tunggu Approve</span>'}</span>
                                </div>
                                
                                <div class="mb-3">
                                    <b>Accepted Date&emsp;: </b>${row.it_approval_date ? row.it_approval_date : '<span class="badge btn-primary">Tunggu Approve</span>'}
                                </div>

                                <div>
                                    <b>Pending Date&emsp;: </b>${row.on_progress_date ? row.on_progress_date : '-'}
                                </div>
                                <div class="mb-3">
                                    <b>Pending Reason&emsp;: </b>${row.on_progress_note ? row.on_progress_note : '-'}
                                </div>

                                <div>
                                    <b>Finish Date&emsp;: </b>${row.finish_date ? row.finish_date : '-'}
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            let path = row.ticket_photos.length > 0 ? row.ticket_photos[0].path :
                                'No photo available';

                            // Periksa apakah ada path, jika tidak ada tampilkan teks default
                            if (path !== 'No photo available') {
                                return `
                                <div>
                                    <button type="button" class="btn btn-primary btn-sm btn-table-view" data-bs-toggle="modal" data-bs-target="#guideModal" data-path=${path}>
                                        <i class="menu-icon tf-icons mdi mdi-image"></i>View
                                    </button>
                                </div>`;
                            } else {
                                return `
                                <div>
                                    <span>${path}</span>
                                </div>`;
                            }
                        }
                    },

                ],
            });

            $('#app_table').on('click', '.btn-table-view', function() {
                var path_view = '/storage/lampiran/' + $(this).data('path');

                // Set the image source
                $('#zoomable-image').attr('src', path_view);

                // Initialize Viewer.js after setting the image source
                var image = document.getElementById('zoomable-image');
                if (image.viewer) {
                    image.viewer.destroy(); // Destroy previous instance if it exists
                }
                var viewer = new Viewer(image, {
                    zoomable: true,
                    scalable: true,
                    rotatable: false,
                    transition: false,
                    toolbar: true,
                });

                // Show the modal
                $('#guideModal').modal('show');
            });

            // FINISH
            $('#app_table').on('click', '.btn-table-finish', function() {
                var id_finish = $(this).data('id');
                var no_reg_finish = $(this).data('no_reg');
                var finishButton = document.getElementById('btn-finish');

                finishButton.removeAttribute('disabled');
                finishButton.innerHTML = 'Yes, Finish!';
                $('#id_finish').val(id_finish)
                $('#no_reg_finish').val(no_reg_finish)
                $('#it_note_finish').val('');
            })

            $('#btn-finish').on('click', function() {
                let id_finish = $('#id_finish').val();
                let notifikasi_finish = $('#notifikasi_finish').is(':checked') ? 'Ya' :
                    'Tidak';
                $.ajax({
                    url: "{{ route('website.ticket.it_approve') }}",
                    type: "POST",
                    data: {
                        id: id_finish,
                        finish_note: $('#it_note_finish').val(),
                        notifikasi: notifikasi_finish,
                        type: 'finish',
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response)
                        table.ajax.reload();
                        getApprovalCount();
                        $('#finishModal').modal('hide')
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                    }
                });
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
                $('#it_note_approve').val('');
            })

            $('#btn-approve').on('click', function() {
                let id_approve = $('#id_approve').val();
                let notifikasi_approve = $('#notifikasi_approve').is(':checked') ? 'Ya' :
                    'Tidak';
                $.ajax({
                    url: "{{ route('website.ticket.it_approve') }}",
                    type: "POST",
                    data: {
                        id: id_approve,
                        it_note: $('#it_note_approve').val(),
                        notifikasi: notifikasi_approve,
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
            // ON PROGRESS
            $('#on_progress_note_progress').on('keyup', function() {
                if ($(this).val() != "")
                    $('#btn-progress').removeAttr('disabled');
                else
                    $('#btn-progress').attr('disabled', 'disabled');
            });

            $('#app_table').on('click', '.btn-table-progress', function() {
                var id_progress = $(this).data('id');
                var no_reg_progress = $(this).data('no_reg');
                var approveButton = document.getElementById('btn-progress');

                approveButton.innerHTML = 'Yes, Progress!';
                $('#id_progress').val(id_progress)
                $('#no_reg_progress').val(no_reg_progress)
                $('#on_progress_note_progress').val('');
            })

            $('#btn-progress').on('click', function() {
                let id_progress = $('#id_progress').val();
                $.ajax({
                    url: "{{ route('website.ticket.it_approve') }}",
                    type: "POST",
                    data: {
                        id: id_progress,
                        on_progress_note: $('#on_progress_note_progress').val(),
                        type: 'progress',
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response)
                        table.ajax.reload();
                        getApprovalCount();
                        $('#progressModal').modal('hide')
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                    }
                });
            });
            // REJECT
            $('#it_note_reject').on('keyup', function() {
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
                $('#it_note_reject').val('');
            })

            $('#btn-reject').on('click', function() {
                let id_reject = $('#id_reject').val();
                $.ajax({
                    url: "{{ route('website.ticket.it_approve') }}",
                    type: "POST",
                    data: {
                        id: id_reject,
                        it_note: $('#it_note_reject').val(),
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var finishButton = document.getElementById('btn-finish');
            var spinner = '<i class="mdi mdi-loading spin"></i>';

            finishButton.addEventListener('click', function() {
                finishButton.setAttribute('disabled', 'true');
                finishButton.innerHTML = spinner + ' Finishing...';
            });
        });
    </script>
@endpush
