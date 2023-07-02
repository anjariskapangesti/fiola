@extends('website.layouts.main', ['title' => 'Manager Data Approval Account'])

@section('content')
    <div class="pagetitle">
        <h4>Department</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Department</a></li>
                <li class="breadcrumb-item active"><a href="#">Data Department</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body p-3">
                    <table class="display" width="100%" id="app_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
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
                        <input type="text" readonly class="form-control-plaintext" id="name_department">
                        <input type="hidden" id="id_department">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="btn-approve-delete">Yes, Delete!</button>
                    </div>
                </div>
            </div>
        </div>
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
                        <td width="30%">Code</td>
                        <td>${d.code} </td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>${d.name} </td>
                    </tr>                    
                </table>
                `
                );
            }

            $(document).ready(function() {
                var table = $('#app_table').DataTable({
                    "lengthChange": false,
                    'processing': true,
                    'serverSide': true,
                    ajax: {
                        url: "{{ route('website.department.show_data_department_ajax') }}",
                    },
                    columns: [{
                            className: 'dt-control',
                            orderable: false,
                            data: null,
                            defaultContent: '',
                            searchable: false,
                        },
                        {
                            data: 'code',
                            name: 'code',
                        },
                        {
                            data: 'name',
                            name: 'name',
                        },
                        {
                            orderable: false,
                            searchable: false,
                            data: null,
                            render: function(data, type, row, meta) {
                                return `                            
                            <button class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="${data.id}" data-name="${data.name}">Delete</button>`;
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

                $('#btn-approve').on('clcik', function() {

                });

                $('#app_table').on('click', '.btn-delete', function() {
                    var id_department = $(this).data('id');
                    var name_department = $(this).data('name');
                    $('#id_department').val(id_department)
                    $('#name_department').val(name_department)
                    // console.log(id_form_account);
                })

                $('#btn-approve-delete').on('click', function() {
                    let id_department = $('#id_department').val();
                    console.log(id_department);
                    $.ajax({
                        url: "{{ route('website.department.destroy') }}",
                        type: "DELETE",
                        data: {
                            id: id_department,
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function(response) {

                            toastr['success'](response)
                            table.ajax.reload();
                            $('#deleteModal').modal('hide')
                        },
                        error: function(xhr, status, error, response) {
                            toastr['error']('Error')
                        }
                    });
                });

            });
        </script>
    @endpush
