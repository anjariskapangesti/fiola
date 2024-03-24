@extends('website.layouts.main', ['title' => 'Manager Approval Software'])

@section('content')
    <div class="pagetitle">
        <h4>Standard Setting Change (Software Installation) Form (FRM-ITD-S13-005-00)</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Manager Approval</a></li>
                <li class="breadcrumb-item active"><a href="#">Form Software</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body p-3 table table-responsive">
                    <table class="display" width="100%" id="app_table">
                        <thead>
                            <tr>
                                <th style="max-width: 30px;">No</th>
                                <th style="max-width: 70px;">No. Reg</th>
                                <th>Creator</th>
                                <th>App Name</th>
                                <th>Category</th>
                                <th>Install on</th>
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
                        <input type="text" readonly class="form-control-plaintext" id="appname_form_software">
                        <input type="hidden" id="id_form_software">

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
                        Please share the reason why you're rejecting
                        <input type="text" readonly class="form-control-plaintext" id="appname_form_software_reject">
                        <input type="hidden" id="id_form_software_reject">

                        <textarea class="form-control" id="reject_reason"></textarea>

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
                <table class ="table table-sm table-bordered">
                    <tr>
                        <th style="background-color: #66a7e3; max-width: 50px;">App Name</th>
                        <td>${d.appname} </td>
                    </tr>
                    <tr>
                        <th style="background-color: #66a7e3; max-width: 50px;">Install on</th>
                        <td>${d.installon} </td>
                    </tr>      
                    <tr>
                        <th style="background-color: #66a7e3; max-width: 50px;">Detail</th>
                        <td>${d.detail}</td>
                    </tr>     
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-end">
                        </th>
                    </tr>
                    <tr>
                        <th style="background-color: #66a7e3; max-width: 50px;">Created by</th>
                        <td>${d.user_name}</td>
                    </tr>
                    <tr>
                        <th style="background-color: #66a7e3; max-width: 50px;">Purpose</th>
                        <td>${d.purpose}</td>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-end">
                            <button class="btn btn-success btn-sm btn-table-approve" data-bs-toggle="modal" data-bs-target="#confirmModal" data-id="${d.id}" data-appname="${d.user_name}">Approve</button>
                            <button class="btn btn-danger btn-sm btn-table-reject" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="${d.id}" data-appname="${d.user_name}">Reject</button>
                        </th>
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
                // "sScrollY": true,
                ajax: {
                    url: "{{ route('website.software.show_manager_approval_ajax') }}",
                },
                columns: [
                    // {
                    //     className: 'dt-control',
                    //     orderable: false,
                    //     data: null,
                    //     defaultContent: '',
                    //     searchable: false,
                    // },
                    {
                        className: 'text-center',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'no_reg',
                        name: 'no_reg',
                    },
                    {
                        data: 'user_name',
                        name: 'user_name',
                    },
                    {
                        data: 'appname',
                        name: 'appname',
                    },
                    {
                        data: 'category',
                        name: 'category',
                    },
                    {
                        data: 'installon',
                        name: 'installon',
                    },
                    // {
                    //     orderable: false,
                    //     searchable: false,
                    //     data: null,
                    //     render: function(data, type, row, meta) {
                    //         return `
                    //         <button class="btn btn-success btn-sm btn-table-approve" data-bs-toggle="modal" data-bs-target="#confirmModal" data-id="${data.id}" data-appname="${data.appname}">Approve</button>
                    //         <button class="btn btn-danger btn-sm btn-table-reject" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="${data.id}" data-appname="${data.appname}">Reject</button>`;
                    //     }
                    // },
                    {
                        className: 'klik',
                        orderable: false,
                        data: null,
                        defaultContent: '',
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return `<button class="badge bg-primary">Klik untuk Detail dan Approve</button>`;
                        }
                    },
                ],
            });

            $('#app_table tbody').on('click', 'td.klik', function() {
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
                let id_form_software = $('#id_form_software').val();
                $.ajax({
                    url: "{{ route('website.software.approve_manager') }}",
                    type: "POST",
                    data: {
                        id: id_form_software,
                        type: 'ok',
                        manager_note: $('#note').val(),
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
                let id_form_software_reject = $('#id_form_software_reject').val();
                console.log(id_form_software_reject);
                // window.location.href = "{{ route('website.software.approve_manager') }}";
                $.ajax({
                    url: "{{ route('website.software.approve_manager') }}",
                    type: "POST",
                    data: {
                        id: id_form_software_reject,
                        type: 'reject',
                        manager_note: $('#reject_reason').val(),
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
                var id_form_software = $(this).data('id');
                var appname_form_software = $(this).data('appname');
                $('#id_form_software').val(id_form_software)
                $('#appname_form_software').val(appname_form_software)
                // console.log(id_form_software);
            })

            $('#app_table').on('click', '.btn-table-reject', function() {
                var id_form_software_reject = $(this).data('id');
                var appname_form_software_reject = $(this).data('appname');
                $('#id_form_software_reject').val(id_form_software_reject)
                $('#appname_form_software_reject').val(appname_form_software_reject)
                // console.log(id_form_software_reject);
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
