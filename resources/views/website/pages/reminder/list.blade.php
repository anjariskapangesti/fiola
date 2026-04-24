@extends('website.layouts.main', ['title' => 'Data Reminder'])

@section('content')
    <div class="pagetitle">
        <h4>Reminder</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Reminder</a></li>
                <li class="breadcrumb-item active"><a href="#">Data Reminder</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body p-3 table table-responsive">
                    <a href="{{ route('website.reminder.create') }}" class="btn btn-success">Add Reminder</a>
                    <table class="display" width="100%" id="app_table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Department</th>
                                <th>Nama Manager</th>
                                <th>Email</th>
                                <th>No. HP</th>
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
                        <input type="hidden" id="id_reminder_edit">

                        <div class="col-md-12">
                            <label for="department_id"><b>Department</b></label>
                            <select name="department_id" id="department_id_edit" class="form-control">
                                <option value=""></option>
                                @foreach ($departments as $department)
                                    @if ($department->id < 19 || $department->id > 27)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="user_id"><b>Nama Manager</b></label>
                            <select name="user_id" id="user_id_edit" class="form-control">
                                <option value=""></option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
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
                        <h5 class="modal-title">Delete Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure want to delete this reminder?
                        <input type="text" readonly class="form-control-plaintext" id="user_name_delete">
                        <input type="hidden" id="id_reminder_delete">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="btn-approve-delete">Yes, Delete!</button>
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
                        url: "{{ route('website.reminder.list_ajax') }}",
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
                            data: 'department_name',
                            name: 'department_name',
                        },
                        {
                            data: 'user_name',
                            name: 'user_name',
                        },
                        {
                            data: 'user_email',
                            name: 'user_email',
                        },
                        {
                            data: 'user_nohp',
                            name: 'user_nohp',
                        },
                        {
                            orderable: false,
                            searchable: false,
                            data: null,
                            render: function(data, type, row, meta) {
                                return `                        
                                <button class="btn btn-success btn-sm btn-edit" data-bs-toggle="modal" data-bs-target="#editModal" data-id="${data.id}" data-user_id="${data.user_id}" data-department_id="${data.department_id}">Edit</button>    
                                <button class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="${data.id}" data-user_name="${data.user_name}">Delete</button>`;
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
                    var id_reminder_delete = $(this).data('id');
                    var user_name_delete = $(this).data('user_name');

                    $('#id_reminder_delete').val(id_reminder_delete)
                    $('#user_name_delete').val(user_name_delete)
                })

                $('#btn-approve-delete').on('click', function() {
                    let id_reminder_delete = $('#id_reminder_delete').val();
                    $.ajax({
                        url: "{{ route('website.reminder.destroy') }}",
                        type: "post",
                        data: {
                            id: id_reminder_delete,
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
                    var id_reminder_edit = $(this).data('id');
                    var user_id_edit = $(this).data('user_id');
                    var department_id_edit = $(this).data('department_id');

                    $('#id_reminder_edit').val(id_reminder_edit)
                    $('#user_id_edit').val(user_id_edit)
                    $('#department_id_edit').val(department_id_edit)

                    $('#department_id_edit').trigger('change');
                    $('#user_id_edit').trigger('change');
                })

                

                $('#btn-approve-edit').on('click', function() {
                    let id_reminder_edit = $('#id_reminder_edit').val();
                    let user_id_edit = $('#user_id_edit').val();
                    let department_id_edit = $('#department_id_edit').val();

                    $.ajax({
                        url: "{{ route('website.reminder.edit') }}",
                        type: "post",
                        data: {
                            id: id_reminder_edit,
                            user_id: user_id_edit,
                            department_id: department_id_edit,

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
        <script>
            $(document).ready(function() {
                    var user_id_edit = $('#user_id_edit').val();
                    var department_id_edit = $('#department_id_edit').val();

                    // Set selected option for department_id_edit
                    $('#department_id_edit').find('option[value="' + department_id_edit + '"]').attr('selected',
                        'selected');

                    // Set selected option for user_id_edit
                    $('#user_id_edit').find('option[value="' + user_id_edit + '"]').attr('selected',
                    'selected');
                });
        </script>
    @endpush
