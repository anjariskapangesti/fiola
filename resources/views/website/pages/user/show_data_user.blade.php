@extends('website.layouts.main', ['title' => 'Data User'])

@section('content')
    <div class="pagetitle">
        <h4>User</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">User</a></li>
                <li class="breadcrumb-item active"><a href="#">Data User</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body p-3 table table-responsive">
                    <a href="{{ route('website.user.create') }}" class="btn btn-success">Add User</a>
                    <table class="display" width="100%" id="app_table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>NPK</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>No HP</th>
                                <th>Option</th>
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
                        <input type="text" readonly class="form-control-plaintext" id="name_user">
                        <input type="hidden" id="id_user">
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
            // function format(d) {
            //     return (
            //         `
            //     <table class="table table-sm">
            //         <tr>
            //             <td width="30%">NPK</td>
            //             <td>${d.npk} </td>
            //         </tr>
            //         <tr>
            //             <td width="30%">Name</td>
            //             <td>${d.name} </td>
            //         </tr>
            //         <tr>
            //             <td>Email</td>
            //             <td>${d.email} </td>
            //         </tr>
            //         <tr>
            //             <td>user</td>
            //             <td>${d.dept_id} </td>
            //         </tr>    
            //         <tr>
            //             <td>No. Handphone</td>
            //             <td>${d.nohp} </td>
            //         </tr>               
            //     </table>
            //     `
            //     );
            // }

            $(document).ready(function() {
                var table = $('#app_table').DataTable({
                    'lengthChange' : true,
                    'processing': true,
                    'serverSide': false,
                    'orderable': true,
                    ajax: {
                        url: "{{ route('website.user.show_data_user_ajax') }}",
                    },
                    columns: [{
                            data: null,
                            orderable: true,
                            searchable: true,
                            render: function(data, type, row, meta) {
                                var rowIndex = meta.row + meta.settings._iDisplayStart + 1;
                                return rowIndex;
                            },
                        },
                        {
                            data: 'npk',
                            name: 'npk',
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
                // $('#app_table tbody').on('click', 'td.dt-control', function() {
                //     var tr = $(this).closest('tr');
                //     var row = table.row(tr);

                //     if (row.child.isShown()) {
                //         row.child.hide();
                //         tr.removeClass('shown');
                //     } else {
                //         row.child(format(row.data())).show();
                //         tr.addClass('shown');
                //     }
                // });

                $('#reject_reason').on('keyup', function() {
                    if ($(this).val() != "")
                        $('#btn-reject').removeAttr('disabled');
                    else
                        $('#btn-reject').attr('disabled', 'disabled');
                });

                $('#btn-approve').on('clcik', function() {

                });

                $('#app_table').on('click', '.btn-delete', function() {
                    var id_user = $(this).data('id');
                    var name_user = $(this).data('name');
                    $('#id_user').val(id_user)
                    $('#name_user').val(name_user)
                })

                $('#btn-approve-delete').on('click', function() {
                    let id_user = $('#id_user').val();
                    $.ajax({
                        url: "{{ route('website.user.destroy') }}",
                        type: "POST",
                        data: {
                            id: id_user, 
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            toastr['success'](response);
                            table.ajax.reload();
                            $('#deleteModal').modal('hide');
                        },
                        error: function(xhr, status, error) {
                            toastr['error']('Error');
                        }
                    });
                });

            });
        </script>
    @endpush
