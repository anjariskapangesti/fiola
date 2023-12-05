@extends('website.layouts.main', ['title' => 'Data Department'])

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
                <div class="card-body p-3 table table-responsive">
                    <a href="{{ route('website.department.create') }}" class="btn btn-success">Add Department</a>
                    <table class="display" width="100%" id="app_table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id_edit">

                        <label for="name"><b>Nama Department</b></label>
                        <input type="text" class="form-control" name="name" id="name_edit">

                        <label for="code"><b>Code Department</b></label>
                        <input type="text" class="form-control" name="code" id="code_edit">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="btn-approve-edit">Yes, Edit!</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data</h5>
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
                    'lengthChange': true,
                    'processing': true,
                    'serverSide': false,
                    'orderable': true,
                    ajax: {
                        url: "{{ route('website.department.show_data_department_ajax') }}",
                    },
                    columns: [{
                            data: null,
                            orderable: false,
                            searchable: true,
                            render: function(data, type, row, meta) {
                                // Calculate the row number using the meta object
                                var rowIndex = meta.row + meta.settings._iDisplayStart + 1;
                                return rowIndex;
                            },
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
                                <button class="btn btn-success btn-sm btn-edit" data-bs-toggle="modal" data-bs-target="#editModal" data-id="${data.id}" data-name="${data.name}" data-code="${data.code}">Edit</button>    
                                <button class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="${data.id}" data-name="${data.name}">Delete</button>`;
                            }
                        },
                    ],
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
                })

                $('#btn-approve-delete').on('click', function() {
                    let id_department = $('#id_department').val();
                    $.ajax({
                        url: "{{ route('website.department.destroy') }}",
                        type: "post",
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

                $('#app_table').on('click', '.btn-edit', function() {
                    var id_edit = $(this).data('id');
                    var name_edit = $(this).data('name');
                    var code_edit = $(this).data('code');
                    
                    $('#id_edit').val(id_edit)
                    $('#name_edit').val(name_edit)
                    $('#code_edit').val(code_edit)
                })

                $('#btn-approve-edit').on('click', function() {
                    let id_edit = $('#id_edit').val();
                    let name_edit = $('#name_edit').val();
                    let code_edit = $('#code_edit').val();
                    $.ajax({
                        url: "{{ route('website.department.edit') }}",
                        type: "post",
                        data: {
                            id: id_edit,
                            name: name_edit,
                            code: code_edit,
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function(response) {

                            toastr['success'](response)
                            table.ajax.reload();
                            $('#editModal').modal('hide')
                        },
                        error: function(xhr, status, error, response) {
                            toastr['error']('Error')
                        }
                    });
                });

            });
        </script>
    @endpush
