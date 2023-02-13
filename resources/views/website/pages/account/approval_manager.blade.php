@extends('website.layouts.main')
@section('title', 'Manager Approval')

@section('content')
    <div class="pagetitle">
        <h4>Approval Account Registration/Change/Deletion</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Manager Approval</a></li>
                <li class="breadcrumb-item active"><a href="#">Form Account</a></li>
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
                                <th>Fullname</th>
                                <th>Budget Type</th>
                                <th>Request Type</th>
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
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-success">Yes, Approve!</button>
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
                  Please share the reason why you're rejecting<br/><br/>
                  <textarea class="form-control" id="reject_reason"></textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="button" id="btn-reject" class="btn btn-danger" disabled>Reject!</button>
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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
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
                        <td>Dept.</td>
                        <td>${d.department} </td>
                    </tr>
                    <tr>
                        <td>Company</td>
                        <td>${d.company} </td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td>${d.phone} </td>
                    </tr>
                    <tr>
                        <td>Expired Date</td>
                        <td>${d.expired_date ?? '-'} </td>
                    </tr>
                    <tr>
                        <td>Login Username</td>
                        <td>aiia\\${d.ad_name}</td>
                    </tr>
                    <tr>
                        <td>Email Address</td>
                        <td>${ d.is_email == 1 ? '<i>Will be Informed Later after approved</i>' : 'User did not Request'}</td>
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
                    url: "{{ route('website.account.show_manager_approval_ajax') }}",
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
                        render: function(data,type,row,meta){
                            if(data == 'budget'){
                                return `<span class="badge bg-success">Budget</span>`;
                            }else{
                                return `<span class="badge bg-danger">UN-budget</span>`;
                            }
                        }
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
                            return `<button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal">Approve</button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>`;
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

            $('#reject_reason').on('keyup', function(){
                if($(this).val()!="")
                    $('#btn-reject').removeAttr('disabled');
                else
                    $('#btn-reject').attr('disabled','disabled');
            });

            $('#btn-approve').on('clcik', function(){

            });

        });
    </script>
@endpush
