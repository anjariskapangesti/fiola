@extends('website.layouts.main', ['title' => 'Form Hardware'])

@section('content')
    <div class="pagetitle">
        <h4>Device Request/Transfer/Scrap Form (FRM-ITD-S13-002-00)</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Forms</a></li>
                <li class="breadcrumb-item active"><a href="#">Form Hardware</a></li>
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
            <form method="post" action="{{ route('website.hardware.store') }}" class="needs-validation" novalidate id="myForm">
                @csrf
                <div class="col-lg-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">General</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="category">Category : </label>
                                    <br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="category" id="categoryType1" value="request" required>
                                        <label class="form-check-label" for="categoryType1">Request</label>
                                        <div class="invalid-feedback">Please select category</div>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="category" id="categoryType2" value="change">
                                        <label class="form-check-label" for="categoryType2">Change</label>
                                        <div class="invalid-feedback">-</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="type">Type : </label>
                                    <br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="type"
                                            id="radio01" value="cpu" required>
                                        <label class="form-check-label" for="radio01">CPU</label>
                                        <div class="invalid-feedback">Please select type</div>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="type"
                                            id="radio02" value="laptop">
                                        <label class="form-check-label" for="radio02">Laptop</label>
                                        <div class="invalid-feedback">-</div>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="type"
                                            id="radio03" value="laptop">
                                        <label class="form-check-label" for="radio03">Flashdisk</label>
                                        <div class="invalid-feedback">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">User Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="NPK" name="npk"
                                        maxlength="6" required>
                                    <div class="invalid-feedback">Please enter your NPK</div>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Full Name" name="fullname"
                                        maxlength="60" required onkeyup="formatFullName(this)">
                                    <div class="invalid-feedback">Please enter your Full Name</div>
                                </div>
                                <div class="col-md-6">
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
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Phone Number" name="phone"
                                        maxlength="14" required>
                                    <div class="invalid-feedback">Please enter your phone number</div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Company Name (If External/Non AIIA)"
                                        name="company" maxlength="100">
                                </div> --}}
                                <div class="col-md-6">
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="inputGroupPrepend">Due Date</span>
                                        <input type="date" name="due_date" class="form-control" required>
                                        <div class="invalid-feedback">Please enter your due date</div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="divDeviceBefore" style="display:none;">
                                    <input type="text" class="form-control" placeholder="ID Device Before (NTB-001 or CPU-001)" name="device_before"
                                        maxlength="60" onkeyup="convertToUppercase(this)">
                                    <div class="invalid-feedback">Please enter your ID Device Before</div>
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

        function convertToUppercase(inputElement) {
            let inputValue = inputElement.value;
            inputElement.value = inputValue.toUpperCase();
        }

        const categoryType1 = document.getElementById("categoryType1");
        const categoryType2 = document.getElementById("categoryType2");
        const divDeviceBefore = document.getElementById("divDeviceBefore");

        // Add event listener to the radio buttons
        categoryType1.addEventListener("change", toggleDivDeviceBefore);
        categoryType2.addEventListener("change", toggleDivDeviceBefore);

        function toggleDivDeviceBefore() {
            if (categoryType2.checked) {
                divDeviceBefore.style.display = "block";
            } else {
                divDeviceBefore.style.display = "none";
            }
        }

        // Trigger the initial state when the page loads
        toggleDivDeviceBefore();
    </script>
@endpush
