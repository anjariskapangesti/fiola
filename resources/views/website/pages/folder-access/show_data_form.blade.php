@extends('website.layouts.main', ['title' => 'Track Forms Folder Access'])

@section('content')
    <div class="pagetitle">
        <h4>Change Access of Folder Share Application (FRM-ITD-S13-009-00)</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Track Forms</a></li>
                <li class="breadcrumb-item active"><a href="#">Form Folder Access</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="row">
        @if (Session::get('info'))
            <div class="alert alert-info">
                {{ Session::get('info') }}
            </div>
        @endif
    </div>
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body p-3 table-responsive">
                    <table class="table table-striped" width="100%" id="app_table">
                        <thead>
                            <tr>
                                <th style="max-width: 50px;">Detail</th>
                                <th style="max-width: 100px;">No. Reg</th>
                                <th>Creator</th>
                                <th>Status</th>
                                <th>Confirm</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="confirmModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure want to confirm this request?
                        <input type="hidden" id="id_form_folder_access">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="btn-approve">Yes, Confirm!</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Confirmation Modal -->
        <!-- Confirmation Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Please share the reason why you're rejecting<br /><br />
                        <textarea class="form-control" id="reject_reason"></textarea>
                        <input type="hidden" id="id_folder_access_reject">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="btn-reject" class="btn btn-danger" disabled
                            id="btn-reject">Reject!</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Confirmation Modal -->
    </section>
@endsection

@push('styles')
    <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script lang="text/javascript">
        $(document).ready(function() {
            @if (session()->has('success'))
                toastr['success']("{{ Session('success') }}")
            @endif
        })

        $(function() {
            var table = $('#app_table').DataTable({
                processing: true,
                ordering: true,
                serverSide: true,
                ajax: {
                    'url': "{{ route('website.folder-access.show_data_form_ajax') }}",
                },
                columns: [{
                        data: null,
                        className: 'dt-control',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return ''
                        },
                    },
                    {
                        data: 'no_reg',
                        name: 'no_reg',
                    },
                    {
                        data: 'creator_created_by',
                        name: 'creator_created_by',
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
                                return `<button class="btn btn-success btn-sm btn-table-approve" data-bs-toggle="modal" data-bs-target="#confirmModal" data-id="${data.id}" data-fullname="${data.fullname}">Confirm</button>
                                `;
                            } else if (data.is_confirm == '1') {
                                return `Confirmed`
                            } else {
                                return `Not yet`;
                            }
                        }
                    },
                ]

            })

            $('#btn-approve').on('click', function() {
                let id_form_folder_access = $('#id_form_folder_access').val();
                console.log(id_form_folder_access);
                $.ajax({
                    url: "{{ route('website.folder-access.approve_form') }}",
                    type: "POST",
                    data: {
                        id: id_form_folder_access,
                        type: 'ok',
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response)
                        table.ajax.reload();
                        $('#confirmModal').modal('hide')
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                    }
                });
            });

            $('#app_table').on('click', '.btn-table-approve', function() {
                var id_form_folder_access = $(this).data('id');
                var fullname_form_folder_access = $(this).data('fullname');

                $('#id_form_folder_access').val(id_form_folder_access)
                $('#fullname_form_folder_access').val(fullname_form_folder_access)
            })

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
                    <table class = "table table-sm table-bordered">
                        <thead style="background-color: #66a7e3;">
                            <tr>
                                <th colspan="2" class="text-center">Email</th>    
                                <th colspan="2" class="text-center">Department</th>    
                            </tr>    
                        </thead>`

                        for (let i = 0; i < d.form_folder_access_user.length; i++) {
                    html += `<tr style="background-color: #ebf1f2;">
                                <td colspan="2">${d.form_folder_access_user[i].username}</td>
                                <td colspan="2">${d.form_folder_access_user[i].department}</td>
                                `
                    html += `</tr>
                    `
                }
                        
                        html += `<tr style="background-color: #c9d2d4;">
                            <th> Main Path </th>
                            <th> Folder </th>
                            <th> Subfolder </th>
                            <th> Permission </th>
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
                            <td>Manager Note</td>
                            <td colspan="3">${d.manager_note ?? '-'}</td>
                        </tr>
                        <tr>
                            <td>ITD Note</td>
                            <td colspan="3">${d.it_note ?? '-'}</td>
                        </tr>  
                        <tr>
                            <td>ITD Manager Note</td>
                            <td colspan="3">${d.it_mgr_note ?? '-'}</td>
                        </tr>
                        <tr>
                            <td>Note</td>
                            <td colspan="3">${d.finish_note ?? '-'}</td>
                        </tr>

                        <tfoot style="background-color: #ebf1f2;">
                            <tr>
                                <th>Purpose</th>
                                <th colspan="3">${d.purpose}</th>
                            </tr>
                            <tr>
                                <th>Created by</th>
                                <th colspan="3">${d.creator_created_by}</th>
                            </tr>
                            <tr>
                                <th>IT Approved by</th>
                                <th colspan="3">${d.it_approve_by_name  ?? '-'}</th>
                            </tr>
                            <tr>
                                <th>Finish by</th>
                                <th colspan="3">${d.finish_by_name  ?? '-'}</th>
                            </tr>
                        </tfoot>     

                        </table>`

                return html
            }

        })
    </script>
@endpush
