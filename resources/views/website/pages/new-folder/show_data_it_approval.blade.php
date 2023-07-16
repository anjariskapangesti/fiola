@extends('website.layouts.main', ['title' => 'ITD History New Folder'])

@section('content')
    <div class="pagetitle">
        <h4>File Server Folder Add/Change/Delete Form (FRM-ITD-S13-003-00)</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">ITD History</a></li>
                <li class="breadcrumb-item active"><a href="#">Form New Folder</a></li>
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
                                <th>Detail</th>
                                <th>New Folder Name</th>
                                <th>Main Path</th>
                                <th>Date Approved</th>
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
                        <input type="text" readonly class="form-control-plaintext" id="username_new_folder">
                        <input type="hidden" id="id_new_folder">
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
                        <input type="hidden" id="id_new_folder_reject">
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
                bLengthChange: true,
                processing: true,
                ordering: true,
                serverSide: true,
                ajax: {
                    'url': "{{ route('website.new-folder.show_data_it_approval_ajax') }}",
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
                        data: 'foldername',
                        name: 'foldername',
                    },
                    {
                        data: 'mainpath',
                        name: 'mainpath',
                    },
                    {
                        data: 'it_approval_date',
                        name: 'it_approval_date',
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
                    <table class = "table table-sms">
                                                <tr class = "bg-light">
                                                <td> Username </td>
                                                <td> Department </td>
                                                <td> Permission </td>
                                                </tr>
                                                `
                console.log(d)
                for (let i = 0; i < d.form_new_folder_access.length; i++) {
                    html += `<tr>
                                    <td>${d.form_new_folder_access[i].username}</td>
                                    <td>${d.form_new_folder_access[i].department}</td>
                                    <td>${d.form_new_folder_access[i].permission}</td>`
                    html += `</tr>
                    `
                }

                html += `
                        <tfoot>
                            <tr>
                                <th>Purpose</th>
                                <th colspan="2">${d.purpose}</th>
                            </tr>
                            <tr>
                                <th>Created by</th>
                                <th colspan="2">${d.creator_created_by}</th>
                            </tr>
                        </tfoot>                      
                        </table>`

                return html
            }



            // $('#confirmModal').on('shown.bs.modal', function() {
            //     $('#nama').text('Nama Requestor')
            // });

            $('.table').on('click', '.btn-table-approve', function() {
                var id_new_folder = $(this).data('id');
                var username_new_folder = $(this).data('username');
                $('#id_new_folder').val(id_new_folder)
                $('#username_new_folder').val(username_new_folder)
                // console.log(id_new_folder);
            })

            $('.table').on('click', '.btn-table-reject', function() {
                var id_new_folder_reject = $(this).data('id');
                $('#id_new_folder_reject').val(id_new_folder_reject)
                // console.log(id_new_folder_reject);
            })

        })
    </script>
@endpush
