@extends('website.layouts.main', ['title' => 'IT Approved Izin Memasuki Area Level 3'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h5 class="card-header">Form Izin Memasuki Area Level 3 (FRM-HRD-S5-030-00)</h5>
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
                    url: "{{ route('website.izin.it_approved_ajax') }}",
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

            var detailsRow = [];

            $('.table tbody').on('click', 'tr td.dt-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var idx = $.inArray(tr.attr('id'), detailsRow);

                if (row.child.isShown()) {
                    tr.removeClass('details')
                    row.child.hide()
                    detailsRow.splice(idx, 1)
                } else {
                    tr.addClass('details')
                    row.child(format(row.data())).show()
                    if (idx === -1) {
                        detailsRow.push(tr.attr('id'))
                    }
                }
            })

            table.on('draw', function() {
                $.each(detailsRow, function(i, id) {
                    $('#' + id + ' td.dt-control').trigger('click')
                })
            })

            function format(d) {
                var html = `
                    <table class="table table-sm table-bordered">
                        <tbody style="background-color: #66a7e3; width: 30px; font-weight: bold; border: 2px solid black;">
                            <tr>
                                <td colspan="1" class="text-center">NPK / No. Identitas</td>    
                                <td colspan="3" class="text-center">Nama</td>    
                                <td colspan="2" class="text-center">Asal Perusahaan</td>    
                                <td colspan="2" class="text-center">No. HP</td>    
                            </tr>    
                        `

                for (let i = 0; i < d.form_izin_user.length; i++) {
                    html += `
                            <tr style="background-color: #ebf1f2;">
                                <td colspan="1">${d.form_izin_user[i].npk}</td>
                                <td colspan="3">${d.form_izin_user[i].name}</td>
                                <td colspan="2">${d.form_izin_user[i].asal_perusahaan}</td>
                                <td colspan="2">${d.form_izin_user[i].no_hp}</td>
                                `
                    html += `</tr>
                    `
                }

                html += `<tr style="background-color: #66a7e3; width: 30px; font-weight: bold;">
                            <td colspan="2" class="text-center">Nama Barang</td>    
                            <td colspan="2" class="text-center">No Device</td>    
                            <td colspan="1" class="text-center">Merk</td>    
                            <td colspan="1" class="text-center">Jumlah</td> 
                            <td colspan="1" class="text-center">Satuan</td> 
                            <td colspan="2" class="text-center">Keterangan</td> 
                        </tr>
                        `
                for (let i = 0; i < d.form_izin_barang.length; i++) {
                    html += `<tr style="background-color: #ebf1f2;">
                                <td colspan="2">${d.form_izin_barang[i].nama_barang ?? '-'}</td>
                                <td colspan="2">${d.form_izin_barang[i].no_device ?? '-'}</td>
                                <td colspan="1">${d.form_izin_barang[i].merk ?? '-'}</td>
                                <td colspan="1">${d.form_izin_barang[i].jumlah ?? '-'}</td>
                                <td colspan="1">${d.form_izin_barang[i].satuan ?? '-'}</td>
                                <td colspan="2">${d.form_izin_barang[i].keterangan ?? '-'}</td>
                                `
                    html += `</tr>
                            
                    `
                }

                html += `
                            <tr>
                                <td colspan="8" class="text-center">Lokasi</td>    
                            </tr>
                            <tr style="background-color: #ebf1f2; max-width: 250px; white-space: pre-wrap;">
                                <td colspan="8" >${d.lokasi}</td>   
                            </tr>
                            <tr>
                                <td colspan="8" class="text-center">Waktu Akses</td>    
                            </tr>
                            <tr style="background-color: #ebf1f2; max-width: 250px; white-space: pre-wrap;">
                                <td colspan="8" >${d.date_access_start} s/d ${d.date_access_end}</td>   
                            </tr>
                            <tr>
                                <td colspan="8" class="text-center">Purpose</td>    
                            </tr>
                            <tr style="background-color: #ebf1f2; max-width: 250px; white-space: pre-wrap;">
                                <td colspan="8" >${d.purpose}</td>   
                            </tr>
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>Manager Approval Date</td>
                                <td colspan="8">${d.manager_approval_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Manager Approval By</td>
                                <td colspan="8">${d.manager_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Manager Note</td>
                                <td colspan="8" style="max-width: 250px; white-space: pre-wrap;">${d.manager_note ?? '-'}</td>
                            </tr>
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>ITD Approval Date</td>
                                <td colspan="8">${d.it_approval_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Approval By</td>
                                <td colspan="8">${d.it_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Note</td>
                                <td colspan="8" style="max-width: 250px; white-space: pre-wrap;">${d.it_note ?? '-'}</td>
                            </tr>  
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>ITD Manager Approval Date</td>
                                <td colspan="8">${d.it_mgr_approval_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Manager Approval By</td>
                                <td colspan="8">${d.it_mgr_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>ITD Manager Note</td>
                                <td colspan="8" style="max-width: 250px; white-space: pre-wrap;">${d.it_mgr_note ?? '-'}</td>
                            </tr>
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>On Progress Date</td>
                                <td colspan="8">${d.on_progress_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>On Progress By</td>
                                <td colspan="8">${d.on_progress_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>On Progress Note</td>
                                <td colspan="8" style="max-width: 250px; white-space: pre-wrap;">${d.on_progress_note ?? '-'}</td>
                            </tr>
                        </tbody>
                        <tbody style="border: 2px solid black;">
                            <tr>
                                <td>Finish Date</td>
                                <td colspan="8">${d.finish_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Finish By</td>
                                <td colspan="8">${d.finish_name ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Finish Note</td>
                                <td colspan="8" style="max-width: 250px; white-space: pre-wrap;">${d.finish_note ?? '-'}</td>
                            </tr>
                        </tbody>   
                        </table>`

                return html
            }
        });
    </script>
@endpush
