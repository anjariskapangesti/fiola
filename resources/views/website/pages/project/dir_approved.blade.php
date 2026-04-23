@extends('website.layouts.main', ['title' => 'Director Approved Project'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header">Director Approved Project List</h5>
            <div class="table-responsive text-nowrap" style="padding: 0 1.25rem 0 1.25rem;">
                <table class="table table-bordered" id="app_table" width="100%">
                    <thead>
                        <tr>
                            <th width="50px">No</th>
                            <th>No. Reg</th>
                            <th>Requestor</th>
                            <th>Director</th>
                            <th>Approval Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
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
    <script src="{{ asset('vendor/moment/moment.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#app_table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ route('website.project.dir_approved_ajax') }}",
                },
                columns: [
                    { data: null, render: (data, type, row, meta) => meta.row + 1 },
                    { data: 'no_reg' },
                    { data: 'requestor' },
                    { data: 'director_name' },
                    { 
                        data: 'dir_approval_date',
                        render: (data) => moment(data).format('YYYY-MM-DD HH:mm:ss')
                    },
                    { 
                        data: 'final_status',
                        render: (data) => `<span class="badge bg-success">${data}</span>`
                    }
                ]
            });
        });
    </script>
@endpush
