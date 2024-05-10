@extends('website.layouts.main', ['title' => 'Create Form Folder Access'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-1">Change Access of Folder Share Application (FRM-ITD-S13-009-00)</h4>
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
                <form method="post" action="{{ route('website.folder-access.store') }}" class="needs-validation"
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
                                <div class="row" id="div-username">
                                    <label class="col-md-6 col-sm-6 col-form-label" for="username">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="username" name="username[]"
                                                {{ old('username') }} placeholder="user@aiia.co.id" required />
                                            <label for="username">Email <span class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi Email</div>
                                        </div>
                                    </label>
                                    <label class="col-md-5 col-sm-4 col-form-label" for="department">
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
                                        class="btn btn-success btn-tambah-username col-md-1 col-sm-1 col-form-label mt-2 mb-2"
                                        id="btn-tambah-username" onclick="tambahUsername(this)"><i
                                            class="mdi mdi-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- FOLDER ACCESS INFORMATION --}}
                    <div class="card mb-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-header">Folder Access Information</h5>
                        </div>
                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <div class="folder-access-information">
                                <div class="row div-folder-access">
                                    <label class="col-md-3 col-sm-3 col-form-label" for="folder">
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-select" id="folder" name="folder[]"
                                                aria-label="Select" required>
                                                <option selected disabled value="">-- Choose Main Path --</option>
                                                @foreach ($folders as $folder)
                                                    @php
                                                        $selected = '';
                                                        if (old('folder') && old('folder') == $folder->name) {
                                                            $selected = 'selected';
                                                        }
                                                    @endphp
                                                    <option value="{{ $folder->name }}" {{ $selected }}>
                                                        {{ $folder->name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="folder">Main Path <span class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi Main Path</div>
                                        </div>
                                    </label>
                                    <label class="col-md-3 col-sm-3 col-form-label" for="subfolder">
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-select" id="subfolder" name="subfolder[]"
                                                aria-label="Select" required>
                                                <option selected disabled value="">-- Choose Folder --</option>
                                            </select>
                                            <label for="subfolder">Folder Name <span class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi Folder</div>
                                        </div>
                                    </label>
                                    <label class="col-md-3 col-sm-3 col-form-label" for="subsubfolder">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="subsubfolder"
                                                name="subsubfolder[]" {{ old('subsubfolder') }}
                                                placeholder="01_NAMA_SUBFOLDER" />
                                            <label for="subsubfolder">Subfolder (Opsional)</label>
                                            <div class="invalid-feedback">*Mohon isi Subfolder</div>
                                        </div>
                                    </label>
                                    <label class="col-md-2 col-sm-2 col-form-label" for="permission">
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
                                        class="btn btn-success btn-tambah-folder col-md-1 col-sm-1 col-form-label mt-2 mb-2"
                                        id="btn-tambah-folder" onclick="tambahFolder(this)"><i
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
@endsection

@push('styles')
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {

            @if (session()->has('success'))
                toastr['success']("{{ Session('success') }}")
            @endif

            $('.folder-access-information').on('change', 'select[name="folder[]"]', function() {
                var idFolder = $(this).val();
                var subfolderSelect = $(this).closest('.div-folder-access').find(
                    'select[name="subfolder[]"]');
                subfolderSelect.html('');
                $.ajax({
                    url: "{{ route('website.folder-access.subfolder_ajax') }}",
                    type: "GET",
                    data: {
                        folder_id: idFolder,
                    },
                    dataType: 'json',
                    success: function(result) {
                        subfolderSelect.html(
                            '<option value="">-- Choose Sub Folder --</option>');
                        $.each(result.subfolders, function(key, value) {
                            subfolderSelect.append('<option value="' + value.name +
                                '">' + value.name + '</option>');
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Error:', textStatus, errorThrown);
                        // Tambahkan kode untuk menangani error di sini, seperti menampilkan pesan kesalahan kepada pengguna
                    }
                });
            });
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
    {{-- TOMBOL TAMBAH USERNAME --}}
    <script>
        let usernameCount = 1;

        function tambahUsername(button) {
            usernameCount++;
            const divUsername = button.parentNode.cloneNode(true);
            const usernameInput = divUsername.querySelector('input[name="username[]"]');
            console.log(divUsername)
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

            const folderSelect = divFolder.querySelector('select[name="folder[]"]');
            const subfolderSelect = divFolder.querySelector('select[name="subfolder[]"]');
            const subsubfolderInput = divFolder.querySelector('input[name="subsubfolder[]"]');
            const permissionSelect = divFolder.querySelector('select[name="permission[]"]');
            // Mengubah ID dan nama atribut
            folderSelect.setAttribute('id', `folder${folderCount}`);
            // folderSelect.setAttribute('name', `folder${folderCount}[]`);

            subfolderSelect.setAttribute('id', `subfolder${folderCount}`);
            // subfolderSelect.setAttribute('name', `subfolder${folderCount}[]`);

            subsubfolderInput.setAttribute('id', `subsubfolder${folderCount}`);
            // subsubfolderInput.setAttribute('name', `subsubfolder${folderCount}[]`);

            permissionSelect.setAttribute('id', `permission_${folderCount}`);
            // permissionSelect.setAttribute('name', `permission_${folderCount}[]`);

            // Mereset nilai input
            folderSelect.selectedIndex = 0;
            subfolderSelect.innerHTML = '<option value="">-- Choose Folder --</option>';
            subsubfolderInput.value = '';
            permissionSelect.selectedIndex = 0;

            // Mengatur kembali fungsi untuk tombol tambah dan kurang
            divFolder.querySelector('.btn-tambah-folder').setAttribute('onclick', 'tambahFolder(this)');
            divFolder.querySelector('.btn-tambah-folder').classList.remove('btn-success');
            divFolder.querySelector('.btn-tambah-folder').classList.add('btn-kurang');
            divFolder.querySelector('.btn-tambah-folder').classList.add('btn-danger');
            divFolder.querySelector('.btn-tambah-folder').innerHTML = '<i class="mdi mdi-minus"></i>';
            divFolder.querySelector('.btn-tambah-folder').setAttribute('onclick', 'hapusFolder(this)');
            // divFolder.id = `div-folder-${folderCount}`;

            document.querySelector('.folder-access-information').appendChild(divFolder);
        }

        function hapusFolder(button) {
            button.parentNode.remove();
        }
    </script>
@endpush
