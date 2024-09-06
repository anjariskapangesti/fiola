@extends('website.layouts.main', ['title' => 'List User'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header">List User</h5>
            <div class="table-responsive text-nowrap" style="padding: 0 1.25rem 0 1.25rem;">
                <table class="table table-bordered" id="app_table" width="100%">
                    <thead>
                        <tr>
                            <th style="max-width: 20px;">No</th>
                            <th>NPK</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department Code</th>
                            <th>No HP</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
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
    <script>
        $(document).ready(function() {
            var table = $('#app_table').DataTable({
                'lengthChange': true,
                'processing': true,
                'serverSide': false,
                'orderable': true,
                ajax: {
                    url: "{{ route('website.user.list_ajax') }}",
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
                        data: 'npk',
                        name: 'npk',
                        className: 'text-center',
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
                        data: 'department_codes',
                        name: 'department_codes',
                    },
                    {
                        data: 'nohp',
                        name: 'nohp',
                    },
                    {
                        data: 'last_online',
                        name: 'last_online',
                        render: function(data, type, row) {
                            if (row.last_online) {
                                var lastOnlineDate = new Date(row.last_online);
                                var currentTime = new Date();
                                var timeDiff = currentTime - lastOnlineDate;

                                if (timeDiff < 10 * 60 * 1000) {
                                    return `<span class="text-success">Online</span>`;
                                } else {
                                    return "Terakhir dilihat pada " + lastOnlineDate
                                        .toLocaleDateString('id-ID', {
                                            day: '2-digit',
                                            month: '2-digit',
                                            year: 'numeric'
                                        }) + ", " + lastOnlineDate
                                        .toLocaleTimeString('id-ID', {
                                            hour: '2-digit',
                                            minute: '2-digit',
                                            second: '2-digit'
                                        });
                                }
                            } else {
                                return "Belum pernah online";
                            }
                        },
                    }
                ],
            });
        });
    </script>
@endpush
