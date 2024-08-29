@extends('website.layouts.main', ['title' => 'Create Form Akses Sistem'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between mb-2">
            <h4 class="py-1">Form Akses Sistem (FRM-ITD-S13-046-00)</h4>
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
                <form method="post" action="{{ route('website.akses_sistem.store') }}" class="needs-validation"
                    id="myForm" novalidate>
                    @csrf
                    <div class="card mb-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-header">Applicant Information</h5>
                        </div>
                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <div class="row mb-3">
                                <label class="col-sm-6 col-form-label" for="npk_pic">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="npk_pic" name="npk_pic"
                                            value="{{ Auth::user()->npk }}" placeholder="000000" disabled />
                                        <label for="npk_pic">NPK <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-6 col-form-label" for="name_pic">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="name_pic" name="name_pic"
                                            value="{{ Auth::user()->name }}" placeholder="Device Name" disabled />
                                        <label for="name_pic">Name <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-6 col-form-label" for="department_pic">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="department_pic" name="department_pic"
                                            value="{{ Auth::user()->departments->pluck('name')->implode(', ') }}"
                                            placeholder="Department Name" disabled />
                                        <label for="department_pic">Department <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-6 col-form-label" for="phone_pic">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="phone_pic" name="phone_pic"
                                            value="{{ Auth::user()->nohp }}" placeholder="081234567890" disabled />
                                        <label for="phone_pic">Phone Number <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    {{-- USER INFORMATION --}}
                    <div class="card mb-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-header">User Information</h5>
                        </div>
                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <div class="user-information">
                                <div class="row">
                                    <label class="col-md-2 col-sm-2 col-form-label" for="npk">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="npk" name="npk[]"
                                                {{ old('npk') }} placeholder="" required />
                                            <label for="npk">NPK <span class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi NPK</div>
                                        </div>
                                    </label>

                                    <label class="col-md-3 col-sm-3 col-form-label" for="name">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="name" name="name[]"
                                                {{ old('name') }} placeholder="" required
                                                onkeyup="formatFullName(this)" />
                                            <label for="name">Nama <span class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi Nama</div>
                                        </div>
                                    </label>

                                    <label class="col-md-2 col-sm-2 col-form-label" for="email">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="email" name="email[]"
                                                {{ old('email') }} placeholder="(Opsional Jika Ada)" />
                                            <label for="email">Email</label>
                                            <div class="invalid-feedback">*Mohon isi Email</div>
                                        </div>
                                    </label>

                                    <label class="col-md-4 col-sm-4 col-form-label" for="department">
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-select" id="department" name="department[]"
                                                aria-label="Select" required>
                                                <option selected disabled value="">-- Choose Department --</option>
                                                @foreach ($departments as $department)
                                                    @php
                                                        $selected = '';
                                                        if (
                                                            old('department') &&
                                                            old('department') == $department->name
                                                        ) {
                                                            $selected = 'selected';
                                                        }
                                                    @endphp
                                                    <option value="{{ $department->name }}" {{ $selected }}>
                                                        {{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="department">Department Name <span
                                                    class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi Department</div>
                                        </div>
                                    </label>

                                    <button type="button"
                                        class="btn btn-success btn-tambah-user col-md-1 col-sm-1 col-form-label mt-2 mb-2"
                                        id="btn-tambah-user" onclick="tambahUser(this)"><i
                                            class="mdi mdi-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- DEVICE INFORMATION --}}
                    <div class="card mb-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-header">System / Application Information</h5>
                        </div>
                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <div class="barang-information">
                                <div class="row">
                                    <label class="col-md-11 col-sm-11 col-form-label" for="app_name">
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-select" id="app_name" name="app_name[]"
                                                aria-label="Select" required>
                                                <option selected disabled value="">-- Choose App --</option>
                                                @foreach ($apps as $app)
                                                    @php
                                                        $selected = '';
                                                        if (old('app_name') && old('app_name') == $app->name) {
                                                            $selected = 'selected';
                                                        }
                                                    @endphp
                                                    <option value="{{ $app->name }}" {{ $selected }}>
                                                        {{ $app->name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="app_name">App Name <span class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi App</div>
                                        </div>
                                    </label>

                                    <button type="button"
                                        class="btn btn-success btn-tambah-barang col-md-1 col-sm-1 col-form-label mt-2 mb-2"
                                        id="btn-tambah-barang" onclick="tambahBarang(this)"><i
                                            class="mdi mdi-plus"></i></button>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-12 col-form-label" for="purpose">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control auto-resize" id="purpose" name="purpose" placeholder="Reason" required>{{ old('purpose') }}</textarea>
                                        <label for="purpose">Purpose <span class="text-danger">*</span></label>
                                        <div class="invalid-feedback">*Mohon isi Purpose</div>
                                    </div>
                                </label>
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
                        $guide = App\Models\Guide::where('form_name', 'form_folder_access')->first();
                        $imageUrl = $guide ? asset('storage/' . $guide->lampiran) : null;
                    @endphp
                    @if ($imageUrl)
                        <img src="{{ $imageUrl }}" alt="Guide Image" id="zoomable-image">
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var image = document.getElementById('zoomable-image');
                                var viewer = new Viewer(image, {
                                    // Viewer options
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
@endsection

@push('styles')
    {{-- STYLE GUIDE MODAL --}}
    <link rel="stylesheet" href="{{ asset('vendor/viewer/viewer.min.css') }}">
    <style>
        #zoomable-image {
            max-width: 100%;
            max-height: 100%;
            cursor: pointer;
        }
    </style>
    {{-- END STYLE GUIDE MODAL --}}
    {{-- <link href="{{ asset('vendor/select2/select2.min.css') }}" rel="stylesheet" /> --}}
@endpush

@push('scripts')
    {{-- <script src="{{ asset('vendor/select2/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#app_name').select2({
                maximumSelectionLength: 2,
                // placeholder: '-- Select --',
                // allowClear: true,
                // theme: 'bootstrap5'
            });
        });
    </script> --}}
    {{-- SCRIPT GUIDE MODAL --}}
    <script src="{{ asset('vendor/viewer/viewer.min.js') }}"></script>
    {{-- END SCRIPT GUIDE MODAL --}}
    <script>
        $(document).ready(function() {

            @if (session()->has('success'))
                toastr['success']("{{ Session('success') }}")
            @endif
        })
    </script>
    <script>
        const textarea = document.querySelector('.auto-resize');

        textarea.addEventListener('input', function() {
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
            var form = document.getElementById('myForm');
            var submitButton = document.getElementById('submitButton');
            var spinner = '<i class="mdi mdi-loading spin"></i>';

            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    event.preventDefault();
                } else {
                    submitButton.setAttribute('disabled', 'true');
                    submitButton.innerHTML = spinner + ' Submitting...';
                }
            });

            form.addEventListener('input', function() {
                if (form.checkValidity()) {
                    submitButton.removeAttribute('disabled');
                    submitButton.innerHTML = 'Submit';
                }
            });
        });
    </script>

    {{-- TOMBOL TAMBAH USER --}}
    <script>
        let userCount = 1;

        function tambahUser(button) {
            userCount++;
            const divUser = button.parentNode.cloneNode(true);

            // Clear all input fields in the cloned row
            divUser.querySelectorAll('input').forEach(input => {
                input.value = '';
            });

            // Change the add button to a remove button in the new row
            divUser.querySelector('.btn-tambah-user').setAttribute('onclick', 'hapusUser(this)');
            divUser.querySelector('.btn-tambah-user').classList.remove('btn-success');
            divUser.querySelector('.btn-tambah-user').classList.add('btn-danger');
            divUser.querySelector('.btn-tambah-user').innerHTML = '<i class="mdi mdi-minus"></i>';

            // Append the new row to the form
            document.querySelector('.user-information').appendChild(divUser);
        }

        function hapusUser(button) {
            button.parentNode.remove();
        }
    </script>

    {{-- TOMBOL TAMBAH BARANG --}}
    <script>
        let barangCount = 1;

        function tambahBarang(button) {
            barangCount++;
            const divBarang = button.parentNode.cloneNode(true);

            // Clear all input fields in the cloned row
            divBarang.querySelectorAll('input').forEach(input => {
                input.value = '';
            });

            // Change the add button to a remove button in the new row
            divBarang.querySelector('.btn-tambah-barang').setAttribute('onclick', 'hapusBarang(this)');
            divBarang.querySelector('.btn-tambah-barang').classList.remove('btn-success');
            divBarang.querySelector('.btn-tambah-barang').classList.add('btn-danger');
            divBarang.querySelector('.btn-tambah-barang').innerHTML = '<i class="mdi mdi-minus"></i>';

            // Append the new row to the form
            document.querySelector('.barang-information').appendChild(divBarang);
        }

        function hapusBarang(button) {
            button.parentNode.remove();
        }
    </script>
@endpush
