@extends('website.layouts.main', ['title' => 'Form Account'])

@section('content')
    <div class="pagetitle">
        <h4>Account Registration/Change/Deletion Form (FRM-ITD-S13-001-00)</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Forms</a></li>
                <li class="breadcrumb-item active"><a href="#">Form Account</a></li>
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
            <form method="post" action="{{ route('website.account.store') }}" class="needs-validation" novalidate
                id="myForm">
                @csrf
                <div class="col-lg-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">General</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="budget_type" id="budgetType1"
                                            value="budget" required>
                                        <label class="form-check-label" for="budgetType1" data-toggle="tooltip"
                                            data-placement="top">Budget</label>
                                        <div class="invalid-feedback">Please select budget type</div>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="budget_type" id="budgetType2"
                                            value="unbudget">
                                        <label class="form-check-label" for="budgetType2" data-toggle="tooltip"
                                            data-placement="top">Un Budget</label>
                                        <div class="invalid-feedback">-</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="form_type"
                                            id="inlineRadioType1" value="registration" required>
                                        <label class="form-check-label" for="inlineRadioType1" data-toggle="tooltip"
                                            data-placement="top" title="Baru">Registration</label>
                                        <div class="invalid-feedback">Please select request type</div>
                                    </div>
                                    {{-- <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="form_type"
                                            id="inlineRadioType2" value="change" required>
                                        <label class="form-check-label" for="inlineRadioType2" data-toggle="tooltip"
                                            data-placement="top" title="Ganti">Change</label>
                                        <div class="invalid-feedback">-</div>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="form_type"
                                            id="inlineRadioType3" value="deletion">
                                        <label class="form-check-label" for="inlineRadioType3" data-toggle="tooltip"
                                            data-placement="top" title="Hapus">Deletion</label>
                                        <div class="invalid-feedback">-</div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="registration-section form-section" style="display: none;">
                        <div class="card mb-2">
                            <div class="card-body">
                                <h5 class="card-title">User Information (Data User baru yang akan dibuat)</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="NPK (000000)" name="npk"
                                            maxlength="6" required data-toggle="tooltip" data-placement="top"
                                            title="6 Digit NPK">
                                        <div class="invalid-feedback">Please enter your NPK</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Full Name" name="fullname"
                                            maxlength="60" required onkeyup="formatFullName(this)" data-toggle="tooltip"
                                            data-placement="top" title="Nama Lengkap">
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
                                        <input type="text" class="form-control"
                                            placeholder="Phone Number (0812345678910)" name="phone" maxlength="14"
                                            required data-toggle="tooltip" data-placement="top" title="No. HP">
                                        <div class="invalid-feedback">Please enter your phone number</div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px;"
                                                name="purpose" maxlength="100" required data-toggle="tooltip" data-placement="top" title="Alasan membuat akun"></textarea>
                                            <label for="floatingTextarea">Purpose</label>
                                            <div class="invalid-feedback">Please fill your purpose</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-2">
                            <div class="card-body">
                                <h5 class="card-title">Email & Active Directory</h5>
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control"
                                            placeholder="Login Username (FirstName.LastName)" name="ad_name"
                                            maxlength="60" required onkeyup="convertToLowercase(this)"
                                            data-toggle="tooltip" data-placement="top" title="2 Kata (depan.belakang)">
                                        <div class="invalid-feedback">Please enter your username</div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault"
                                                name="is_email" value="false">
                                            <label class="form-check-label" for="flexSwitchCheckDefault"
                                                data-toggle="tooltip" data-placement="top"
                                                title="Buatkan Email Outlook">Create Email for Outlook (Mail
                                                address will be decided by ITD)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-check-label text-danger" for="note_ad_name">*Username may
                                            change
                                            depending on the availability on the server</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="card mb-2 change-section form-section" style="display: none;">
                        <div class="card-body">
                            <h5 class="card-title">Change Account Information</h5>
                            <div class="row g-3">
                                <h6 class="text-black">
                                    <b>Target Perubahan Informasi Akun</b>
                                </h6>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" placeholder="Email@aiia.co.id"
                                        name="email" maxlength="60" required onkeyup="updateUsername(this)">
                                    <div class="invalid-feedback">Please enter your email</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="inputGroupPrepend">AIIA\</span>
                                        <input type="text" name="username" class="form-control"
                                            placeholder="username (automatically when typing email)" required readonly>
                                        <div class="invalid-feedback">Please enter your username</div>
                                    </div>
                                </div>
                                <table class="table table-responsive table-bordered display">
                                    <thead>
                                        <tr>
                                            <th style="width: 50%; text-align: center;">Before</th>
                                            <th style="width: 50%; text-align: center;">After</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control"
                                                        placeholder="Display Name Before" name="display_name_before"
                                                        maxlength="100" required onkeyup="convertToLowercase(this)"
                                                        data-toggle="tooltip" data-placement="top" title="Nama Lengkap">
                                                    <div class="invalid-feedback">Please enter your username</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control"
                                                        placeholder="Display Name After" name="display_name_after"
                                                        maxlength="100" required onkeyup="convertToLowercase(this)"
                                                        data-toggle="tooltip" data-placement="top" title="Nama Lengkap">
                                                    <div class="invalid-feedback">Please enter your username</div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="col-md-12">
                                                    <div class="input-group has-validation">
                                                        <span class="input-group-text" id="inputGroupPrepend">AIIA\</span>
                                                        <input type="text" name="username_before" class="form-control"
                                                            placeholder="Username Before" required>
                                                        <div class="invalid-feedback">Please enter your Username Before</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="col-md-12">
                                                    <div class="input-group has-validation">
                                                        <span class="input-group-text" id="inputGroupPrepend">AIIA\</span>
                                                        <input type="text" name="username_after" class="form-control"
                                                            placeholder="Username After" required>
                                                        <div class="invalid-feedback">Please enter your Username After</div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px;"
                                            name="purpose" maxlength="100" required data-toggle="tooltip" data-placement="top" title="Alasan membuat akun"></textarea>
                                        <label for="floatingTextarea">Purpose</label>
                                        <div class="invalid-feedback">Please fill your purpose</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-2 deletion-section form-section" style="display: none;">
                        <div class="card-body">
                            <h5 class="card-title">Delete Account</h5>
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <input type="text" class="form-control"
                                        placeholder="Login Username (FirstName.LastName)" name="ad_name" maxlength="60"
                                        required onkeyup="convertToLowercase(this)" data-toggle="tooltip"
                                        data-placement="top" title="2 Kata (depan.belakang)">
                                    <div class="invalid-feedback">Please enter your username</div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault"
                                            name="is_email" value="false">
                                        <label class="form-check-label" for="flexSwitchCheckDefault"
                                            data-toggle="tooltip" data-placement="top"
                                            title="Buatkan Email Outlook">Create Email for Outlook (Mail
                                            address will be decided by ITD)</label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-check-label text-danger" for="note_ad_name">*Username may change
                                        depending on the availability on the server</label>
                                </div>
                            </div>
                        </div>
                    </div> --}}

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

    <script>
        // Aktifkan tooltip Bootstrap
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const radioButtons = document.querySelectorAll('input[name="form_type"]');
            const changeFormElements = document.querySelectorAll(
                '.change-section input, .change-section select, .change-section textarea');
            const deletionFormElements = document.querySelectorAll(
                '.deletion-section input, .deletion-section select, .deletion-section textarea');

            radioButtons.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    const registrationDiv = document.querySelector('.registration-section');
                    const changeDiv = document.querySelector('.change-section');
                    const deletionDiv = document.querySelector('.deletion-section');

                    hideAllSections();

                    if (radio.value === 'registration') {
                        registrationDiv.style.display = 'block';
                        makeFieldsNotRequired(changeFormElements);
                        makeFieldsNotRequired(deletionFormElements);
                    } else if (radio.value === 'change') {
                        changeDiv.style.display = 'block';
                        makeFieldsRequired(changeFormElements);
                        makeFieldsNotRequired(deletionFormElements);
                    } else if (radio.value === 'deletion') {
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById('myForm');
            var submitButton = document.getElementByType('submit');
    
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    event.preventDefault();
                } else {
                    submitButton.setAttribute('disabled', 'true');
                    submitButton.innerHTML = 'Submitting...';
                }
            });
    
            form.addEventListener('input', function () {
                if (form.checkValidity()) {
                    submitButton.removeAttribute('disabled');
                    submitButton.innerHTML = 'Save & Submit Request';
                }
            });
        });
    </script>
    
    
    
    
    
@endpush
