@extends('website.layouts.main', ['title' => 'ITD History Request Project'])

@section('content')
    <div class="pagetitle">
        <h4>Request Project for Application</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">ITD History</a></li>
                <li class="breadcrumb-item active"><a href="#">Form Request Project</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body p-3 table table-responsive">

                    <table class="display" width="100%" id="app_table">
                        <thead>
                            <tr>
                                <th>Detail</th>
                                <th>Nama Project</th>
                                <th>Nama Requestor</th>
                                <th>Date Approved</th>
                            </tr>
                        </thead>
                    </table>
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
                        <td width="30%">NPK / Full Name</td>
                        <td>${d.npk} / ${d.fullname} </td>
                    </tr>
                    <tr>
                        <td>Dept.</td>
                        <td>${d.department} </td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td>${d.phone} </td>
                    </tr>
                    <tr>
                        <td>Nama Project</td>
                        <td>${d.nama_project ?? '-'} </td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td>
                            ${d.lampiran ? `<a href="/storage/lampiran/${d.lampiran}" class="btn btn-success" target="_blank"><i class="fas fa-download"></i> Lampiran</a>` : 'Tidak ada lampiran'}
                        </td>
                    </tr>
                    <tr>
                        <td>Kondisi Sebelum Improvement</td>
                        <td>${d.kondisi_sebelum ?? '-'}</td>
                    </tr>
                    <tr>
                        <td>Kondisi yang diharapkan</td>
                        <td>${d.kondisi_target ?? '-'}</td>
                    </tr>
                    <tr>
                        <td>Benefit yang didapat</td>
                        <td>${d.benefit ?? '-'}</td>
                    </tr>
                    <tr>
                        <td>Estimasi Cost</td>
                        <td>${d.cost ?? '-'}</td>
                    </tr>
                    <tr>
                        <td>Manager Note</td>
                        <td>${d.manager_note ?? '-'}</td>
                    </tr>
                    <tr>
                        <td>ITD Note</td>
                        <td>${d.it_note ?? '-'}</td>
                    </tr>
                    <tfoot>
                    <tr>
                        <th>Dibuat oleh</th>
                        <th>${d.user_name}</th>
                    </tr>
                    </tfoot>
                </table>
                `
                );
            }

            $(document).ready(function() {
                var table = $('#app_table').DataTable({
                    "lengthChange": true,
                    'processing': true,
                    'serverSide': true,
                    ajax: {
                        url: "{{ route('website.project.show_data_it_approval_ajax') }}",
                    },
                    columns: [{
                            className: 'dt-control',
                            orderable: false,
                            data: null,
                            defaultContent: '',
                            searchable: false,
                        },
                        {
                            data: 'nama_project',
                            name: 'nama_project'
                        },
                        {
                            data: 'fullname',
                            name: 'fullname'
                        },
                        {
                            data: 'it_approval_date',
                            name: 'it_approval_date'
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

            });
        </script>
    @endpush
