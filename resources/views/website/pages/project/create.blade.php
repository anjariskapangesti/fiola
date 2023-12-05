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
            <form method="post" enctype="multipart/form-data" action="{{ route('website.project.store') }}" class="needs-validation" novalidate>
                @csrf
                <div class="col-lg-12">                                        
                    
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">Applicant Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="npk"><b>NPK</b></label>
                                    <input type="text" class="form-control" placeholder="NPK" name="npk"
                                        maxlength="6" value="{{ Auth::user()->npk }}" readonly required>
                                    <div class="invalid-feedback">Please enter your NPK</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="fullname"><b>Nama</b></label>
                                    <input type="text" class="form-control" placeholder="Full Name" name="fullname"
                                        maxlength="60" value="{{ Auth::user()->name }}" readonly required onkeyup="formatFullName(this)">
                                    <div class="invalid-feedback">Please enter your Full Name</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone"><b>No. Handphone</b></label>
                                    <input type="text" class="form-control" placeholder="No Handphone" name="phone"
                                        maxlength="60" value="{{ Auth::user()->nohp }}" readonly required onkeyup="formatFullName(this)">
                                    <div class="invalid-feedback">Please enter your Full Name</div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <select name="department" class="form-control" required>
                                        <option selected value="">-- Choose Department --</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->name }}">{{ $department->name }} </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please choose your department</div>
                                </div> --}}
                                <div class="col-md-6">
                                    <label for="department"><b>Department</b></label>
                                    <input type="text" class="form-control" placeholder="Department" name="department"
                                        maxlength="14" value="{{ Auth::user()->departments->pluck('name')->implode(', ') }}" readonly required>
                                    <div class="invalid-feedback">Please enter your Department</div>
                                </div>
                                <hr style="margin-bottom: 0rem; opacity: 100%;">
                                <div class="col-md-12">
                                    <label for="nama_project"><b>Nama Project</b></label>
                                    <input type="text" class="form-control" placeholder="Nama Project" name="nama_project"
                                        value="{{ old('nama_project') }}" required>
                                    <div class="invalid-feedback">Please enter your Nama Project</div>
                                </div>
                                <div class="col-md-12">
                                    <label for="lampiran"><b>Lampiran</b></label>
                                    <input type="file" class="form-control" placeholder="Lampiran" name="lampiran" accept=".xlsx, .xls, .pdf">
                                    <div class="invalid-feedback">Please enter your Lampiran</div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px;"
                                            name="kondisi_sebelum" required>{{ old('kondisi_sebelum') }}</textarea>
                                        <label for="floatingTextarea">Kondisi Sebelum Improvement</label>
                                        <div class="invalid-feedback">Please fill your Kondisi Sebelum Improvement</div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px;"
                                            name="kondisi_target" required>{{ old('kondisi_target') }}</textarea>
                                        <label for="floatingTextarea">Kondisi yang diharapkan</label>
                                        <div class="invalid-feedback">Please fill your Kondisi yang diharapkan</div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px;"
                                            name="benefit" required>{{ old('benefit') }}</textarea>
                                        <label for="floatingTextarea">Benefit</label>
                                        <div class="invalid-feedback">Please fill your Benefit</div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px;"
                                            name="alat">{{ old('alat') }}</textarea>
                                        <label for="floatingTextarea">Additional Support Alat-alat</label>
                                        <div class="invalid-feedback">Please fill your alat</div>
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
@endpush
