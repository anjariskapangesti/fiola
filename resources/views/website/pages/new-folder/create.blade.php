@extends('website.layouts.main', ['title' => 'Create Form New Folder'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between mb-2">
            <h4 class="py-1">File Server Folder Add/Change/Delete Form (FRM-ITD-S13-003-00)</h4>
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
                <form method="post" action="{{ route('website.new-folder.store') }}" class="needs-validation"
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

                    {{-- FOLDER ACCESS INFORMATION --}}
                    <div class="card mb-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-header">New Folder Information</h5>
                        </div>
                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <div class="new-folder-information">
                                <div class="row div-new-folder">
                                    <label class="col-md-6 col-sm-12 col-form-label" for="foldername">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="foldername" name="foldername[]"
                                                {{ old('foldername') }} placeholder="01_NAMA_FOLDER" required
                                                onkeyup="convertToUppercase(this)" />
                                            <label for="foldername">New Folder Name <span
                                                    class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi Nama Folder</div>
                                        </div>
                                    </label>
                                    <label class="col-md-5 col-sm-12 col-form-label" for="mainpath">
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-select" id="mainpath" name="mainpath[]" aria-label="Select"
                                                required>
                                                <option selected disabled value="">-- Choose Main Path --</option>
                                                @foreach ($folders as $folder)
                                                    @php
                                                        $selected = '';
                                                        if (old('mainpath') && old('mainpath') == $folder->name) {
                                                            $selected = 'selected';
                                                        }
                                                    @endphp
                                                    <option value="{{ $folder->name }}" {{ $selected }}>
                                                        {{ $folder->name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="mainpath">Main Path <span class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi Main Path</div>
                                        </div>
                                    </label>

                                    <button type="button"
                                        class="btn btn-success btn-tambah-folder col-md-1 col-sm-12 col-form-label mt-2 mb-2"
                                        id="btn-tambah-folder" onclick="tambahFolder(this)"><i
                                            class="mdi mdi-plus"></i></button>
                                </div>
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
                                <div class="row" id="div-username">
                                    <label class="col-md-4 col-sm-12 col-form-label" for="username">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="username" name="username[]"
                                                {{ old('username') }} placeholder="user@aiia.co.id" required
                                                onkeyup="convertToLowercase(this)" />
                                            <label for="username">Email <span class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi Email</div>
                                        </div>
                                    </label>
                                    <label class="col-md-4 col-sm-12 col-form-label" for="department">
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
                                    <label class="col-md-3 col-sm-12 col-form-label" for="permission">
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-select" id="permission" name="permission[]"
                                                aria-label="Select" required>
                                                <option selected disabled value="">-- Choose Permission --</option>
                                                <option value="Modify">Modify</option>
                                                <option value="Readonly">Readonly</option>
                                            </select>
                                            <label for="permission">Permission <span class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi Permission</div>
                                        </div>
                                    </label>
                                    <button type="button"
                                        class="btn btn-success btn-tambah-username col-md-1 col-sm-12 col-form-label mt-2 mb-2"
                                        id="btn-tambah-username" onclick="tambahUsername(this)"><i
                                            class="mdi mdi-plus"></i></button>
                                </div>
                            </div>
                            <label class="col-sm-12 col-form-label" for="purpose">
                                <div class="form-floating form-floating-outline">
                                    <textarea class="form-control auto-resize" id="purpose" name="purpose" placeholder="Reason" required>{{ old('purpose') }}</textarea>
                                    <label for="purpose">Purpose <span class="text-danger">*</span></label>
                                    <div class="invalid-feedback">*Mohon isi Purpose</div>
                                </div>
                            </label>
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
                        $guide = App\Models\Guide::where('form_name', 'form_new_folder')->first();
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
@endpush

@push('scripts')
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

        function convertToUppercase(element) {
            element.value = element.value.toUpperCase();
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
    {{-- TOMBOL TAMBAH USERNAME --}}
    <script>
        let usernameCount = 1;

        function tambahUsername(button) {
            usernameCount++;
            const divUsername = button.parentNode.cloneNode(true);
            const usernameInput = divUsername.querySelector('input[name="username[]"]');
            usernameInput.value = '';
            divUsername.querySelector('.btn-tambah-username').setAttribute('onclick', 'tambahUsername(this)');

            divUsername.querySelector('.btn-tambah-username').classList.remove('btn-success');
            divUsername.querySelector('.btn-tambah-username').classList.add('btn-kurang');
            divUsername.querySelector('.btn-tambah-username').classList.add('btn-danger');
            divUsername.querySelector('.btn-tambah-username').innerHTML = '<i class="mdi mdi-minus"></i>';
            divUsername.querySelector('.btn-tambah-username').setAttribute('onclick', 'hapusUsername(this)');
            divUsername.id = `div-department-${usernameCount}`;

            document.querySelector('.user-information').appendChild(divUsername);
        }

        function hapusUsername(button) {
            button.parentNode.remove();
        }
    </script>
    {{-- TOMBOL TAMBAH FOLDER --}}
    <script>
        let folderCount = 1;

        function tambahFolder(button) {
            folderCount++;
            const divFolder = button.parentNode.cloneNode(true);

            const mainpathSelect = divFolder.querySelector('select[name="mainpath[]"]');
            const foldernameInput = divFolder.querySelector('input[name="foldername[]"]');
            // Mengubah ID dan nama atribut
            // mainpathSelect.setAttribute('name', `folder${folderCount}[]`);

            mainpathSelect.setAttribute('id', `mainpath${folderCount}`);
            // mainpathSelect.setAttribute('name', `mainpath${folderCount}[]`);

            foldernameInput.setAttribute('id', `foldername${folderCount}`);
            // foldernameInput.setAttribute('name', `foldername${folderCount}[]`);

            // permissionSelect.setAttribute('name', `permission_${folderCount}[]`);

            // Mereset nilai input
            mainpathSelect.selectedIndex = 0;
            foldernameInput.value = '';

            // Mengatur kembali fungsi untuk tombol tambah dan kurang
            divFolder.querySelector('.btn-tambah-folder').setAttribute('onclick', 'tambahFolder(this)');
            divFolder.querySelector('.btn-tambah-folder').classList.remove('btn-success');
            divFolder.querySelector('.btn-tambah-folder').classList.add('btn-kurang');
            divFolder.querySelector('.btn-tambah-folder').classList.add('btn-danger');
            divFolder.querySelector('.btn-tambah-folder').innerHTML = '<i class="mdi mdi-minus"></i>';
            divFolder.querySelector('.btn-tambah-folder').setAttribute('onclick', 'hapusFolder(this)');
            // divFolder.id = `div-folder-${folderCount}`;

            document.querySelector('.new-folder-information').appendChild(divFolder);
        }

        function hapusFolder(button) {
            button.parentNode.remove();
        }
    </script>
@endpush
