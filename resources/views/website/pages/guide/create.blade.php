@extends('website.layouts.main', ['title' => 'Create Guide'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-header">Create Guide</h5>
                        <a href="{{ route('website.guide.list') }}" class="btn btn-primary" style="margin: 1.25rem;">List</a>
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
                        <form method="post" action="{{ route('website.guide.store') }}" id="myForm"
                            class="needs-validation" novalidate enctype="multipart/form-data">
                            @csrf
                            {{-- <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control" id="form_name" name="form_name"
                                    value="{{ old('form_name') }}" placeholder="Form Name" />
                                <label for="form_name">Form Name <span class="text-danger">*</span></label>
                            </div> --}}
                            @php
                                $forms = [
                                    (object) ['id' => 1, 'name' => 'form_account'],
                                    (object) ['id' => 2, 'name' => 'form_folder_access'],
                                    (object) ['id' => 3, 'name' => 'form_new_folder'],
                                    (object) ['id' => 4, 'name' => 'form_software'],
                                    (object) ['id' => 5, 'name' => 'form_hardware'],
                                    (object) ['id' => 6, 'name' => 'form_vpn'],
                                    (object) ['id' => 7, 'name' => 'form_project'],
                                    (object) ['id' => 8, 'name' => 'form_fitur'],
                                    (object) ['id' => 9, 'name' => 'form_relayout'],
                                    (object) ['id' => 10, 'name' => 'form_network'],
                                    (object) ['id' => 11, 'name' => 'form_akses_sistem'],
                                    (object) ['id' => 12, 'name' => 'form_incident_report'],
                                ];
                            @endphp

                            <div class="form-floating form-floating-outline mb-4">
                                <select class="form-select form-control" id="form_name" name="form_name"
                                    aria-label="Select">
                                    <option value="" {{ old('form_name') == '' ? 'selected' : '' }}>-- Select --
                                    </option>
                                    @foreach ($forms as $form)
                                        <option value="{{ $form->name }}"
                                            {{ old('form_name') == $form->id ? 'selected' : '' }}>
                                            {{ $form->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="form_name">Name <span class="text-danger">*</span></label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="file" class="form-control" id="path" name="lampiran"
                                    value="{{ old('lampiran') }}" placeholder="Photo Guide" accept="image/*" />
                                <label for="lampiran">Photo Guide <span class="text-danger">*</span></label>
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
        $(document).ready(function() {
            $('#form_name').select2({
                maximumSelectionLength: 2,
                // placeholder: '-- Select --',
                // allowClear: true,
                // theme: 'bootstrap5'
            });
        });
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
