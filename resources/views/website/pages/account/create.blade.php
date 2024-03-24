@extends('website.layouts.main', ['title' => 'Create Form Account'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-1">Account Registration/Change/Deletion Form (FRM-ITD-S13-001-00)</h4>
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
                <form method="post" action="{{ route('website.account.store') }}" class="needs-validation" id="myForm"
                    novalidate>
                    @csrf
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
                                            value="Budget" {{ old('budget_type') == 'Budget' ? 'checked' : '' }} />
                                        <label class="form-check-label" for="Budget">Budget</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="budget_type" id="Unbudget"
                                            value="Unbudget" {{ old('budget_type') == 'Unbudget' ? 'checked' : '' }} />
                                        <label class="form-check-label" for="Unbudget">Unbudget</label>
                                    </div>
                                </div>
                                <div class="col-md p-1">
                                    <small class="text-light fw-medium d-block">Form Type <span
                                            class="text-danger">*</span></small>
                                    <div class="form-check form-check-inline mt-3">
                                        <input class="form-check-input" type="radio" name="form_type" id="Registration"
                                            value="Registration"
                                            {{ old('form_type') == 'Registration' ? 'checked' : '' }} />
                                        <label class="form-check-label" for="Registration">Registration</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="form_type" id="Change"
                                            value="Change" {{ old('form_type') == 'Change' ? 'checked' : '' }} />
                                        <label class="form-check-label" for="Change">Change</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="form_type" id="Deletion"
                                            value="Deletion" {{ old('form_type') == 'Deletion' ? 'checked' : '' }} />
                                        <label class="form-check-label" for="Deletion">Deletion</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
                    <div class="registration-section form-section" style="display: none;">
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
                                    <label class="col-sm-12 col-form-label" for="phone">
                                        <div class="form-floating form-floating-outline">
                                            <textarea class="form-control auto-resize" id="purpose" name="purpose" placeholder="Reason">{{ old('purpose') }}</textarea>
                                            <label for="purpose">Purpose <span class="text-danger">*</span></label>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-header">Email & Active Directory</h5>
                            </div>
                            <div class="card-body demo-vertical-spacing demo-only-element">
                                <div class="row mb-3">
                                    <label class="col-sm-6 col-form-label" for="ad_name">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="ad_name" name="ad_name"
                                                value="{{ old('ad_name') }}" placeholder="namadepan.namabelakang"
                                                onkeyup="convertToLowercase(this)" />
                                            <label for="ad_name">Username <span class="text-danger">*</span></label>
                                        </div>
                                    </label>
                                    <label class="col-sm-6 col-form-label" for="is_email">
                                        <small class="text-light fw-medium d-block">Email</small>
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" id="is_email"
                                                name="is_email" {{ old('is_email') ? 'checked' : '' }} />
                                            <label class="form-check-label" for="is_email">Create Email for Outlook
                                                <i>(Need
                                                    Budget)</i></label>
                                        </div>
                                    </label>

                                </div>
                                <h6 style="color: red;">Note : Username may change depending on the availability on the
                                    server
                                    (Mail address will be decided by ITD) maximum 5 working days when it is in progress
                                    status.
                                </h6>

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
            const radioButtons = document.querySelectorAll('input[name="form_type"]');
            const changeFormElements = document.querySelectorAll(
                '.change-section input, .change-section select, .change-section textarea');
            const deletionFormElements = document.querySelectorAll(
                '.deletion-section input, .deletion-section select, .deletion-section textarea');
            const oldFormType = '{{ old('form_type') }}';

            // Menampilkan section berdasarkan old('form_type')
            if (oldFormType === 'Registration') {
                document.querySelector('.registration-section').style.display = 'block';
                makeFieldsNotRequired(changeFormElements);
                makeFieldsNotRequired(deletionFormElements);
            } else if (oldFormType === 'Change') {
                document.querySelector('.change-section').style.display = 'block';
                makeFieldsRequired(changeFormElements);
                makeFieldsNotRequired(deletionFormElements);
            } else if (oldFormType === 'Deletion') {
                document.querySelector('.deletion-section').style.display = 'block';
                makeFieldsNotRequired(changeFormElements);
                makeFieldsRequired(deletionFormElements);
            }

            radioButtons.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    const registrationDiv = document.querySelector('.registration-section');
                    const changeDiv = document.querySelector('.change-section');
                    const deletionDiv = document.querySelector('.deletion-section');
                    hideAllSections();

                    if (radio.value === 'Registration') {
                        registrationDiv.style.display = 'block';
                        makeFieldsNotRequired(changeFormElements);
                        makeFieldsNotRequired(deletionFormElements);
                    } else if (radio.value === 'Change') {
                        changeDiv.style.display = 'block';
                        makeFieldsRequired(changeFormElements);
                        makeFieldsNotRequired(deletionFormElements);
                    } else if (radio.value === 'Deletion') {
                        deletionDiv.style.display = 'block';
                        makeFieldsNotRequired(changeFormElements);
                        makeFieldsRequired(deletionFormElements);
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

            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    event.preventDefault();
                } else {
                    submitButton.setAttribute('disabled', 'true');
                    submitButton.innerHTML = 'Submitting...';
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
@endpush
