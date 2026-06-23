@extends('website.layouts.main', ['title' => 'Manager Approved Incident Report'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h5 class="card-header">IT Disaster Incident Report Form (FRM-ITD-S13-035-00)</h5>
            </div>
            <div class="row">
                @if (Session::get('info'))
                    <div class="alert alert-info">
                        {{ Session::get('info') }}
                    </div>
                @endif
            </div>
            <div class="table-responsive text-nowrap" style="padding: 0 1.25rem 0 1.25rem;">
                <table class="table table-bordered" id="app_table" width="100%">
                    <thead>
                        <tr>
                            <th width="50px">No</th>
                            <th style="max-width: 50px;">Detail</th>
                            <th>No. Reg</th>
                            <th>Requestor</th>
                            <th>Created Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    </tbody>
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
                    <input type="text" readonly class="form-control-plaintext" id="no_reg_delete">
                    <input type="hidden" id="id_delete">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="btn-delete">Yes, Delete!</button>
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
    <script src="{{ asset('vendor/moment/moment.min.js') }}"></script>

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
                    url: "{{ route('website.incident_report.manager_approved_ajax') }}",
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
                        className: 'dt-control text-center',
                        orderable: false,
                        data: null,
                        defaultContent: '',
                        searchable: false,
                    },
                    {
                        data: 'no_reg',
                        name: 'no_reg',
                    },
                    {
                        data: 'requestor',
                        name: 'requestor',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type, row, meta) {
                            return moment(data).format('YYYY-MM-DD HH:mm:ss');
                        }
                    },
                    {
                        data: 'final_status',
                        name: 'final_status',
                        render: function(data, type, row, meta) {
                            const status = (data ?? '').toString();
                            const statusLower = status.toLowerCase();

                            if (status == 'created') {
                                return `<span class="badge bg-warning">Waiting Manager Approval</span>`;
                            } else if (statusLower.includes('reject') || statusLower.includes('rejected') || statusLower.includes('not accepted') || statusLower.includes('tidak diterima')) {
                                return `<span class="badge bg-danger">${status}</span>`;
                            } else if (statusLower.includes('waiting') || statusLower.includes('pending')) {
                                return `<span class="badge bg-warning">${status}</span>`;
                            } else if (statusLower.includes('approve') || statusLower.includes('approved') || statusLower.includes('finished') || statusLower.includes('on progress') || statusLower.includes('done') || statusLower.includes('confirmed')) {
                                return `<span class="badge bg-success">${status}</span>`;
                            } else {
                                return `<span class="badge bg-danger">${status}</span>`;
                            }
                        }
                    },
                ],
            });

            function format(d) {
                return (
                    `
                    <table class="table table-bordered table-sm" style="background-color: #ebf1f2;">
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td style="width: 30px; font-weight: bold;" colspan="2">General</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Kategori</td>
                                <td>${d.kategori} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Penyebab</td>
                                <td>${d.penyebab} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Aktual Keparahan</td>
                                <td>${d.aktual_keparahan}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Penemu</td>
                                <td>${d.penemu}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Department</td>
                                <td>${d.department}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Tanggal Penemuan</td>
                                <td>${d.tanggal_penemuan}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Device/System</td>
                                <td>${d.device}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Dampak Awal</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.dampak_awal} </td>
                            </tr>
                            <tr>
                                <td style="width: 30px; font-weight: bold;" colspan="2">Detail Insiden</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Kronologi</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.kronologi} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Dampak Luas</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.dampak_luas} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Root Cause</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.root_cause} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Potensi Kelemahan</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.potensi_kelemahan} </td>
                            </tr>

                            <tr>
                                <td style="width: 30px; font-weight: bold;" colspan="2">Corrective Action</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Staff/Dept.</td>
                                <td>${d.staff_corrective_action}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Tanggal Mulai</td>
                                <td>${d.tanggal_mulai_corrective_action}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Tanggal Berakhir</td>
                                <td>${d.tanggal_berakhir_corrective_action}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Corrective Action</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.corrective_action} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Dampak Lanjutan</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.dampak_lanjutan_corrective_action} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Kondisi Bisnis</td>
                                <td>${d.kondisi_bisnis_corrective_action}</td>
                            </tr>

                            <tr>
                                <td style="width: 30px; font-weight: bold;" colspan="2">Preventive Action</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Staff/Dept.</td>
                                <td>${d.staff_preventive_action}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Tanggal Mulai</td>
                                <td>${d.tanggal_mulai_preventive_action}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Tanggal Berakhir</td>
                                <td>${d.tanggal_berakhir_preventive_action}</td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Preventive Action</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.preventive_action} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Dampak Lanjutan</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.dampak_lanjutan_preventive_action} </td>
                            </tr>
                            <tr>
                                <td style="background-color: #66a7e3; width: 30px; font-weight: bold;">Kondisi Bisnis</td>
                                <td>${d.kondisi_bisnis_preventive_action}</td>
                            </tr>
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>Manager Approval Date</td>
                                <td>${d.manager_approval_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Manager Approval By</td>
                                <td>${d.manager_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Manager Note</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.manager_note ?? '-'}</td>
                            </tr>
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>ITD Approval Date</td>
                                <td>${d.it_approval_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Approval By</td>
                                <td>${d.it_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Note</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.it_note ?? '-'}</td>
                            </tr>  
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>ITD Manager Approval Date</td>
                                <td>${d.it_mgr_approval_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Manager Approval By</td>
                                <td>${d.it_mgr_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Manager Note</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.it_mgr_note ?? '-'}</td>
                            </tr>
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>On Progress Date</td>
                                <td>${d.on_progress_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>On Progress By</td>
                                <td>${d.on_progress_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>On Progress Note</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.on_progress_note ?? '-'}</td>
                            </tr>
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>Finish Date</td>
                                <td>${d.finish_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Finish By</td>
                                <td>${d.finish_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Finish Note</td>
                                <td style="max-width: 250px; white-space: pre-wrap;">${d.finish_note ?? '-'}</td>
                            </tr>
                        </tbody>
                    </table>
                    `
                );
            }

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
        });
    </script>
@endpush
