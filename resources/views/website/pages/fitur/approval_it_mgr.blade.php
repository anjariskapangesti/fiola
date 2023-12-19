@extends('website.layouts.main', ['title' => 'ITD Manager Approval Request Fitur'])

@section('content')
    <div class="pagetitle">
        <h4>Request Fitur for Application</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">ITD Manager Approval</a></li>
                <li class="breadcrumb-item active"><a href="#">Form Request Fitur</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body p-3 table table-responsive">
                    {{-- <a href="{{ route('website.fitur.show_data_it_mgr_approval') }}" class="btn btn-primary">Show Data</a> --}}
                    <table class="display" width="100%" id="app_table">
                        <thead>
                            <tr>
                                <th>Detail</th>
                                <th>Nama Aplikasi</th>
                                <th>Nama Fitur</th>
                                <th>Nama Requestor</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!-- Approve Confirmation Modal -->

        <div class="modal fade" id="confirmModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Approve Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure want to approve this request?
                        <input type="text" readonly class="form-control-plaintext" id="fullname_form_fitur">
                        <input type="hidden" id="id_form_fitur">

                        <textarea class="form-control" id="note" placeholder="add note if there are additional"></textarea>

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
                        <input type="hidden" id="id_form_fitur_reject">
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
    <script>
        function format(d) {
            // `d` is the original data object for the row
            return (
                `
                <table class="table table-sm">
                    <tr>
                        <td width="30%">NPK / Full Name</td>
                        <td>${d.npk} / ${d.fullname} </td>
                    </tr>
                    <tr>
                        <td>Dept.</td>
                        <td>${d.department} </td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td>${d.phone} </td>
                    </tr>
                    <tr>
                        <td>Nama Aplikasi</td>
                        <td>${d.aplikasi ?? '-'} </td>
                    </tr>  
                    <tr>
                        <td>Nama Fitur</td>
                        <td>${d.nama_fitur ?? '-'} </td>
                    </tr>  
                    <tr>
                        <td>Lampiran</td>
                        <td>
                            ${d.lampiran ? `<a href="/storage/lampiran/${d.lampiran}" class="btn btn-success" target="_blank"><i class="fas fa-download"></i> Lampiran</a>` : 'Tidak ada lampiran'}
                        </td>
                    </tr>
                    <tr>
                        <td>Kondisi Sebelum Improvement</td>
                        <td>${d.kondisi_sebelum ?? '-'}</td>
                    </tr>
                    <tr>
                        <td>Kondisi yang diharapkan</td>
                        <td>${d.kondisi_target ?? '-'}</td>
                    </tr>
                    <tr>
                        <td>Benefit yang didapat</td>
                        <td>${d.benefit ?? '-'}</td>
                    </tr>
                    <tr>
                        <td>Manager Note</td>
                        <td>${d.manager_note ?? '-'}</td>
                    </tr>
                    <tr>
                        <td>ITD Note</td>
                        <td>${d.it_note ?? '-'}</td>
                    </tr>
                    <tfoot>
                    <tr>
                        <th>Dibuat oleh</th>
                        <th>${d.user_name}</th>
                    </tr>
                    </tfoot>
                </table>
                `
            );
        }

        $(document).ready(function() {
            var table = $('#app_table').DataTable({
                "lengthChange": true,
                'processing': true,
                'serverSide': true,
                ajax: {
                    url: "{{ route('website.fitur.show_it_mgr_approval_ajax') }}",
                },
                columns: [{
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: '',
                        searchable: false,
                    },
                    {
                        data: 'aplikasi',
                        name: 'aplikasi',
                    },
                    {
                        data: 'nama_fitur',
                        name: 'nama_fitur'
                    },
                    {
                        data: 'fullname',
                        name: 'fullname'
                    },
                    {
                        orderable: false,
                        searchable: false,
                        data: null,
                        render: function(data, type, row, meta) {
                            return `
                            <button class="btn btn-success btn-sm btn-table-approve" data-bs-toggle="modal" data-bs-target="#confirmModal" data-id="${data.id}" data-fullname="${data.fullname}">Approve</button>
                            <button class="btn btn-danger btn-sm btn-table-reject" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="${data.id}" data-fullname="${data.fullname}">Reject</button>`;
                        }
                    },
                ],
            });

            $('#app_table tbody').on('click', 'td.dt-control', function() {
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

            $('#reject_reason').on('keyup', function() {
                if ($(this).val() != "")
                    $('#btn-reject').removeAttr('disabled');
                else
                    $('#btn-reject').attr('disabled', 'disabled');
            });

            $('#btn-approve').on('click', function() {
                let btnApprove = $(this);
                let id_form_fitur = $('#id_form_fitur').val();
                $.ajax({
                    url: "{{ route('website.fitur.approve_it_mgr') }}",
                    type: "POST",
                    data: {
                        id: id_form_fitur,
                        type: 'ok',
                        it_mgr_note: $('#note').val(),
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response)
                        table.ajax.reload();
                        btnApprove.prop('disabled', false);
                        getApprovalCount();
                        $('#confirmModal').modal('hide')
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                    }
                });
            });

            $('#btn-reject').on('click', function() {
                let id_form_fitur_reject = $('#id_form_fitur_reject').val();
                $.ajax({
                    url: "{{ route('website.fitur.approve_it_mgr') }}",
                    type: "POST",
                    data: {
                        id: id_form_fitur_reject,
                        type: 'reject',
                        it_mgr_note: $('#reject_reason').val(),
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {

                        toastr['success'](response)
                        table.ajax.reload();
                        $('#rejectModal').modal('hide')
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                    }
                });
            });

            $('#app_table').on('click', '.btn-table-approve', function() {
                var id_form_fitur = $(this).data('id');
                var fullname_form_fitur = $(this).data('fullname');
                $('#id_form_fitur').val(id_form_fitur)
                $('#fullname_form_fitur').val(fullname_form_fitur)
            })

            $('#app_table').on('click', '.btn-table-reject', function() {
                var id_form_fitur_reject = $(this).data('id');
                $('#id_form_fitur_reject').val(id_form_fitur_reject)
            })

        });
    </script>
    <script>
        const btnApprove = document.getElementById('btn-approve');

        btnApprove.addEventListener('click', function() {
            btnApprove.disabled = true;
        });
    </script>
@endpush
