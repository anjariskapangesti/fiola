@extends('website.layouts.main', ['title' => 'Finished Account'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h5 class="card-header">Account Registration/Change/Deletion Form (FRM-ITD-S13-001-00)</h5>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

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
                    url: "{{ route('website.account.finished_ajax') }}",
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
                                return `<center>Not yet</center>`;
                            }
                        }
                    },
                ],
            });

            function format(d) {
                return (
                    `
                    <table class="table table-bordered table-sm" style="background-color: #ebf1f2;">
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Budget Type</td>
                                <td>${d.budget_type} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Form Type</td>
                                <td>${d.form_type} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">NPK</td>
                                <td>${d.npk}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Fullname</td>
                                <td>${d.fullname} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Department</td>
                                <td>${d.department} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Phone Number</td>
                                <td>${d.phone} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">AD Username</td>
                                <td>AIIA\\${d.ad_name}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">User Lisensi Microsoft Office</td>
                                <td>
                                    ${d.is_email === false ? '<i>Tidak butuh lisensi</i>' : (d.is_email === true ? (d.email_address == null ? '<i>Akan diinformasikan setelah disetujui</i>' : d.ad_name + '@aisinaiia.onmicrosoft.com') : '')}
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Email Address</td>
                                <td>
                                    ${d.is_email === false ? '<i>Tidak butuh email</i>' : (d.is_email === true ? (d.email_address == null ? '<i>Akan diinformasikan setelah disetujui</i>' : d.email_address) : '')}
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Purpose</td>
                                <td>${d.purpose} </td>
                            </tr>
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>Manager Approval Date</td>
                                <td>${d.manager_approval_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Manager Approval By</td>
                                <td>${d.manager_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Manager Note</td>
                                <td>${d.manager_note ?? '-'}</td>
                            </tr>
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>ITD Approval Date</td>
                                <td>${d.it_approval_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Approval By</td>
                                <td>${d.it_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Note</td>
                                <td>${d.it_note ?? '-'}</td>
                            </tr>  
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>ITD Manager Approval Date</td>
                                <td>${d.it_mgr_approval_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Manager Approval By</td>
                                <td>${d.it_mgr_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Manager Note</td>
                                <td>${d.it_mgr_note ?? '-'}</td>
                            </tr>
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>On Progress Date</td>
                                <td>${d.on_progress_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>On Progress By</td>
                                <td>${d.on_progress_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>On Progress Note</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.on_progress_note ?? '-'}</td>
                            </tr>
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>Finish Date</td>
                                <td>${d.finish_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Finish By</td>
                                <td>${d.finish_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Finish Note</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.finish_note ?? '-'}</td>
                            </tr>
                        </tbody>
                    </table>
                    `
                );
            }

            $('#app_table').on('click', '.btn-table-delete', function() {
                var id_delete = $(this).data('id');
                var no_reg_delete = $(this).data('no_reg');

                $('#id_delete').val(id_delete)
                $('#no_reg_delete').val(no_reg_delete)
            })

            $('#btn-delete').on('click', function() {
                let id_delete = $('#id_delete').val();
                $.ajax({
                    url: "{{ route('website.account.delete_form') }}",
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

            $('#btn-approve').on('clcik', function() {

            });

            $('#app_table').on('click', '.btn-table-approve', function() {
                var id_form_account = $(this).data('id');
                var fullname_form_account = $(this).data('fullname');
                var ad_name = $(this).data('ad_name');

                $('#id_form_account').val(id_form_account)
                $('#fullname_form_account').val(fullname_form_account)
                $('#ad_name').val(ad_name)
            })

            $('#btn-approve').on('click', function() {
                let id_form_account = $('#id_form_account').val();
                $.ajax({
                    url: "{{ route('website.account.approve_form') }}",
                    type: "POST",
                    data: {
                        id: id_form_account,
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
        });
    </script>
@endpush
