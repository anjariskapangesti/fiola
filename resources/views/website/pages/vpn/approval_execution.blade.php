@extends('website.layouts.main', ['title' => 'Execution VPN'])

@section('content')
    <div class="pagetitle">
        <h4>PERMIT TO USE VIRTUAL PRIVATE NETWORK (VPN) (FRM-ITD-S13-035-00)</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Execution</a></li>
                <li class="breadcrumb-item active"><a href="#">Form VPN</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body p-3 table table-responsive">
                    {{-- <a href="{{ route('website.vpn.show_data_execution') }}" class="btn btn-primary">Show Data</a> --}}
                    <table class="display" width="100%" id="app_table">
                        <thead>
                            <tr>
                                <th>Detail</th>
                                <th>Fullname</th>
                                <th>Username</th>
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
                        <input type="text" readonly class="form-control-plaintext" id="fullname_form_vpn">
                        <input type="hidden" id="id_form_vpn">

                        <label for="note">Note :</label>
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
                        <input type="hidden" id="id_form_vpn_reject">
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
                        <td>Email</td>
                        <td>${d.email ?? '-'} </td>
                    </tr>
                    <tr>
                        <td>Username</td>
                        <td>${d.username ?? '-'} </td>
                    </tr>  
                    <tr>
                        <td>Manager Note</td>
                        <td>${d.manager_note ?? '-'}</td>
                    </tr>
                    <tr>
                        <td>ITD Note</td>
                        <td>${d.it_note ?? '-'}</td>
                    </tr>  
                    <tr>
                        <td>ITD Manager Note</td>
                        <td>${d.it_mgr_note ?? '-'}</td>
                    </tr>

                    <tfoot>
                    <tr>
                        <th>Created by</th>
                        <th>${d.user_name}</th>
                    </tr>
                    <tr>
                        <th>Purpose</th>
                        <th>${d.purpose}</th>
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
                    url: "{{ route('website.vpn.show_execution_ajax') }}",
                },
                columns: [{
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: '',
                        searchable: false,
                    },
                    {
                        data: 'fullname',
                        name: 'fullname',
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        orderable: false,
                        searchable: false,
                        data: null,
                        render: function(data, type, row, meta) {
                            return `
                            <button class="btn btn-success btn-sm btn-table-approve" data-bs-toggle="modal" data-bs-target="#confirmModal" data-id="${data.id}" data-fullname="${data.fullname}" data-ad_name="${data.ad_name}">Approve</button>
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
                let id_form_vpn = $('#id_form_vpn').val();
                $.ajax({
                    url: "{{ route('website.vpn.approve_execution') }}",
                    type: "POST",
                    data: {
                        id: id_form_vpn,
                        type: 'ok',
                        ad_name: $('#ad_name').val(),
                        finish_note: $('#note').val(),
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
                let id_form_vpn_reject = $('#id_form_vpn_reject').val();
                console.log(id_form_vpn_reject);
                // window.location.href = "{{ route('website.vpn.approve_it') }}";
                $.ajax({
                    url: "{{ route('website.vpn.approve_execution') }}",
                    type: "POST",
                    data: {
                        id: id_form_vpn_reject,
                        type: 'reject',                        
                        finish_note: $('#reject_reason').val(),
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

            // $('#confirmModal').on('shown.bs.modal', function() {
            //     $('#nama').text('Nama Requestor')
            // });

            $('#app_table').on('click', '.btn-table-approve', function() {
                var id_form_vpn = $(this).data('id');
                var fullname_form_vpn = $(this).data('fullname');
                var ad_name = $(this).data('ad_name');

                $('#id_form_vpn').val(id_form_vpn)
                $('#fullname_form_vpn').val(fullname_form_vpn)
                $('#ad_name').val(ad_name)
                // console.log(id_form_vpn);
            })

            $('#app_table').on('click', '.btn-table-reject', function() {
                var id_form_vpn_reject = $(this).data('id');
                $('#id_form_vpn_reject').val(id_form_vpn_reject)
                // console.log(id_form_vpn_reject);
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
