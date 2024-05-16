@extends('website.layouts.main', ['title' => 'Create Form Hardware'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-1">Device Request/Transfer/Scrap Form (FRM-ITD-S13-002-00)</h4>
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
                <form method="post" action="{{ route('website.hardware.store') }}" class="needs-validation" id="myForm"
                    novalidate>
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

                    <div class="card mb-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-header">General</h5>
                        </div>
                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <div class="row row-bordered g-0">
                                <div class="col-md p-1">
                                    <small class="text-light fw-medium d-block">Budget Type <span
                                            class="text-danger">*</span></small>
                                    <div class="form-check form-check-inline mt-3">
                                        <input class="form-check-input" type="radio" name="budget_type" id="Budget"
                                            value="Budget" {{ old('budget_type') == 'Budget' ? 'checked' : '' }} required />
                                        <label class="form-check-label" for="Budget">Budget</label>
                                        <div class="invalid-feedback">*Mohon pilih Budget Type</div>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="budget_type" id="Unbudget"
                                            value="Unbudget" {{ old('budget_type') == 'Unbudget' ? 'checked' : '' }} />
                                        <label class="form-check-label" for="Unbudget">Unbudget</label>
                                        <div class="invalid-feedback">*</div>
                                    </div>
                                </div>
                                <div class="col-md p-1">
                                    <small class="text-light fw-medium d-block">Type <span
                                            class="text-danger">*</span></small>
                                    <div class="form-check form-check-inline mt-3">
                                        <input class="form-check-input" type="radio" name="type" id="PC"
                                            value="PC" {{ old('type') == 'PC' ? 'checked' : '' }} />
                                        <label class="form-check-label" for="PC">PC</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="type" id="Laptop"
                                            value="Laptop" {{ old('type') == 'Laptop' ? 'checked' : '' }} />
                                        <label class="form-check-label" for="Laptop">Laptop</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="type" id="HDD/SSD"
                                            value="HDD/SSD" {{ old('type') == 'HDD/SSD' ? 'checked' : '' }} />
                                        <label class="form-check-label" for="HDD/SSD">HDD/SSD</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="type" id="Other"
                                            value="Other" {{ old('type') == 'Other' ? 'checked' : '' }} />
                                        <label class="form-check-label" for="Other">Other</label>
                                        <input type="text" name="other_type" id="other_type" class="form-control"
                                            placeholder="Specify other" {{ old('type') != 'Other' ? 'disabled' : '' }}
                                            value="{{ old('other_type') }}">
                                    </div>
                                </div>
                                <div class="col-md p-1">
                                    <small class="text-light fw-medium d-block">Category <span
                                            class="text-danger">*</span></small>
                                    <div class="form-check form-check-inline mt-3">
                                        <input class="form-check-input" type="radio" name="category" id="Request"
                                            value="Request" {{ old('category') == 'Request' ? 'checked' : '' }} />
                                        <label class="form-check-label" for="Request">Request</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="category" id="Transfer"
                                            value="Transfer" {{ old('category') == 'Transfer' ? 'checked' : '' }}
                                            disabled />
                                        <label class="form-check-label" for="Transfer">Transfer</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="category" id="Scrap"
                                            value="Scrap" {{ old('category') == 'Scrap' ? 'checked' : '' }} disabled />
                                        <label class="form-check-label" for="Scrap">Scrap</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="request-section form-section" style="display: none;">
                        <div class="card mb-4">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-header">User Information</h5>
                            </div>
                            <div class="card-body demo-vertical-spacing demo-only-element">
                                <div class="row mb-3">
                                    <label class="col-sm-6 col-form-label" for="npk">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="npk" name="npk"
                                                value="{{ old('npk') }}" placeholder="000000" maxlength="6" />
                                            <label for="npk">NPK <span class="text-danger">*</span></label>
                                        </div>
                                    </label>
                                    <label class="col-sm-6 col-form-label" for="fullname">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="fullname" name="fullname"
                                                value="{{ old('fullname') }}" placeholder="Full Name"
                                                onkeyup="formatFullName(this)" />
                                            <label for="fullname">Name <span class="text-danger">*</span></label>
                                        </div>
                                    </label>
                                    <label class="col-sm-6 col-form-label" for="department">
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-select" id="department" name="department"
                                                aria-label="Select">
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
                                        </div>
                                    </label>
                                    <label class="col-sm-6 col-form-label" for="phone">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                value="{{ old('phone') }}" placeholder="081234567890" maxlength="15" />
                                            <label for="phone">Phone Number <span class="text-danger">*</span></label>
                                        </div>
                                    </label>
                                    <label class="col-sm-12 col-form-label" for="purpose">
                                        <div class="form-floating form-floating-outline">
                                            <textarea class="form-control auto-resize" id="purpose" name="purpose" placeholder="Reason">{{ old('purpose') }}</textarea>
                                            <label for="purpose">Purpose <span class="text-danger">*</span></label>
                                        </div>
                                    </label>
                                </div>
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
@endsection

@push('styles')
@endpush

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const radioButtons = document.querySelectorAll('input[name="category"]');
            const transferFormElements = document.querySelectorAll(
                '.transfer-section input, .transfer-section select, .transfer-section textarea');
            const scrapFormElements = document.querySelectorAll(
                '.scrap-section input, .scrap-section select, .scrap-section textarea');
            const oldFormType = '{{ old('category') }}';

            // Menampilkan section berdasarkan old('category')
            if (oldFormType === 'Request') {
                document.querySelector('.request-section').style.display = 'block';
                makeFieldsNotRequired(transferFormElements);
                makeFieldsNotRequired(scrapFormElements);
            } else if (oldFormType === 'Change') {
                document.querySelector('.transfer-section').style.display = 'block';
                makeFieldsRequired(transferFormElements);
                makeFieldsNotRequired(scrapFormElements);
            } else if (oldFormType === 'Scrap') {
                document.querySelector('.scrap-section').style.display = 'block';
                makeFieldsNotRequired(transferFormElements);
                makeFieldsRequired(scrapFormElements);
            }

            radioButtons.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    const requestDiv = document.querySelector('.request-section');
                    const changeDiv = document.querySelector('.transfer-section');
                    const scrapDiv = document.querySelector('.scrap-section');
                    hideAllSections();

                    if (radio.value === 'Request') {
                        requestDiv.style.display = 'block';
                        makeFieldsNotRequired(transferFormElements);
                        makeFieldsNotRequired(scrapFormElements);
                    } else if (radio.value === 'Change') {
                        changeDiv.style.display = 'block';
                        makeFieldsRequired(transferFormElements);
                        makeFieldsNotRequired(scrapFormElements);
                    } else if (radio.value === 'Scrap') {
                        scrapDiv.style.display = 'block';
                        makeFieldsNotRequired(transferFormElements);
                        makeFieldsRequired(scrapFormElements);
                    }
                });
            });

            function hideAllSections() {
                const allSections = document.querySelectorAll('.form-section');
                allSections.forEach(function(section) {
                    section.style.display = 'none';
                });
            }

            function makeFieldsRequired(elements) {
                elements.forEach(function(element) {
                    element.setAttribute('required', 'required');
                });
            }

            function makeFieldsNotRequired(elements) {
                elements.forEach(function(element) {
                    element.removeAttribute('required');
                });
            }
        });
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
    <script>
        document.getElementById('Other').addEventListener('change', function() {
            document.getElementById('other_type').disabled = !this.checked;
        });

        // To handle the page reload with old input
        window.addEventListener('DOMContentLoaded', (event) => {
            if (document.getElementById('Other').checked) {
                document.getElementById('other_type').disabled = false;
            }
        });
    </script>
@endpush
