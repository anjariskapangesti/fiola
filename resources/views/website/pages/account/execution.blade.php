@extends('website.layouts.main', ['title' => 'Execution Account'])

@section('content')
    <div class="pagetitle">
        <h4>Account Registration/Change/Deletion Form (FRM-ITD-S13-001-00)</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Execution</a></li>
                <li class="breadcrumb-item active"><a href="#">Form Account</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body p-3 table table-responsive">
                    {{-- <a href="{{ route('website.account.show_data_execution') }}" class="btn btn-primary">Show Data</a> --}}
                    <table class="display" width="100%" id="app_table">
                        <thead>
                            <tr>
                                <th>Detail</th>
                                <th>Fullname</th>
                                <th>Budget Type</th>
                                <th>Request Type</th>
                                <th>Status</th>
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
                        <input type="text" readonly class="form-control-plaintext" id="fullname_form_account">
                        <input type="hidden" id="id_form_account">

                        <label for="ad_name">AD Name</label>
                        <input type="text" class="form-control" id="ad_name">

                        <label for="email_address">Email</label>
                        <input type="text" class="form-control" id="email_address">

                        <label for="note">Note :</label>
                        <textarea class="form-control" id="note" rows="20" cols="50"></textarea>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="btn-approve">Yes, Approve!</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Confirmation Modal -->
        <div class="modal fade" id="delayModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delay Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Please share the reason why delay?<br /><br />
                        <textarea class="form-control" id="delay_reason"></textarea>
                        <input type="hidden" id="id_form_account_delay">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="btn-delay" class="btn btn-warning" disabled
                            id="btn-delay">Delay!</button>
                    </div>
                </div>
            </div>
        </div>
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
                        <input type="hidden" id="id_form_account_reject">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="btn-reject" class="btn btn-danger" disabled
                            id="btn-reject">Reject!</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="infoModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Delay Information<br /><br />
                        <textarea class="form-control-plaintext" id="delay_note" readonly></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

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
                        <td>Department</td>
                        <td>${d.department} </td>
                    </tr>
                    <tr>
                        <td>Company</td>
                        <td>${d.company ?? 'PT. Aisin Indonesia Automotive'} </td>
                    </tr>
                    <tr>
                        <td>Phone Number</td>
                        <td>${d.phone} </td>
                    </tr>
                    <tr>
                        <td>Login Username</td>
                        <td>${d.ad_name}@aiia.co.id</td>
                    </tr>
                    <tr>
                        <td>Email Address</td>
                        <td>${ d.is_email == 1 ? '<i>Need Email for Outlook</i>' : 'User did not Request'}</td>
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
                    url: "{{ route('website.account.execution_ajax') }}",
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
                        data: 'budget_type',
                        name: 'budget_type',
                    },
                    {
                        data: 'form_type',
                        name: 'form_type'
                    },
                    {
                        orderable: false,
                        searchable: false,
                        data: null,
                        render: function(data, type, row, meta) {

                            if (data.final_status == 'Delay') {
                                return `
                                <button class="btn btn-primary btn-sm btn-table-info" data-bs-toggle="modal" data-bs-target="#infoModal" data-id="${data.id}" data-fullname="${data.fullname}" data-ad_name="${data.ad_name}" data-npk="${data.npk}" data-is_email="${data.is_email}" data-delay_note="${data.delay_note}">Progress</button>                                    
                                `;
                            } else {
                                return `
                                    Wait
                                `;
                            }
                        }
                    },
                    {
                        orderable: false,
                        searchable: false,
                        data: null,
                        render: function(data, type, row, meta) {

                            if (data.final_status == 'Delay') {
                                return `
                                <button class="btn btn-success btn-sm btn-table-approve" data-bs-toggle="modal" data-bs-target="#confirmModal" data-id="${data.id}" data-fullname="${data.fullname}" data-ad_name="${data.ad_name}" data-npk="${data.npk}" data-is_email="${data.is_email}" data-budget_type="${data.budget_type}">Finish</button>
                                <button class="btn btn-danger btn-sm btn-table-reject" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="${data.id}" data-fullname="${data.fullname}">Reject</button>`;
                            } else {
                                return `
                                <button class="btn btn-success btn-sm btn-table-approve" data-bs-toggle="modal" data-bs-target="#confirmModal" data-id="${data.id}" data-fullname="${data.fullname}" data-ad_name="${data.ad_name}" data-npk="${data.npk}" data-is_email="${data.is_email}" data-budget_type="${data.budget_type}">Finish</button>
                                <button class="btn btn-warning btn-sm btn-table-delay" data-bs-toggle="modal" data-bs-target="#delayModal" data-id="${data.id}" data-fullname="${data.fullname}" data-ad_name="${data.ad_name}" data-npk="${data.npk}" data-is_email="${data.is_email}" data-budget_type="${data.budget_type}">Delay</button>
                                <button class="btn btn-danger btn-sm btn-table-reject" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="${data.id}" data-fullname="${data.fullname}">Reject</button>`;
                            }
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

            $('#delay_reason').on('keyup', function() {
                if ($(this).val() != "")
                    $('#btn-delay').removeAttr('disabled');
                else
                    $('#btn-delay').attr('disabled', 'disabled');
            });

            $('#btn-approve').on('click', function() {
                let btnApprove = $(this);
                let id_form_account = $('#id_form_account').val();
                $.ajax({
                    url: "{{ route('website.account.approve_execution') }}",
                    type: "POST",
                    data: {
                        id: id_form_account,
                        type: 'ok',
                        ad_name: $('#ad_name').val(),
                        email_address: $('#email_address').val(),
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

            $('#btn-delay').on('click', function() {
                let id_form_account_delay = $('#id_form_account_delay').val();

                $.ajax({
                    url: "{{ route('website.account.approve_execution') }}",
                    type: "POST",
                    data: {
                        id: id_form_account_delay,
                        type: 'delay',
                        delay_note: $('#delay_reason').val(),
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {

                        toastr['success'](response)
                        table.ajax.reload();
                        $('#delayModal').modal('hide')
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                    }
                });
            });

            $('#btn-reject').on('click', function() {
                let id_form_account_reject = $('#id_form_account_reject').val();

                $.ajax({
                    url: "{{ route('website.account.approve_execution') }}",
                    type: "POST",
                    data: {
                        id: id_form_account_reject,
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

            $('#app_table').on('click', '.btn-table-approve', function() {
                var id_form_account = $(this).data('id');
                var fullname_form_account = $(this).data('fullname');
                var ad_name = $(this).data('ad_name');
                var npk = $(this).data('npk');
                // var email_address = $(this).data('email_address');
                var is_email = $(this).data('is_email');
                var budget_type = $(this).data('budget_type');

                var digitNpk = npk.match(/\d{4}$/);

                var noteText =
                    'Form Account telah selesai dibuat Silahkan login windows pada Device dengan memilih Other user, dengan user : \n\nLogin Windows\nUser name : ' +
                    ad_name + '@aiia.co.id\nPassword : Kiic2023\n\n';

                if (budget_type === 'budget') {
                    noteText += 'Lisensi Microsoft Office\nUser name : ' + ad_name +
                        '@Aisinaiia.onmicrosoft.com\nPassword : Kiic2023\n\n';
                }

                if (is_email === 1) {
                    noteText += 'Akun Email\nEmail address : ' + digitNpk +
                        '-aiia@ap01.aisingroup.com\nPassword : P@55w0rd!' + digitNpk + '\n\n';
                }

                noteText += 'Jika ada yang kurang dimengerti, harap hubungi Tim ITD\nTerima Kasih';

                $('#note').val(noteText);

                if (is_email === 1) {
                    $('#email_address').val(function(_, currentValue) {
                        return currentValue + digitNpk + '-aiia@ap01.aisingroup.com';
                    });
                }

                $('#id_form_account').val(id_form_account)
                $('#fullname_form_account').val(fullname_form_account)
                $('#ad_name').val(ad_name)
                // $('#email_address').val(email_address)
            })

            $('#app_table').on('click', '.btn-table-reject', function() {
                var id_form_account_reject = $(this).data('id');
                $('#id_form_account_reject').val(id_form_account_reject)
            })

            $('#app_table').on('click', '.btn-table-delay', function() {
                var id_form_account_delay = $(this).data('id');
                $('#id_form_account_delay').val(id_form_account_delay)
            })

            $('#app_table').on('click', '.btn-table-info', function() {
                var id_form_account_info = $(this).data('id');
                var delay_note = $(this).data('delay_note');
                $('#id_form_account_info').val(id_form_account_info)
                $('#delay_note').val(delay_note)
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
