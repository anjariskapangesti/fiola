@extends('website.layouts.main', ['title' => 'Finished Folder Access'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h5 class="card-header">Change Access of Folder Share Application (FRM-ITD-S13-009-00)</h5>
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
                            <th width="150px">Option</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    </tbody>
                </table>
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
                    url: "{{ route('website.folder-access.finished_ajax') }}",
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
                        orderable: false,
                        searchable: false,
                        data: null,
                        render: function(data, type, row, meta) {
                            if (data.is_confirm == '0') {
                                return `
                                <center>
                                    <span class="badge bg-warning">Not Yet Confirmed</span>
                                </center>
                                `;
                            } else if (data.is_confirm == '1') {
                                return `
                                <center>
                                    <span class="badge bg-success">Confirmed</span>
                                </center>
                                `
                            } else if (data.final_status == 'created') {
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
                var html = `
                    <table class="table table-sm table-bordered">
                        <tbody style="background-color: #66a7e3; width: 30px; font-weight: bold; border: 2px solid black;">
                            <tr>
                                <td colspan="2" class="text-center">Email</td>    
                                <td colspan="2" class="text-center">Department</td>    
                            </tr>    
                        `

                for (let i = 0; i < d.form_folder_access_user.length; i++) {
                    html += `
                            <tr style="background-color: #ebf1f2;">
                                <td colspan="2">${d.form_folder_access_user[i].username}</td>
                                <td colspan="2">${d.form_folder_access_user[i].department}</td>
                                `
                    html += `</tr>
                    `
                }

                html += `<tr style="background-color: #66a7e3; width: 30px; font-weight: bold;">
                            <td> Main Path </td>
                            <td> Folder </td>
                            <td> Subfolder </td>
                            <td> Permission </td>
                        </tr>
                        `
                for (let i = 0; i < d.form_folder_access_path.length; i++) {
                    html += `<tr style="background-color: #ebf1f2;">
                                    <td>${d.form_folder_access_path[i].folder}</td>
                                    <td>${d.form_folder_access_path[i].subfolder}</td>
                                    <td>${d.form_folder_access_path[i].subsubfolder ?? '-'}</td>
                                    <td>${d.form_folder_access_path[i].permission}</td>
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
