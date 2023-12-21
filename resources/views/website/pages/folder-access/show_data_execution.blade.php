@extends('website.layouts.main', ['title' => 'Finished Folder Access'])

@section('content')
    <div class="pagetitle">
        <h4>Change Access of Folder Share Application (FRM-ITD-S13-009-00)</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Finished</a></li>
                <li class="breadcrumb-item active"><a href="#">Form Folder Access</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body p-3 table-responsive">
                    <table class="table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th style="max-width: 50px;">Detail</th>
                                <th style="max-width: 100px;">No. Reg</th>
                                <th>Creator</th>
                                <th>Final Status</th>
                                <th>Date Execution</th>
                            </tr>
                        </thead>
                        <tbody></tbody>

                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="confirmModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Approve Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure want to approve this request?
                        <input type="text" readonly class="form-control-plaintext" id="username_folder_access">
                        <input type="hidden" id="id_folder_access">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="btn-approve">Yes, Approve!</button>
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
        $(function() {
            var table = $('.table').DataTable({
                processing: true,
                ordering: true,
                serverSide: true,
                ajax: {
                    'url': "{{ route('website.folder-access.show_data_execution_ajax') }}",
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
                        name: 'final_status'
                    },                    
                    {
                        data: 'finish_date',
                        name: 'finish_date',
                    },
                ]

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

                        <tfoot>
                            <tr style="background-color: #ebf1f2;"style="background-color: #ebf1f2;">
                                <th>Purpose</th>
                                <th colspan="3">${d.purpose}</th>
                            </tr>
                            <tr style="background-color: #ebf1f2;">
                                <th>Created by</th>
                                <th colspan="3">${d.creator_created_by}</th>
                            </tr>
                        </tfoot>     

                        </table>`

                return html
            }

        })
    </script>
@endpush
