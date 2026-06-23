@extends('website.layouts.main', ['title' => 'Track Forms New Folder'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h5 class="card-header">File Server Folder Add/Change/Delete Form (FRM-ITD-S13-003-00)</h5>
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
    {{-- CONFIRM MODAL --}}
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
    {{-- DELETE MODAL --}}
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
                    url: "{{ route('website.new-folder.list_ajax') }}",
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
                            const status = (data ?? '').toString();
                            const statusLower = status.toLowerCase();

                            if (status == 'created') {
                                return `<span class="badge bg-warning">Waiting Manager Approval</span>`;
                            } else if (statusLower.includes('reject') || statusLower.includes('rejected') || statusLower.includes('not accepted') || statusLower.includes('tidak diterima')) {
                                return `<span class="badge bg-danger">${status}</span>`;
                            } else if (statusLower.includes('waiting') || statusLower.includes('pending')) {
                                return `<span class="badge bg-warning">${status}</span>`;
                            } else if (statusLower.includes('approve') || statusLower.includes('approved') || statusLower.includes('finished') || statusLower.includes('on progress') || statusLower.includes('done') || statusLower.includes('confirmed')) {
                                return `<span class="badge bg-success">${status}</span>`;
                            } else {
                                return `<span class="badge bg-danger">${status}</span>`;
                            }
                        }
                    },
                    {
                        orderable: false,
                        searchable: false,
                        data: null,
                        render: function(data, type, row, meta) {
                            if (data.is_confirm == '0' && data.created_by ==
                                '{{ Auth::user()->id }}') {
                                return `
                                <center>
                                    <button class="btn btn-success btn-sm btn-table-confirm" data-bs-toggle="modal" data-bs-target="#confirmModal" data-id="${data.id}" data-no_reg="${data.no_reg}">Confirm</button>
                                </center>
                                `;
                            } else if (data.is_confirm == '1') {
                                return `<center><span class="badge bg-success">Confrimed</span></center>`
                            } else if (data.final_status == 'created' && data.created_by ==
                                '{{ Auth::user()->id }}') {
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
                var
                    html = `
                    <table class="table table-sm table-bordered">
                        <tbody style="background-color: #66a7e3; width: 30px; font-weight: bold; border: 2px solid black;">
                            <tr>
                                <td colspan="1" class="text-center">New Folder Name</td>    
                                <td colspan="2" class="text-center">Main Path</td>    
                            </tr>    
                        `

                for (let i = 0; i < d.form_new_folder_path.length; i++) {
                    html += `
                            <tr style="background-color: #ebf1f2;">
                                <td colspan="1">${d.form_new_folder_path[i].foldername}</td>
                                <td colspan="2">${d.form_new_folder_path[i].mainpath}</td>
                                `
                    html += `</tr>
                    `
                }

                html += `<tr style="background-color: #66a7e3; width: 30px; font-weight: bold;">
                            <td> Username </td>
                            <td> Department </td>
                            <td> Permission </td>
                        </tr>
                        `
                for (let i = 0; i < d.form_new_folder_user.length; i++) {
                    html += `<tr style="background-color: #ebf1f2;">
                                    <td>${d.form_new_folder_user[i].username}</td>
                                    <td>${d.form_new_folder_user[i].department}</td>
                                    <td>${d.form_new_folder_user[i].permission ?? '-'}</td>
                                    `
                    html += `</tr>
                            
                    `
                }

                html += `
                            <tr>
                                <td colspan="4" class="text-center">Purpose</td>    
                            </tr>
                            <tr style="background-color: #ebf1f2; max-width: 250px; white-space: pre-wrap;">
                                <td colspan="4" >${d.purpose}</td>   
                            </tr>
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>Manager Approval Date</td>
                                <td colspan="3">${d.manager_approval_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Manager Approval By</td>
                                <td colspan="3">${d.manager_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Manager Note</td>
                                <td colspan="3" style="max-width: 250px; white-space: pre-wrap;">${d.manager_note ?? '-'}</td>
                            </tr>
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>ITD Approval Date</td>
                                <td colspan="3">${d.it_approval_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Approval By</td>
                                <td colspan="3">${d.it_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Note</td>
                                <td colspan="3" style="max-width: 250px; white-space: pre-wrap;">${d.it_note ?? '-'}</td>
                            </tr>  
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>ITD Manager Approval Date</td>
                                <td colspan="3">${d.it_mgr_approval_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Manager Approval By</td>
                                <td colspan="3">${d.it_mgr_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Manager Note</td>
                                <td colspan="3" style="max-width: 250px; white-space: pre-wrap;">${d.it_mgr_note ?? '-'}</td>
                            </tr>
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>On Progress Date</td>
                                <td colspan="3">${d.on_progress_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>On Progress By</td>
                                <td colspan="3">${d.on_progress_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>On Progress Note</td>
                                <td colspan="3" style="max-width: 250px; white-space: pre-wrap;">${d.on_progress_note ?? '-'}</td>
                            </tr>
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>Finish Date</td>
                                <td colspan="3">${d.finish_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Finish By</td>
                                <td colspan="3">${d.finish_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Finish Note</td>
                                <td colspan="3" style="max-width: 250px; white-space: pre-wrap;">${d.finish_note ?? '-'}</td>
                            </tr>
                        </tbody>   
                        </table>`

                return html
            }

            // DELETE MODAL
            $('#app_table').on('click', '.btn-table-delete', function() {
                var id_delete = $(this).data('id');
                var no_reg_delete = $(this).data('no_reg');

                $('#id_delete').val(id_delete)
                $('#no_reg_delete').val(no_reg_delete)
            })

            $('#btn-delete').on('click', function() {
                let id_delete = $('#id_delete').val();
                $.ajax({
                    url: "{{ route('website.new-folder.delete_form') }}",
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
            // END DELETE MODAL

            // CONFIRM MODAL
            $('#app_table').on('click', '.btn-table-confirm', function() {
                var id_confirm = $(this).data('id');
                var no_reg_confirm = $(this).data('no_reg');
                var confirmButton = document.getElementById('btn-confirm');

                confirmButton.removeAttribute('disabled');
                confirmButton.innerHTML = 'Yes, Confirm!';
                $('#id_confirm').val(id_confirm)
                $('#no_reg_confirm').val(no_reg_confirm)
                $('#it_mgr_note_confirm').val('');
            })

            $('#btn-confirm').on('click', function() {
                let id_confirm = $('#id_confirm').val();
                $.ajax({
                    url: "{{ route('website.new-folder.approve_form') }}",
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
            // END CONFIRM MODAL
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
