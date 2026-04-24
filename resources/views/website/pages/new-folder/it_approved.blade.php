@extends('website.layouts.main', ['title' => 'IT Approved New Folder'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h5 class="card-header">File Server Folder Add/Change/Delete Form (FRM-ITD-S13-003-00)</h5>
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
                            <th style="max-width: 50px;">Detail</th>
                            <th>No. Reg</th>
                            <th>Requestor</th>
                            <th>Created Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    </tbody>
                </table>
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
                    url: "{{ route('website.new-folder.it_approved_ajax') }}",
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
        });
    </script>
@endpush
