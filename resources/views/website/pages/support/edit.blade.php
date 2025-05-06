@extends('website.layouts.main', ['title' => 'Edit Subfolder'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-header">Edit Subfolder</h5>
                        <a href="{{ route('website.support.list') }}" class="btn btn-primary" style="margin: 1.25rem;">List</a>
                    </div>
                    <div class="card-body demo-vertical-spacing demo-only-element">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Ooops..</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('website.support.update', $support->id) }}"
                            class="needs-validation" id="myForm" novalidate>
                            @csrf
                            <div class="form-floating form-floating-outline mb-4">
                                <select class="form-select" id="shift" name="shift" aria-label="Select" required>
                                    <option disabled value="">-- Choose Shift --</option>
                                    @foreach (['Non Shift', 'Shift 1', 'Shift 2', 'Shift 3'] as $shift)
                                        <option value="{{ $shift }}"
                                            {{ old('shift', $support->shift) == $shift ? 'selected' : '' }}>
                                            {{ $shift }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="shift">Shift <span class="text-danger">*</span></label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <select class="form-select form-control" id="name" name="name" aria-label="Select"
                                    onchange="fillProfile()" required>
                                    <option value="">-- Select --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->name }}" data-email="{{ $user->email }}"
                                            data-nohp="{{ $user->nohp }}"
                                            {{ old('name', $support->name) == $user->name ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="name">Name <span class="text-danger">*</span></label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', $support->email) }}" placeholder=""
                                    style="background-color: #efeff0" readonly required>
                                <label for="email">Email <span class="text-danger">*</span></label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control" id="nohp" name="nohp"
                                    value="{{ old('nohp', $support->nohp) }}" placeholder=""
                                    style="background-color: #efeff0" readonly required>
                                <label for="nohp">Phone <span class="text-danger">*</span></label>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success" id="submitButton">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="{{ asset('vendor/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="{{ asset('vendor/select2/select2.min.js') }}"></script>
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
    <script>
        $(document).ready(function() {
            $('#name').select2({
                maximumSelectionLength: 2,
                // placeholder: '-- Select --',
                // allowClear: true,
                // theme: 'bootstrap5'
            });
        });
    </script>
    <script>
        function fillProfile() {
            var select = document.getElementById('name');
            var emailInput = document.getElementById('email');
            var phoneInput = document.getElementById('nohp');
            // Get the selected option
            var selectedOption = select.options[select.selectedIndex];
            // Set the value of email input to the email address associated with the selected option
            emailInput.value = selectedOption.getAttribute('data-email');
            phoneInput.value = selectedOption.getAttribute('data-nohp');
        }
    </script>
@endpush
