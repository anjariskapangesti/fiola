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
            <form method="post" action="{{ route('website.folder-access.store') }}" class="needs-validation" novalidate>
                @csrf
                <div class="col-lg-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">A. Information Folder Access Permission</h5>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="inputGroupPrepend">AIIA\</span>
                                        <input type="text" name="username" class="form-control" placeholder="Username"
                                            required>
                                        <div class="invalid-feedback">Please enter your email</div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mt-1 row border bg-light">
                                        <label for="message" class="col-sm-6 col-form-label">Folder Path</label>
                                        <label for="message" class="col-sm-4 col-form-label">Permission</label>
                                    </div>
                                    <div id="dynamic-row" class="">
                                        <div class="row border p-3">
                                            <div class="col-md-3">
                                                <select name="folder" class="form-control" required>
                                                    <option selected disabled value="">-- Choose Folder --
                                                    </option>
                                                    @foreach ($folders as $folder)
                                                        <option value="{{ $folder->name }}">{{ $folder->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <select name="subfolder[]" id="" class="form-control" required>
                                                    <option value="">-- Choose Sub Folder --</option>
                                                    <option value="01_Official Report">01_Official Report</option>
                                                    <option value="02_WO_Realease">02_WO_Realease</option>
                                                </select>
                                            </div>
                                            {{-- <div class="col-md-3">
                                                <input type="text" class="form-control" placeholder="Subfolder"
                                                    name="subsubfolder" maxlength="60" required>
                                            </div> --}}
                                            <div class="col-sm-4">
                                                <select name="permission[]" id="" class="form-control" required>
                                                    <option value="">-- Choose Permission --</option>
                                                    <option value="Read-only">Read-only</option>
                                                    <option value="Modify">Modify</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="button" class="btn btn-light border btn-sm btn-tambah">Add
                                                    Row</button>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-floating mb-3 mt-3">
                                                <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px;"
                                                    name="purpose" maxlength="100" required></textarea>
                                                <label for="floatingTextarea">Purpose</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">D. Approval Flow</h5>
                                    <div class="md-stepper-horizontal orange">
                                        <div class="md-step active">
                                            <div class="md-step-circle"><span>1</span></div>
                                            <div class="md-step-title">Submit Request</div>
                                            <div class="md-step-optional">This step</div>
                                            <div class="md-step-bar-left"></div>
                                            <div class="md-step-bar-right"></div>
                                        </div>
                                        <div class="md-step active">
                                            <div class="md-step-circle"><span>2</span></div>
                                            <div class="md-step-title">Approval Manager</div>
                                            <div class="md-step-optional">Request Approveal to your Manager</div>
                                            <div class="md-step-bar-left"></div>
                                            <div class="md-step-bar-right"></div>
                                        </div>
                                        <div class="md-step active">
                                            <div class="md-step-circle"><span>3</span></div>
                                            <div class="md-step-title">Approval ITD</div>
                                            <div class="md-step-bar-left"></div>
                                            <div class="md-step-bar-right"></div>
                                        </div>
                                        <div class="md-step active">
                                            <div class="md-step-circle"><span>4</span></div>
                                            <div class="md-step-title">Approval MGR ITD</div>
                                            <div class="md-step-bar-left"></div>
                                            <div class="md-step-bar-right"></div>
                                        </div>
                                        <div class="md-step active">
                                            <div class="md-step-circle"><span>5</span></div>
                                            <div class="md-step-title">Execution</div>
                                            <div class="md-step-bar-left"></div>
                                            <div class="md-step-bar-right"></div>
                                        </div>
                                    </div>

                                </div>

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
                const html = `
            <div class="row border p-2">
                <div class="col-md-3">
                    <select name="folder" class="form-control" required>
                        <option selected disabled value="">-- Choose Folder --
                        </option>
                        @foreach ($folders as $folder)
                            <option value="{{ $folder->name }}">{{ $folder->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
                    <select name="subfolder[]" id="" class="form-control">
                        <option value="">-- Choose Sub Folder --</option>
                        <option value="01_Official Report">01_Official Report</option>
                        <option value="02_WO_Realease">02_WO_Realease</option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <select name="permission[]" id="" class="form-control" required>
                        <option value="">-- Choose Permission --</option>
                        <option value="Read-only">Read-only</option>
                        <option value="Modify">Modify</option>
                    </select>
                </div>                                         
                <div class="col-sm-2">
                    <button type="button" class="btn btn-danger btn-sm btn-hapus" data-company="astra">Delete Row</button>
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

        })
    </script>
@endpush
