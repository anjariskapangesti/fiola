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
            <form method="post" action="{{ route('website.folder-access.store') }}" class="needs-validation" novalidate id="myForm">
                @csrf
                <div class="col-lg-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">Account Information</h5>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="input-group has-validation">
                                        {{-- <span class="input-group-text" id="inputGroupPrepend">AIIA\</span> --}}
                                        <input type="email" name="username" class="form-control" placeholder="email@aiia.co.id"
                                            required onkeyup="convertToLowercase(this)">
                                        <div class="invalid-feedback">Please enter your email</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-2">
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
                                                    <select name="subfolder[]" id="subfolder" class="form-control" required>
                                                        <option value="">-- Choose Folder --</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please select the subfolder</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" placeholder="Subfolder (Optional)"
                                                        name="subsubfolder[]" maxlength="60">
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
                    </div>

                    <div class="card">
                        <div class="card-body">
                            @include('website.layouts.approval_flow')
                        </div>
                    </div>
                    <button class="btn btn-success" type="submit">Save & Submit Request</button>
                </div>
            </form>
        </div>
    </section>

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

        })

        function convertToLowercase(element) {
            element.value = element.value.toLowerCase();
        }
    </script>
@endpush
