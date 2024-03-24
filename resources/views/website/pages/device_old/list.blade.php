@extends('website.layouts.main', ['title' => 'Data Device'])

@section('content')
    <div class="pagetitle">
        <h4>Device</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Device</a></li>
                <li class="breadcrumb-item active"><a href="#">Data Device</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body p-3 table table-responsive">
                    <a href="{{ route('website.device.create') }}" class="btn btn-success">Add Device</a>
                    <table class="display" width="100%" id="app_table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Spesifikasi</th>
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

                        <label for="name"><b>Nama Device</b></label>
                        <input type="text" class="form-control" name="name" id="name_edit">

                        <label for="cost"><b>Harga</b></label>
                        <input type="text" class="form-control" name="cost" id="cost_edit">

                        <label for="spesifikasi"><b>Spesifikasi</b></label>
                        <textarea class="form-control" id="spesifikasi_edit" style="height: 100px;" name="spesifikasi"></textarea>
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
                        <input type="text" readonly class="form-control-plaintext" id="name_device">
                        <input type="hidden" id="id_device">
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
                        url: "{{ route('website.device.list_ajax') }}",
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
                            data: 'name',
                            name: 'name',
                        },
                        {
                            data: 'cost',
                            name: 'cost',
                        },
                        {
                            data: 'spesifikasi',
                            name: 'spesifikasi',
                        },
                        {
                            orderable: false,
                            searchable: false,
                            data: null,
                            render: function(data, type, row, meta) {
                                return `                        
                                <button class="btn btn-success btn-sm btn-edit" data-bs-toggle="modal" data-bs-target="#editModal" data-id="${data.id}" data-name="${data.name}" data-cost="${data.cost}" data-spesifikasi="${data.spesifikasi}">Edit</button>    
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
                    var id_device = $(this).data('id');
                    var name_device = $(this).data('name');

                    $('#id_device').val(id_device)
                    $('#name_device').val(name_device)
                })

                $('#btn-approve-delete').on('click', function() {
                    let id_device = $('#id_device').val();
                    $.ajax({
                        url: "{{ route('website.device.destroy') }}",
                        type: "post",
                        data: {
                            id: id_device,
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
                    var cost_edit = $(this).data('cost');
                    var spesifikasi_edit = $(this).data('spesifikasi');

                    $('#id_edit').val(id_edit)
                    $('#name_edit').val(name_edit)
                    $('#cost_edit').val(cost_edit)
                    $('#spesifikasi_edit').val(spesifikasi_edit)
                })

                $('#btn-approve-edit').on('click', function() {
                    let id_edit = $('#id_edit').val();
                    let name_edit = $('#name_edit').val();
                    let cost_edit = $('#cost_edit').val();
                    let spesifikasi_edit = $('#spesifikasi_edit').val();
                    $.ajax({
                        url: "{{ route('website.device.edit') }}",
                        type: "post",
                        data: {
                            id: id_edit,
                            name: name_edit,
                            cost: cost_edit,
                            spesifikasi: spesifikasi_edit,
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
