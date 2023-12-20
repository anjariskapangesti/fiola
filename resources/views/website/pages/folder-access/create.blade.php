@extends('website.layouts.main', ['title' => 'Form Folder Access'])

@section('content')
    <div class="pagetitle">
        <h4>Change Access of Folder Share Application (FRM-ITD-S13-009-00)</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Forms</a></li>
                <li class="breadcrumb-item active"><a href="#">Form Folder Access</a></li>
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
            <form method="post" action="{{ route('website.folder-access.store') }}" class="needs-validation" novalidate
                id="myForm">
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
                                    <label for="fullname"><b>Nama Lengkap</b></label>
                                    <input type="text" class="form-control" placeholder="Full Name" name="fullname"
                                        id="fullname" maxlength="60" value="{{ Auth::user()->name }}" disabled required
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
                                <div class="col-md-6">
                                    <label for="phone"><b>No. Handphone</b></label>
                                    <input type="text" class="form-control" placeholder="No Handphone" name="phone"
                                        id="phone" maxlength="60" value="{{ Auth::user()->nohp }}" disabled required
                                        onkeyup="formatFullName(this)">
                                    <div class="invalid-feedback">Please enter your Full Name</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">Account Information</h5>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="div-username"><b>Email</b></label>
                                    <div class="account-information">
                                        <div class="d-flex justify-content-center mb-3" id="div-username">
                                            <input type="email" name="username[]" class="form-control"
                                                style="margin-right: 5px;" placeholder="email@aiia.co.id" required
                                                onkeyup="convertToLowercase(this)">

                                            <select class="form-control department" name="department[]"
                                                style="margin-right: 5px;" required>
                                                <option value="">-- Department --</option>
                                                @foreach ($departments as $department)
                                                    @if ($department->id < 19 || $department->id > 27)
                                                        <option value="{{ $department->name }}">{{ $department->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-success btn-tambah-username"
                                                onclick="tambahUsername(this)"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="col-md-6">
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="inputGroupPrepend">AIIA\</span>
                                        <input type="email" name="username" class="form-control"
                                            placeholder="email@aiia.co.id" required onkeyup="convertToLowercase(this)">
                                        <div class="invalid-feedback">Please enter your email</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="npk"><b>Department</b></label>
                                    <select name="department" class="form-control" required data-toggle="tooltip"
                                        data-placement="top" title="Pilih department">
                                        <option selected disabled value="">-- Choose Department --</option>
                                        @foreach ($departments as $department)
                                            @if ($department->id < 19 || $department->id > 27)
                                                <option value="{{ $department->name }}">{{ $department->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please choose your department</div>
                                </div> --}}

                            </div>
                        </div>
                    </div>

                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">Folder Access Information</h5>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="div-folder"><b>Folder Path</b></label>
                                    <div class="folder-access-information">
                                        <div class="d-flex justify-content-center mb-3 div-folder" id="div-folder">
                                            <select name="folder[]" id="folder" class="form-control"
                                                style="margin-right: 5px;" required>
                                                <option selected disabled value="">-- Choose Main Path --
                                                </option>
                                                @foreach ($folders as $folder)
                                                    <option value="{{ $folder->name }}">{{ $folder->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">Please select the folder</div>

                                            <select name="subfolder[]" id="subfolder" class="form-control"
                                                style="margin-right: 5px;" required>
                                                <option value="">-- Choose Folder --</option>
                                            </select>
                                            <div class="invalid-feedback">Please select the subfolder</div>

                                            <input type="text" class="form-control" style="margin-right: 5px;"
                                                placeholder="Subfolder (Optional)" name="subsubfolder[]" maxlength="60">

                                            <select name="permission[]" id="" class="form-control"
                                                style="margin-right: 5px;" required>
                                                <option value="">-- Choose Permission --</option>
                                                <option value="Read-only">Read-only</option>
                                                <option value="Modify">Modify</option>
                                            </select>

                                            <div class="invalid-feedback">Please select the permission</div>
                                            <button type="button" class="btn btn-success btn-tambah-folder"
                                                onclick="tambahFolder(this)"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12  mt-0">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px;"
                                            name="purpose" maxlength="100" required></textarea>
                                        <label for="floatingTextarea">Purpose</label>
                                        <div class="invalid-feedback">Please fill your purpose</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title mb-1">Folder Access Information</h5>
                            <div class="row g-3">
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <div class="row border bg-light">
                                            <label for="message" class="col-sm-9 col-form-label">Folder Path</label>
                                            <label for="message" class="col-sm-3 col-form-label">Permission</label>
                                        </div>
                                        <div id="dynamic-row" class="">
                                            <div class="row border p-2">
                                                <div class="col-md-3">
                                                    <select name="folder[]" id="folder" class="form-control" required>
                                                        <option selected disabled value="">-- Choose Main Path --
                                                        </option>
                                                        @foreach ($folders as $folder)
                                                            <option value="{{ $folder->name }}">{{ $folder->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">Please select the folder</div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <select name="subfolder[]" id="subfolder" class="form-control"
                                                        required>
                                                        <option value="">-- Choose Folder --</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please select the subfolder</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control"
                                                        placeholder="Subfolder (Optional)" name="subsubfolder[]"
                                                        maxlength="60">
                                                </div>
                                                <div class="col-sm-2">
                                                    <select name="permission[]" id="" class="form-control"
                                                        required>
                                                        <option value="">-- Choose Permission --</option>
                                                        <option value="Read-only">Read-only</option>
                                                        <option value="Modify">Modify</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please select the permission</div>
                                                </div>
                                                <div class="col-sm-1">
                                                    <button type="button"
                                                        class="btn btn-success border btn-sm btn-tambah">Add
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12  mt-0">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px;"
                                            name="purpose" maxlength="100" required></textarea>
                                        <label for="floatingTextarea">Purpose</label>
                                        <div class="invalid-feedback">Please fill your purpose</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div> --}}

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

@endsection

@push('styles')
    <link href="{{ asset('vendor/bs-step/bs-step.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    {{-- <script>
    $(document).on('change', 'select[id^="folder_"]', function() {
        var folder_id = this.value;
        var currentSubfolder = $(this).closest('.folder-access-information').find('select[name^="subfolder_"]');
        console.log(folder_id, currentSubfolder);

        currentSubfolder.html('');
        $.ajax({
            url: "{{ route('website.folder-access.get_data_subfolder') }}",
            type: "GET",
            data: {
                folder_id: folder_id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(result) {
                currentSubfolder.html('<option value="">-- Choose Folder --</option>');
                $.each(result.subfolders, function(key, value) {
                    currentSubfolder.append('<option value="' + value.name + '">' + value.name + '</option>');
                });
            }
        });
    });

</script> --}}
    <script>
        $(document).ready(function() {

            @if (session()->has('success'))
                toastr['success']("{{ Session('success') }}")
            @endif

            // $('#folder').on('change', function() {
            //     var folder_id = this.value;
            //     $("#subfolder").html('');
            //     $.ajax({
            //         url: "{{ route('website.folder-access.subfolder_ajax') }}",
            //         type: "GET",
            //         data: {
            //             folder_id: folder_id,
            //             _token: '{{ csrf_token() }}'
            //         },
            //         dataType: 'json',
            //         success: function(result) {
            //             $('#subfolder').html('<option value="">-- Choose Folder --</option>');
            //             $.each(result.subfolders, function(key, value) {
            //                 $("#subfolder").append('<option value="' + value
            //                     .name + '">' +
            //                     value.name + '</option>');
            //             });
            //         }
            //     });
            // });

            $('#dynamic-row').on('click', '.btn-tambah', function() {
                const rowCounter = $('.row.border').length + 1;

                const html = `
            <div class="row border p-2">
                <div class="col-md-3">
                    <select name="folder[]" id="folder${rowCounter}" class="form-control" required>
                        <option selected disabled value="">-- Choose Folder --
                        </option>
                        @foreach ($folders as $folder)
                            <option value="{{ $folder->name }}">{{ $folder->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Please select the folder</div>
                </div> 
                <div class="col-sm-3">
                    <select name="subfolder[]" id="subfolder${rowCounter}" class="form-control" required>
                        <option value="">-- Choose Subfolder --</option>                                                    
                    </select>
                    <div class="invalid-feedback">Please select the subfolder</div>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Subfolder (Optional)"
                        name="subsubfolder[]" maxlength="60">
                </div>
                <div class="col-sm-2">
                    <select name="permission[]" id="" class="form-control" required>
                        <option value="">-- Choose Permission --</option>
                        <option value="Read-only">Read-only</option>
                        <option value="Modify">Modify</option>
                    </select>
                    <div class="invalid-feedback">Please select the permission</div>
                </div>                                         
                <div class="col-sm-1">
                    <button type="button" class="btn btn-danger btn-sm btn-hapus" data-company="astra">Delete</button>
                </div>
            </div>  
        `
                $('#dynamic-row').prepend(html);

            })

            $('#dynamic-row').on('click', '.btn-hapus', function() {
                if (confirm('Delete this row?'))
                    $(this).parent().parent().remove()
            })

            $('#dynamic-row').on('click', '.date-input', function() {
                var today = new Date();
                var maxDate = new Date(today.getFullYear(), today.getMonth() + 3, today.getDate());
                var formattedMaxDate = maxDate.toISOString().split('T')[0];
                $(this).attr('max', formattedMaxDate);
            })

            $('#dynamic-row').on('change', 'select[name="folder[]"]', function() {
                var idFolder = $(this).val();
                var subfolderSelect = $(this).closest('.row').find('select[name="subfolder[]"]');
                subfolderSelect.html('');

                $.ajax({
                    url: "{{ route('website.folder-access.subfolder_ajax') }}",
                    type: "GET",
                    data: {
                        folder_id: idFolder,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        subfolderSelect.html(
                            '<option value="">-- Choose Sub Folder --</option>');
                        $.each(result.subfolders, function(key, value) {
                            subfolderSelect.append('<option value="' + value.name +
                                '">' + value.name + '</option>');
                        });
                    }
                });
            });

            $('.folder-access-information').on('change', 'select[name="folder[]"]', function() {
                var idFolder = $(this).val();
                var subfolderSelect = $(this).closest('.div-folder').find('select[name="subfolder[]"]');
                subfolderSelect.html('');
                $.ajax({
                    url: "{{ route('website.folder-access.subfolder_ajax') }}",
                    type: "GET",
                    data: {
                        folder_id: idFolder,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        subfolderSelect.html(
                            '<option value="">-- Choose Sub Folder --</option>');
                        $.each(result.subfolders, function(key, value) {
                            subfolderSelect.append('<option value="' + value.name +
                                '">' + value.name + '</option>');
                        });
                    }
                });
            });

        })

        function convertToLowercase(element) {
            element.value = element.value.toLowerCase();
        }
    </script>

    // TOMBOL TAMBAH USERNAME //
    <script>
        let usernameCount = 1;

        function tambahUsername(button) {
            usernameCount++;
            const divDepartment = button.parentNode.cloneNode(true);
            const usernameInput = divDepartment.querySelector('input[name="username[]"]');
            usernameInput.value = '';
            divDepartment.querySelector('.btn-tambah-username').setAttribute('onclick', 'tambahUsername(this)');

            divDepartment.querySelector('.btn-tambah-username').classList.remove('btn-success');
            divDepartment.querySelector('.btn-tambah-username').classList.add('btn-kurang');
            divDepartment.querySelector('.btn-tambah-username').classList.add('btn-danger');
            divDepartment.querySelector('.btn-tambah-username').innerHTML = '<i class="fa fa-minus"></i>';
            divDepartment.querySelector('.btn-tambah-username').setAttribute('onclick', 'hapusUsername(this)');
            divDepartment.id = `div-department-${usernameCount}`;

            document.querySelector('.account-information').appendChild(divDepartment);
        }

        function hapusUsername(button) {
            button.parentNode.remove();
        }

        document.querySelector('.department').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var usernameInput = this.nextElementSibling;
            if (selectedOption.value !== '') {
                usernameInput.setAttribute('required', 'required');
            } else {
                usernameInput.removeAttribute('required');
            }
        });
    </script>

    // TOMBOL TAMBAH FOLDER //
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
            divFolder.querySelector('.btn-tambah-folder').innerHTML = '<i class="fa fa-minus"></i>';
            divFolder.querySelector('.btn-tambah-folder').setAttribute('onclick', 'hapusFolder(this)');
            // divFolder.id = `div-folder-${folderCount}`;

            document.querySelector('.folder-access-information').appendChild(divFolder);
        }

        function hapusFolder(button) {
            button.parentNode.remove();
        }

        document.querySelector('.department').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var folderInput = this.nextElementSibling;
            if (selectedOption.value !== '') {
                folderInput.setAttribute('required', 'required');
            } else {
                folderInput.removeAttribute('required');
            }
        });
    </script>
@endpush