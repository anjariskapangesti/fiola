@extends('website.layouts.main', ['title' => 'List App'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h5 class="card-header">List App</h5>
                <button class="btn btn-success btn-create" style="margin: 1.25rem;" data-bs-toggle="modal"
                    data-bs-target="#createModal">Create</button>
            </div>
            <div class="table-responsive text-nowrap" style="padding: 0 1.25rem 0 1.25rem;">
                <table class="table table-bordered" id="app_table" width="100%">
                    <thead>
                        <tr>
                            <th width="50px">No</th>
                            <th>Name</th>
                            <th>Harga</th>
                            <th>Description</th>
                            <th width="150px">Option</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create App</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                            placeholder="AVICENNA" />
                        <label for="name">Name <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="url" name="url" value="{{ old('url') }}"
                            placeholder="https://avicenna.aiia.co.id" />
                        <label for="url">URL</label>
                    </div>

                    <div class="form-floating form-floating-outline mb-4">
                        <textarea class="form-control auto-resize" id="description" name="description" placeholder="Deskripsi App">{{ old('description') }}</textarea>
                        <label for="description">Description</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="btn-approve-create">Yes, Create!</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit App</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="app_id" name="app_id"> <!-- Hidden field to store app ID -->

                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="edit_name" name="name" placeholder="AVICENNA" />
                        <label for="edit_name">Name <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="edit_url" name="url"
                            placeholder="https://avicenna.aiia.co.id" />
                        <label for="edit_url">URL</label>
                    </div>

                    <div class="form-floating form-floating-outline mb-4">
                        <textarea class="form-control auto-resize" id="edit_description" name="description" placeholder="Deskripsi App"></textarea>
                        <label for="edit_description">Description</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="btn-approve-edit">Save Changes</button>
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
    <style>
        .description-column {
            max-width: 250px;
            white-space: pre-wrap;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('vendor/datatables/js/datatables.min.js') }}"></script>
    <script>
        document.getElementById('name').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        document.getElementById('url').addEventListener('input', function() {
            this.value = this.value.toLowerCase();
        });

        document.getElementById('edit_name').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        document.getElementById('edit_url').addEventListener('input', function() {
            this.value = this.value.toLowerCase();
        });

        const textarea = document.querySelector('.auto-resize');

        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    </script>
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
                    url: "{{ route('website.app.list_ajax') }}",
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
                        data: 'url',
                        name: 'url',
                    },
                    {
                        data: 'description',
                        name: 'description',
                        className: 'description-column',
                    },
                    {
                        orderable: false,
                        searchable: false,
                        data: null,
                        render: function(data, type, row, meta) {
                            return `<div class="text-center">
                                        <button class="btn btn-primary btn-sm btn-edit" data-bs-toggle="modal" data-bs-target="#editModal" data-id="${data.id}" data-name="${data.name}" data-url="${data.url}" data-description="${data.description}">Edit</button>
                                        <button class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="${data.id}" data-name="${data.name}">Delete</button>
                                    </div>
                            `;
                        }
                    },
                ],
            });

            $('#btn-approve-create').on('click', function() {
                let name = $('#name').val();
                let url = $('#url').val();
                let description = $('#description').val();
                let submitButton = $(this);

                // Disable button and change text
                submitButton.attr('disabled', 'true');
                submitButton.text('Submitting...');

                $.ajax({
                    url: "{{ route('website.app.store') }}",
                    type: "POST",
                    data: {
                        name: name,
                        url: url,
                        description: description,
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response.success);
                        table.ajax.reload();
                        $('#createModal').modal('hide');

                        // Reset the modal fields
                        $('#name').val('');
                        $('#url').val('');
                        $('#description').val('');
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            if (errors.name) {
                                toastr.error(errors.name[0]);
                            }
                        } else {
                            toastr.error(xhr.responseJSON.error);
                        }
                    },
                    complete: function() {
                        // Re-enable button and reset text after request completes
                        submitButton.removeAttr('disabled');
                        submitButton.text('Yes, Create!');
                    }
                });
            });

            $('#app_table').on('click', '.btn-edit', function() {
                let appId = $(this).data('id');
                let appName = $(this).data('name');
                let appUrl = $(this).data('url');
                let appDescription = $(this).data('description');
                // Populate the modal fields
                $('#app_id').val(appId);
                $('#edit_name').val(appName);
                $('#edit_url').val(appUrl);
                $('#edit_description').val(appDescription);
            });

            // Handle the save changes functionality
            $('#btn-approve-edit').on('click', function() {
                let appId = $('#app_id').val();
                let name = $('#edit_name').val();
                let url = $('#edit_url').val();
                let description = $('#edit_description').val();
                let submitButton = $(this);
                // Disable button and change text
                submitButton.attr('disabled', 'true');
                submitButton.text('Saving...');

                $.ajax({
                    url: "{{ route('website.app.update') }}",
                    type: "POST",
                    data: {
                        id: appId,
                        name: name,
                        url: url,
                        description: description,
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        toastr['success'](response.success);
                        table.ajax.reload();
                        $('#editModal').modal('hide');

                        // Reset the modal fields
                        $('#app_id').val('');
                        $('#edit_name').val('');
                        $('#edit_url').val('');
                        $('#edit_description').val('');
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            if (errors.name) {
                                toastr.error(errors.name[0]);
                            }
                        } else {
                            toastr.error(xhr.responseJSON.error);
                        }
                    },
                    complete: function() {
                        // Re-enable button and reset text after request completes
                        submitButton.removeAttr('disabled');
                        submitButton.text('Save Changes');
                    }
                });
            });

            $('#app_table').on('click', '.btn-delete', function() {
                var id_delete = $(this).data('id');
                var name_delete = $(this).data('name');
                $('#id_delete').val(id_delete)
                $('#name_delete').val(name_delete)
            })

            $('#btn-approve-delete').on('click', function() {
                let id_delete = $('#id_delete').val();

                let deleteButton = $(this);

                // Disable button and change text
                deleteButton.attr('disabled', 'true');
                deleteButton.text('Deleting...');
                $.ajax({
                    url: "{{ route('website.app.destroy') }}",
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
                    },
                    complete: function() {
                        // Re-enable button and reset text after request completes
                        deleteButton.removeAttr('disabled');
                        deleteButton.text('Yes, Delete!');
                    }
                });
            });
        });
    </script>
@endpush
