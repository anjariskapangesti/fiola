@extends('website.layouts.main', ['title' => 'Form Request Project'])

@section('content')
    <div class="pagetitle">
        <h4>Request Project for Application</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Forms</a></li>
                <li class="breadcrumb-item active"><a href="#">Form Request Project</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
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
                class="needs-validation" novalidate id="myForm">
                @csrf
                <div class="col-lg-12">

                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">Applicant Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="npk"><b>NPK</b></label>
                                    <input type="text" class="form-control" placeholder="NPK" name="npk"
                                        id="npk" maxlength="6" value="{{ Auth::user()->npk }}" disabled required>
                                    <div class="invalid-feedback">Please enter your NPK</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="fullname"><b>Nama</b></label>
                                    <input type="text" class="form-control" placeholder="Full Name" name="fullname"
                                        id="fullname" maxlength="60" value="{{ Auth::user()->name }}" disabled required
                                        onkeyup="formatFullName(this)">
                                    <div class="invalid-feedback">Please enter your Full Name</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone"><b>No. Handphone</b></label>
                                    <input type="text" class="form-control" placeholder="No Handphone" name="phone"
                                        id="phone" maxlength="60" value="{{ Auth::user()->nohp }}" disabled required
                                        onkeyup="formatFullName(this)">
                                    <div class="invalid-feedback">Please enter your Full Name</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="department"><b>Department</b></label>
                                    <input type="text" class="form-control" placeholder="Department" name="department"
                                        id="department" maxlength="14"
                                        value="{{ Auth::user()->departments->pluck('name')->implode(', ') }}" disabled
                                        required>
                                    <div class="invalid-feedback">Please enter your Department</div>
                                </div>

                                <hr style="margin-bottom: 0rem; opacity: 100%;">
                                
                                <div class="col-md-12">
                                    <label for="nama_project"><b>Nama Project</b></label>
                                    <input type="text" class="form-control" placeholder="Nama Project"
                                        name="nama_project" id="nama_project" value="{{ old('nama_project') }}" required>
                                    <div class="invalid-feedback">Please enter your Nama Project</div>
                                </div>

                                <div class="col-md-12">
                                    <label for="lampiran"><b>File PDF Konsep</b></label>
                                    <input type="file" class="form-control" placeholder="Lampiran" name="lampiran"
                                        id="lampiran" accept=".pdf" required>
                                    <div class="invalid-feedback">Please enter your File PDF Konsep</div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="kondisi_sebelum" style="height: 100px;"
                                            name="kondisi_sebelum" required>{{ old('kondisi_sebelum') }}</textarea>
                                        <label for="kondisi_sebelum"><b>Kondisi Sebelum Improvement</b></label>
                                        <div class="invalid-feedback">Please fill your Kondisi Sebelum Improvement</div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="kondisi_target" style="height: 100px;"
                                            name="kondisi_target" required>{{ old('kondisi_target') }}</textarea>
                                        <label for="kondisi_target"><b>Kondisi yang diharapkan</b></label>
                                        <div class="invalid-feedback">Please fill your Kondisi yang diharapkan</div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="benefit" style="height: 100px;"
                                            name="benefit" required>{{ old('benefit') }}</textarea>
                                        <label for="benefit"><b>Benefit yang didapat</b></label>
                                        <div class="invalid-feedback">Please fill your Benefit</div>
                                    </div>
                                </div>

                                {{-- <div class="col-md-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px;"
                                            name="alat">{{ old('alat') }}</textarea>
                                        <label for="floatingTextarea"><b>Additional Support Alat-alat</b></label>
                                        <div class="invalid-feedback">Please fill your alat</div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="cost1"><b>Estimasi Cost</b></label>
                                    <div class="d-flex justify-content-center">
                                        <input type="text" class="form-control" name="cost1" id="cost1">
                                        <h3><b>-</b></h3>
                                        <input type="text" class="form-control" name="cost2" id="cost2">
                                    </div>
                                </div> --}}

                                <div class="col-md-12">
                                    <label for="alat"><b>Additional Support Device</b></label>
                                    <div class="device-container">
                                        <div class="d-flex justify-content-center mb-3" id="div-alat">
                                            <select class="form-control alat" name="alat[]">
                                                <option value="">-- Pilih Device --</option>
                                                @foreach ($devices as $device)
                                                    <option value="{{ $device->name }} | {{ $device->cost }}">
                                                        {{ $device->name }} |
                                                        {{ $device->cost }}</option>
                                                @endforeach
                                            </select>
                                            <input type="number" class="form-control"
                                                style="max-width: 100px; margin-left: 5px; margin-right: 5px;"
                                                placeholder="Qty" name="qty[]" min="1">
                                            <span class="input-group-text" id="unit"
                                                style="margin-right: 5px;">Unit</span>
                                            <button type="button" class="btn btn-success btn-tambah"
                                                onclick="tambahDevice(this)"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#alatModal">List Alat dan Harga</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            @include('website.layouts.approval_flow')
                        </div>
                    </div>
                    <button class="btn btn-success" type="submit" id="submitButton">Save & Submit Request</button>
                </div>
            </form>
        </div>
    </section>

    <div class="modal fade" id="alatModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>List Alat dan Harga</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Alat</th>
                                <th>Harga</th>
                                <th>Spesifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($devices as $device)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $device->name }}</td>
                                    <td>{{ $device->cost }}</td>
                                    <td>{{ $device->spesifikasi }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="{{ asset('vendor/bs-step/bs-step.css') }}" rel="stylesheet">
@endpush

@push('scripts')
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
        function updateUsername(emailInput) {
            const emailValue = emailInput.value.toLowerCase();
            emailInput.value = emailValue;
            const atIndex = emailValue.indexOf('@');
            const usernameInput = document.getElementsByName('username')[0];

            if (atIndex !== -1) {
                const username = emailValue.substring(0, atIndex);
                usernameInput.value = username;
            } else {
                usernameInput.value = '';
            }
        }
    </script>
    <script>
        const cost1 = document.getElementById('cost1');

        cost1.addEventListener('input', function(e) {
            // Menghapus semua karakter selain angka
            let formatCost1 = this.value.replace(/\D/g, '');

            // Menerapkan pemisah ribuan dengan menambahkan titik setiap 3 digit dari belakang
            formatCost1 = formatCost1.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Mengupdate nilai input dengan format yang telah dimodifikasi
            this.value = formatCost1;
        });
    </script>
    <script>
        const cost2 = document.getElementById('cost2');

        cost2.addEventListener('input', function(e) {
            // Menghapus semua karakter selain angka
            let formatCost2 = this.value.replace(/\D/g, '');

            // Menerapkan pemisah ribuan dengan menambahkan titik setiap 3 digit dari belakang
            formatCost2 = formatCost2.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Mengupdate nilai input dengan format yang telah dimodifikasi
            this.value = formatCost2;
        });
    </script>
    <script>
        let deviceCount = 1;

        function tambahDevice(button) {
            deviceCount++;
            const divAlat = button.parentNode.cloneNode(true);
            const qtyInput = divAlat.querySelector('input[name="qty[]"]');
            qtyInput.value = '';
            divAlat.querySelector('.btn-tambah').setAttribute('onclick', 'tambahDevice(this)');

            divAlat.querySelector('.btn-tambah').classList.remove('btn-success');
            divAlat.querySelector('.btn-tambah').classList.add('btn-kurang');
            divAlat.querySelector('.btn-tambah').classList.add('btn-danger');
            divAlat.querySelector('.btn-tambah').innerHTML = '<i class="fa fa-minus"></i>';
            divAlat.querySelector('.btn-tambah').setAttribute('onclick', 'hapusDevice(this)');
            divAlat.id = `div-alat-${deviceCount}`;

            document.querySelector('.device-container').appendChild(divAlat);
        }

        function hapusDevice(button) {
            button.parentNode.remove();
        }
    </script>
    <script>
        document.querySelector('.alat').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var qtyInput = this.nextElementSibling;
            if (selectedOption.value !== '') {
                qtyInput.setAttribute('required', 'required');
            } else {
                qtyInput.removeAttribute('required');
            }
        });
    </script>
@endpush
