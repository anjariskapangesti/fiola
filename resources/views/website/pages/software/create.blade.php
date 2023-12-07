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
            <form method="post" action="{{ route('website.software.store') }}" class="needs-validation" novalidate id="myForm">
                @csrf
                <div class="col-lg-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">Category</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="category" id="categoryType1"
                                            value="software" required checked>
                                        <label class="form-check-label" for="categoryType1">Software</label>
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
                                    <input type="text" class="form-control" placeholder="App Name" name="appname"
                                        maxlength="100" required>
                                    <div class="invalid-feedback">Please enter your App Name</div>
                                </div>

                                <div class="col-md-12">
                                    <input type="text" class="form-control" placeholder="Install on (NTB-000 or CPU-000)" name="installon"
                                        maxlength="100" required>
                                    <div class="invalid-feedback">Please enter your ID Device</div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px;"
                                            name="detail" maxlength="100" required></textarea>
                                        <label for="floatingTextarea">Detail (About location installer or license or more)</label>
                                        <div class="invalid-feedback">Please fill your detail</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
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
                    {{-- <input type="hidden" name="created_dept" value="{{ $userDepartment->id }}"> --}}
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
@endpush
