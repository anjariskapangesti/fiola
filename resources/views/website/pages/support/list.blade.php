@extends('website.layouts.main', ['title' => 'List Support'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h5 class="card-header">List Support</h5>
                <a href="{{ route('website.support.create') }}" class="btn btn-success" style="margin: 1.25rem;">Create</a>
            </div>
            <div class="table-responsive text-nowrap" style="padding: 0 1.25rem 0 1.25rem;">
                <table class="table table-bordered" id="app_table" width="100%">
                    <thead>
                        <tr>
                            <th width="50px">No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Shift</th>
                            <th>Status</th>
                            <th>Update Status</th>
                            <th width="150px">Option</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="activeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Active Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure want to active this item?
                    <input type="text" readonly class="form-control-plaintext" id="name_active">
                    <input type="hidden" id="id_active">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="btn-approve-active">Yes, Confirm!</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="notActiveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Not Active Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure want to not active this item?
                    <input type="text" readonly class="form-control-plaintext" id="name_not_active">
                    <input type="hidden" id="id_not_active">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="btn-approve-not-active">Yes, Confirm!</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cutiModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cuti Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure want to cuti this item?
                    <input type="text" readonly class="form-control-plaintext" id="name_cuti">
                    <input type="hidden" id="id_cuti">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-info" id="btn-approve-cuti">Yes, Confirm!</button>
                </div>
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
                    <input type="text" readonly class="form-control-plaintext" id="name_delete">
                    <input type="hidden" id="id_delete">
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
    <link rel="stylesheet" href="{{ asset('vendor/datatables/css/datatables.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('vendor/datatables/js/datatables.min.js') }}"></script>
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
                    url: "{{ route('website.support.list_ajax') }}",
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
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'email',
                        name: 'email',
                    },
                    {
                        data: 'nohp',
                        name: 'nohp',
                    },
                    {
                        data: 'shift',
                        name: 'shift',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center',
                        render: function(data, type, row, meta) {
                            let statusClass = '';
                            let statusText = '';

                            if (data === 'active') {
                                statusClass = 'bg-success';
                                statusText = 'Active';
                            } else if (data === 'not active') {
                                statusClass = 'bg-warning';
                                statusText = 'Not Active';
                            } else if (data === 'cuti') {
                                statusClass = 'bg-info';
                                statusText = 'Cuti';
                            }

                            return `<span class="badge ${statusClass}">${statusText}</span>`;
                        }
                    },
                    {
                        orderable: false,
                        searchable: false,
                        data: null,
                        render: function(data, type, row, meta) {
                            let buttons = '';

                            if (data.status === null || data.status === 'not active') {
                                buttons = `
                                    <button class="btn btn-success btn-sm btn-active" data-bs-toggle="modal" data-bs-target="#activeModal" data-id="${data.id}" data-name="${data.name}">Active</button>
                                    <button class="btn btn-info btn-sm btn-cuti" data-bs-toggle="modal" data-bs-target="#cutiModal" data-id="${data.id}" data-name="${data.name}">Cuti</button>
                                `;
                            } else if (data.status === 'active') {
                                buttons = `
                                    <button class="btn btn-warning btn-sm btn-not-active" data-bs-toggle="modal" data-bs-target="#notActiveModal" data-id="${data.id}" data-name="${data.name}">Not Active</button>
                                    <button class="btn btn-info btn-sm btn-cuti" data-bs-toggle="modal" data-bs-target="#cutiModal" data-id="${data.id}" data-name="${data.name}">Cuti</button>
                                `;
                            } else if (data.status === 'cuti') {
                                buttons = `
                                    <button class="btn btn-success btn-sm btn-active" data-bs-toggle="modal" data-bs-target="#activeModal" data-id="${data.id}" data-name="${data.name}">Active</button>
                                    <button class="btn btn-warning btn-sm btn-not-active" data-bs-toggle="modal" data-bs-target="#notActiveModal" data-id="${data.id}" data-name="${data.name}">Not Active</button>
                                    `;
                            }

                            return `<div class="text-center">${buttons}</div>`;
                        }
                    },
                    {
                        orderable: false,
                        searchable: false,
                        data: null,
                        render: function(data, type, row, meta) {
                            return `<div class="text-center">
                                        <a href="{{ url('support/edit/') }}/${row.id}" class="btn btn-sm btn-primary">Edit</a>
                                        <button class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="${data.id}" data-name="${data.name}">Delete</button>
                                    </div>
                            `;
                        }
                    },
                ],
            });
            // ACTIVE
            $('#app_table').on('click', '.btn-active', function() {
                var id_active = $(this).data('id');
                var name_active = $(this).data('name');
                $('#id_active').val(id_active)
                $('#name_active').val(name_active)
            })

            $('#btn-approve-active').on('click', function() {
                let id_active = $('#id_active').val();
                $.ajax({
                    url: "{{ route('website.support.status') }}",
                    type: "POST",
                    data: {
                        id: id_active,
                        type: 'active',
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response)
                        table.ajax.reload();
                        $('#activeModal').modal('hide')
                    },
                    error: function(xhr, status, error, response) {
                        toastr.error(xhr.responseJSON.error);
                        $('#activeModal').modal('hide')
                    }
                });
            });

            // NOT ACTIVE
            $('#app_table').on('click', '.btn-not-active', function() {
                var id_not_active = $(this).data('id');
                var name_not_active = $(this).data('name');
                $('#id_not_active').val(id_not_active)
                $('#name_not_active').val(name_not_active)
            })

            $('#btn-approve-not-active').on('click', function() {
                let id_not_active = $('#id_not_active').val();
                $.ajax({
                    url: "{{ route('website.support.status') }}",
                    type: "POST",
                    data: {
                        id: id_not_active,
                        type: 'not_active',
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response)
                        table.ajax.reload();
                        $('#notActiveModal').modal('hide')
                    },
                    error: function(xhr, status, error, response) {
                        toastr.error(xhr.responseJSON.error);
                        $('#notActiveModal').modal('hide')
                    }
                });
            });

            // CUTI
            $('#app_table').on('click', '.btn-cuti', function() {
                var id_cuti = $(this).data('id');
                var name_cuti = $(this).data('name');
                $('#id_cuti').val(id_cuti)
                $('#name_cuti').val(name_cuti)
            })

            $('#btn-approve-cuti').on('click', function() {
                let id_cuti = $('#id_cuti').val();
                $.ajax({
                    url: "{{ route('website.support.status') }}",
                    type: "POST",
                    data: {
                        id: id_cuti,
                        type: 'cuti',
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response)
                        table.ajax.reload();
                        $('#cutiModal').modal('hide')
                    },
                    error: function(xhr, status, error, response) {
                        toastr.error(xhr.responseJSON.error);
                        $('#cutiModal').modal('hide')
                    }
                });
            });

            // DELETE
            $('#app_table').on('click', '.btn-delete', function() {
                var id_delete = $(this).data('id');
                var name_delete = $(this).data('name');
                $('#id_delete').val(id_delete)
                $('#name_delete').val(name_delete)
            })

            $('#btn-approve-delete').on('click', function() {
                let id_delete = $('#id_delete').val();
                $.ajax({
                    url: "{{ route('website.support.destroy') }}",
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
                    error: function(xhr, status, error, response) {
                        toastr.error(xhr.responseJSON.error);
                        $('#deleteModal').modal('hide')
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var deleteButton = document.getElementById('btn-approve-delete');
            var spinner = '<i class="mdi mdi-loading spin"></i>';

            deleteButton.addEventListener('click', function() {
                deleteButton.setAttribute('disabled', 'true');
                deleteButton.innerHTML = spinner + ' Deleting...';
            });
        });
    </script>
@endpush
