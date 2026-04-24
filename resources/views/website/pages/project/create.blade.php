@extends('website.layouts.main', ['title' => 'Create Form Project'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between mb-2">
            <h4 class="py-1">Request Project for Application (FRM-ITD-S13-046-00)</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#guideModal"><i
                    class="menu-icon tf-icons mdi mdi-book-information-variant"></i>Guide</button>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Ooops..</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="post" enctype="multipart/form-data" action="{{ route('website.project.store') }}"
                    class="needs-validation" id="myForm" novalidate>
                    @csrf
                    <input type="hidden" name="is_reschedule" id="is_reschedule" value="0">
                    <input type="hidden" name="reschedule_target_id" id="reschedule_target_id" value="">

                    <div id="reschedule_indicator" class="alert alert-warning d-none mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>
                                <i class="mdi mdi-alert-circle-outline me-2"></i>
                                <strong>Reschedule Request:</strong> Mengajukan reschedule untuk project <span id="target_project_name" class="fw-bold"></span>
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="cancelReschedule()">Batalkan Reschedule</button>
                        </div>
                    </div>



                    {{-- PROJECT INFORMATION --}}
                    <div class="card mb-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-header">Project Information</h5>
                        </div>
                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <div class="user-information">
                                <div class="row" id="div-project">
                                    <label class="col-sm-12 col-form-label" for="nama_project">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="nama_project" name="nama_project"
                                                value="{{ old('nama_project') }}" placeholder="" required />
                                            <label for="nama_project">Nama Project <span
                                                    class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi Nama Project</div>
                                        </div>
                                    </label>

                                    {{-- START DATE --}}
                                    <label class="col-sm-6 col-form-label" for="start_date">
                                        <div class="form-floating form-floating-outline">
                                            <input type="date" class="form-control" id="start_date" name="start_date"
                                                value="{{ old('start_date') }}" required />
                                            <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi Start Date</div>
                                        </div>
                                    </label>

                                    {{-- END DATE --}}
                                    <label class="col-sm-6 col-form-label" for="end_date">
                                        <div class="form-floating form-floating-outline">
                                            <input type="date" class="form-control" id="end_date" name="end_date"
                                                value="{{ old('end_date') }}" required />
                                            <label for="end_date">End Date <span class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi End Date</div>
                                        </div>
                                    </label>

                                    <label class="col-sm-12 col-form-label" for="lampiran">
                                        <div class="form-floating form-floating-outline">
                                            <input type="file" class="form-control" id="lampiran" name="lampiran"
                                                value="{{ old('lampiran') }}" placeholder="" accept=".pdf" required />
                                            <label for="lampiran">File PDF Konsep <span
                                                    class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi File PDF Konsep</div>
                                        </div>
                                    </label>

                                    <label class="col-sm-12 col-form-label" for="kondisi_sebelum">
                                        <div class="form-floating form-floating-outline">
                                            <textarea class="form-control auto-resize" id="kondisi_sebelum" name="kondisi_sebelum" placeholder="" required>{{ old('kondisi_sebelum') }}</textarea>
                                            <label for="kondisi_sebelum">Kondisi Sebelum Improvement <span
                                                    class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi Kondisi Sebelum Improvement</div>
                                        </div>
                                    </label>

                                    <label class="col-sm-12 col-form-label" for="kondisi_target">
                                        <div class="form-floating form-floating-outline">
                                            <textarea class="form-control auto-resize" id="kondisi_target" name="kondisi_target" placeholder="" required>{{ old('kondisi_target') }}</textarea>
                                            <label for="kondisi_target">Kondisi yang diharapkan <span
                                                    class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi Kondisi yang diharapkan</div>
                                        </div>
                                    </label>

                                    <label class="col-sm-12 col-form-label" for="benefit">
                                        <div class="form-floating form-floating-outline">
                                            <textarea class="form-control auto-resize" id="benefit" name="benefit" placeholder="" required>{{ old('benefit') }}</textarea>
                                            <label for="benefit">Benefit yang didapat <span
                                                    class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi Benefit yang didapat</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Additional Support Device --}}
                    <div class="card mb-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-header">Additional Support Device</h5>
                        </div>
                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <div class="project-information">
                                @php
                                    $deviceCount = count(old('device', ['']));
                                    $qtyValues = old('qty', ['']);
                                    $unitValues = old('unit', ['']);
                                @endphp

                                @for ($i = 0; $i < $deviceCount; $i++)
                                    <div class="row div-project">
                                        <label class="col-md-7 col-sm-12 col-form-label" for="device{{ $i }}">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" class="form-control" id="device{{ $i }}"
                                                    name="device[]" value="{{ old('device.' . $i, '') }}"
                                                    placeholder="Input Device Name" />
                                                <label for="device{{ $i }}">Device</label>
                                                <div class="invalid-feedback">*Mohon isi Device</div>
                                            </div>
                                        </label>

                                        <label class="col-md-2 col-sm-6 col-form-label" for="qty{{ $i }}">
                                            <div class="form-floating form-floating-outline">
                                                <input type="number" class="form-control" id="qty{{ $i }}"
                                                    name="qty[]" value="{{ old('qty.' . $i, '') }}"
                                                    placeholder="QTY" />
                                                <label for="qty{{ $i }}">QTY</label>
                                            </div>
                                        </label>

                                        <label class="col-md-2 col-sm-5 col-form-label" for="unit{{ $i }}">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" class="form-control" id="unit{{ $i }}"
                                                    name="unit[]" value="{{ old('unit.' . $i, '') }}" placeholder=""
                                                    disabled />
                                                <label for="unit{{ $i }}">Unit</label>
                                            </div>
                                        </label>

                                        <button type="button"
                                            class="btn btn-success btn-tambah-device col-md-1 col-sm-1 col-form-label mt-2 mb-2"
                                            id="btn-tambah-device" onclick="tambahFolder(this)">
                                            <i class="mdi mdi-plus"></i>
                                        </button>
                                    </div>
                                @endfor
                            </div>

                            <div>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#alatModal">List Alat dan Harga</button>
                            </div>
                        </div>
                    </div>

                    @include('website.layouts.approval_flow')

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success" id="submitButton">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="alatModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>List Alat dan Harga</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="search-input" placeholder="Search...">
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Alat</th>
                                <th>Harga</th>
                                <th>Spesifikasi</th>
                            </tr>
                        </thead>
                        <tbody id="device-table-body">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="previous-button" onclick="previousPage()"
                        disabled>Previous</button>
                    <button type="button" class="btn btn-primary" id="next-button" onclick="nextPage()">Next</button>
                </div>
            </div>
        </div>
    </div>

    {{-- GUIDE MODAL --}}
    <div class="modal fade" id="guideModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>GUIDE</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @php
                        $guide = App\Models\Guide::where('form_name', 'form_project')->first();
                        $imageUrl = $guide ? asset('storage/' . $guide->lampiran) : null;
                    @endphp
                    @if ($imageUrl)
                        <img src="{{ $imageUrl }}" alt="Guide Image" id="zoomable-image">
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var image = document.getElementById('zoomable-image');
                                var viewer = new Viewer(image, {
                                    zoomable: true,
                                    scalable: true,
                                    rotatable: false,
                                    transition: false,
                                    toolbar: true,
                                });
                            });
                        </script>
                        <i class="menu-icon tf-icons mdi mdi-cursor-pointer">Klik gambar untuk memperbesar</i><i
                            class="menu-icon tf-icons mdi mdi-magnify-plus"></i>
                    @else
                        <p><b>Belum ada guide</b></p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END GUIDE MODAL --}}

    {{-- MODAL RESCHEDULE --}}
    <div class="modal fade" id="rescheduleModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white"><i class="mdi mdi-alert me-2"></i>Batas Maksimal Project Tercapai</h5>
                </div>
                <div class="modal-body">
                    <p>Maaf, untuk bulan yang Anda pilih sudah terdapat <strong>2 project terjadwal</strong>. 
                       Sesuai ketentuan, maksimal hanya diperbolehkan 2 project per bulan.</p>
                    <p>Silahkan pilih salah satu project di bawah ini untuk diajukan <strong>Reschedule</strong>:</p>
                    
                    <div class="list-group mt-3" id="project_list_reschedule">
                        <!-- Will be populated via JS -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/viewer/viewer.min.css') }}">
    <style>
        #zoomable-image {
            max-width: 100%;
            max-height: 100%;
            cursor: pointer;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('vendor/viewer/viewer.min.js') }}"></script>

    <script>
        document.getElementById('search-input').addEventListener('keyup', function() {
            let input = this.value.toLowerCase();
            let tableBody = document.getElementById('device-table-body');
            let rows = tableBody.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName('td');
                let match = false;

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].innerText.toLowerCase().indexOf(input) > -1) {
                        match = true;
                        break;
                    }
                }

                rows[i].style.display = match ? '' : 'none';
            }
        });
    </script>

    <script>
        const devices = @json($devices);
        const rowsPerPage = 5;
        let currentPage = 0;

        function renderTablePage(page) {
            const start = page * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedDevices = devices.slice(start, end);

            const tbody = document.getElementById('device-table-body');
            tbody.innerHTML = '';

            paginatedDevices.forEach((device, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${start + index + 1}</td>
                    <td>${device.name}</td>
                    <td>${device.cost}</td>
                    <td>${device.spesifikasi}</td>
                `;
                tbody.appendChild(row);
            });

            document.getElementById('previous-button').disabled = page === 0;
            document.getElementById('next-button').disabled = end >= devices.length;
        }

        function nextPage() {
            const totalPages = Math.ceil(devices.length / rowsPerPage);
            if (currentPage < totalPages - 1) {
                currentPage++;
                renderTablePage(currentPage);
            }
        }

        function previousPage() {
            if (currentPage > 0) {
                currentPage--;
                renderTablePage(currentPage);
            }
        }

        renderTablePage(currentPage);
    </script>

    <script>
        $(document).ready(function() {
            @if (session()->has('success'))
                toastr['success']("{{ Session('success') }}")
            @endif
        })
    </script>

    <script>
        const kondisi_target = document.querySelector('#kondisi_target');
        kondisi_target.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        const kondisi_sebelum = document.querySelector('#kondisi_sebelum');
        kondisi_sebelum.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        const benefit = document.querySelector('#benefit');
        benefit.addEventListener('input', function() {
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

        function convertToLowercase(element) {
            element.value = element.value.toLowerCase();
        }

        function formatFullName(element) {
            let words = element.value.toLowerCase().split(" ");
            for (let i = 0; i < words.length; i++) {
                words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1);
            }
            element.value = words.join(" ");
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('myForm');
            const submitButton = document.getElementById('submitButton');
            const spinner = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            const rescheduleModal = new bootstrap.Modal(document.getElementById('rescheduleModal'));
            let isMonthChecked = false;

            window.cancelReschedule = function() {
                document.getElementById('is_reschedule').value = '0';
                document.getElementById('reschedule_target_id').value = '';
                document.getElementById('reschedule_indicator').classList.add('d-none');
                isMonthChecked = false;
            };

            window.selectProjectForReschedule = function(id, name) {
                document.getElementById('is_reschedule').value = '1';
                document.getElementById('reschedule_target_id').value = id;
                document.getElementById('target_project_name').innerText = name;
                document.getElementById('reschedule_indicator').classList.remove('d-none');
                rescheduleModal.hide();
                isMonthChecked = true;
                document.getElementById('reschedule_indicator').scrollIntoView({ behavior: 'smooth' });
            };

            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    event.preventDefault();
                    return;
                }

                if (isMonthChecked || document.getElementById('is_reschedule').value === '1') {
                    submitButton.setAttribute('disabled', 'true');
                    submitButton.innerHTML = spinner + ' Submitting...';
                    return;
                }

                event.preventDefault();
                const startDate = document.getElementById('start_date').value;
                
                if (!startDate) return;

                submitButton.setAttribute('disabled', 'true');
                submitButton.innerHTML = spinner + ' Checking Slot...';

                fetch(`{{ route('website.project.check_month_limit') }}?start_date=${startDate}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'full') {
                            const listGroup = document.getElementById('project_list_reschedule');
                            listGroup.innerHTML = '';
                            
                            data.projects.forEach(project => {
                                const item = `
                                    <a href="javascript:void(0)" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                       onclick="selectProjectForReschedule('${project.id}', '${project.nama_project}')">
                                        <div>
                                            <h6 class="mb-1">${project.nama_project}</h6>
                                            <small class="text-muted">${project.no_reg} | Requestor: ${project.fullname}</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">Pilih untuk Reschedule</span>
                                    </a>
                                `;
                                listGroup.innerHTML += item;
                            });
                            
                            rescheduleModal.show();
                            submitButton.removeAttribute('disabled');
                            submitButton.innerHTML = 'Submit';
                        } else {
                            isMonthChecked = true;
                            form.submit();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        submitButton.removeAttribute('disabled');
                        submitButton.innerHTML = 'Submit';
                        form.submit();
                    });
            });

            form.addEventListener('input', function() {
                if (form.checkValidity()) {
                    if (submitButton.innerHTML === 'Submit') {
                        submitButton.removeAttribute('disabled');
                    }
                }
            });
            
            document.getElementById('start_date').addEventListener('change', function() {
                isMonthChecked = false;
                if (document.getElementById('is_reschedule').value === '1') {
                    cancelReschedule();
                }
            });
        });
    </script>

    <script>
        let deviceCount = {{ $deviceCount }};

        function tambahFolder(button) {
            deviceCount++;
            const divDevice = button.parentNode.cloneNode(true);

            const deviceInput = divDevice.querySelector('input[name="device[]"]');
            deviceInput.setAttribute('id', `device${deviceCount}`);
            deviceInput.value = '';

            const qtyInput = divDevice.querySelector('input[name="qty[]"]');
            qtyInput.setAttribute('id', `qty${deviceCount}`);
            qtyInput.value = '';

            const unitInput = divDevice.querySelector('input[name="unit[]"]');
            unitInput.setAttribute('id', `unit${deviceCount}`);
            unitInput.value = '';

            divDevice.querySelector('.btn-tambah-device').setAttribute('onclick', 'tambahFolder(this)');
            divDevice.querySelector('.btn-tambah-device').classList.remove('btn-success');
            divDevice.querySelector('.btn-tambah-device').classList.add('btn-kurang');
            divDevice.querySelector('.btn-tambah-device').classList.add('btn-danger');
            divDevice.querySelector('.btn-tambah-device').innerHTML = '<i class="mdi mdi-minus"></i>';
            divDevice.querySelector('.btn-tambah-device').setAttribute('onclick', 'hapusFolder(this)');

            document.querySelector('.project-information').appendChild(divDevice);
        }

        function hapusFolder(button) {
            button.parentNode.remove();
        }
    </script>
@endpush