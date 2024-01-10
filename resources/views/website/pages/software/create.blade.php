@extends('website.layouts.main', ['title' => 'Form Software'])

@section('content')
    <div class="pagetitle">
        <h4>Standard Setting Change (Software Installation) Form (FRM-ITD-S13-005-00)</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Forms</a></li>
                <li class="breadcrumb-item active"><a href="#">Form Software</a></li>
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
            <form method="post" action="{{ route('website.software.store') }}" class="needs-validation" novalidate
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
                            <h5 class="card-title">Category</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="category" id="install"
                                            value="Install" required>
                                        <label class="form-check-label" for="install">Install</label>
                                        <div class="invalid-feedback">Please select category type</div>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="category" id="uninstall"
                                            value="Uninstall" required>
                                        <label class="form-check-label" for="uninstall">Uninstall</label>
                                        <div class="invalid-feedback">Please select category type</div>
                                    </div>
                                    {{-- <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="category_type" id="categoryType2"
                                            value="uncategory">
                                        <label class="form-check-label" for="categoryType2">Un category</label>
                                        <div class="invalid-feedback">-</div>
                                    </div> --}}
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="form_type"
                                            id="inlineRadioType1" value="registration" required>
                                        <label class="form-check-label" for="inlineRadioType1">Registration</label>
                                        <div class="invalid-feedback">Please select request type</div>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="form_type"
                                            id="inlineRadioType3" value="deletion">
                                        <label class="form-check-label" for="inlineRadioType3">Deletion</label>
                                        <div class="invalid-feedback">-</div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">Software Information</h5>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="appname"><b>Nama Aplikasi</b></label>
                                    <input type="text" class="form-control" placeholder="App Name" name="appname"
                                        id="appname" maxlength="100" required>
                                    <div class="invalid-feedback">Please enter your App Name</div>
                                </div>

                                <div class="col-md-12">
                                    <label for="installon"><b>Device</b></label>
                                    <input type="text" class="form-control" placeholder="NTB-000 or CPU-000 or etc."
                                        name="installon" maxlength="100" required>
                                    <div class="invalid-feedback">Please enter your ID Device</div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="detail" style="height: 100px;"
                                            name="detail" required></textarea>
                                        <label for="detail"><b>Detail (About location installer or license or
                                            more)</b></label>
                                        <div class="invalid-feedback">Please fill your detail</div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="purpose" style="height: 100px;"
                                            name="purpose" required></textarea>
                                        <label for="purpose"><b>Purpose</b></label>
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
                    {{-- <input type="hidden" name="created_dept" value="{{ $userDepartment->id }}"> --}}
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
@endpush
