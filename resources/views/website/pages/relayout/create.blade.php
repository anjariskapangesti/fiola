@extends('website.layouts.main', ['title' => 'Create Form Relayout'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between mb-2">
            <h4 class="py-1">Form Permohonan IT Re-layout (FRM-ITD-S13-010-00)</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#guideModal"><i
                    class="menu-icon tf-icons mdi mdi-book-information-variant"></i>Guide</button>
        </div>
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
                <form method="post" action="{{ route('website.relayout.store') }}" class="needs-validation" id="myForm"
                    enctype="multipart/form-data" novalidate>
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
                                    <small class="text-light fw-medium d-block">Request Type <span
                                            class="text-danger">*</span></small>
                                    <div class="form-check form-check-inline mt-3">
                                        <input class="form-check-input" type="radio" name="request_type"
                                            id="Additional" value="Additional"
                                            {{ old('request_type') == 'Additional' ? 'checked' : '' }} />
                                        <label class="form-check-label" for="Additional">Additional</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="request_type"
                                            id="Relayout" value="Relayout"
                                            {{ old('request_type') == 'Relayout' ? 'checked' : '' }} />
                                        <label class="form-check-label" for="Relayout">Relayout</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-header">Form Information</h5>
                        </div>
                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <div class="row mb-3">
                                <label class="col-sm-12 col-form-label" for="project_name">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="project_name" name="project_name"
                                            value="{{ old('project_name') }}" placeholder="Change Network" />
                                        <label for="project_name">Project Name <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-12 col-form-label" for="date_finish_plan">
                                    <div class="form-floating form-floating-outline">
                                        <input type="datetime-local" class="form-control" id="date_finish_plan"
                                            name="date_finish_plan" value="{{ old('date_finish_plan') }}" />
                                        <label for="date_finish_plan">Date Finish Plan <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-12 col-form-label" for="location">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="location" name="location"
                                            value="{{ old('location') }}" placeholder="Change Network" />
                                        <label for="location">Location <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-12 col-form-label" for="relayout_type">
                                    <div class="col-md p-1">
                                        <small class="text-light fw-medium d-block">Relayout Type <span
                                                class="text-danger">*</span></small>
                                        <div class="form-check form-check-inline mt-3">
                                            <input class="form-check-input" type="radio" name="relayout_type"
                                                id="LAN" value="LAN"
                                                {{ old('relayout_type') == 'LAN' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="LAN">LAN</label>
                                        </div>
                                        <div class="form-check form-check-inline mt-3">
                                            <input class="form-check-input" type="radio" name="relayout_type"
                                                id="Telephony" value="Telephony"
                                                {{ old('relayout_type') == 'Telephony' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Telephony">Telephony</label>
                                        </div>
                                        <div class="form-check form-check-inline d-flex align-items-center">
                                            <input class="form-check-input" type="radio" name="relayout_type"
                                                id="Other" value="Other"
                                                {{ old('relayout_type') == 'Other' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Other"
                                                style="margin-left: 10px; margin-right: 10px;">Other</label>
                                            <input type="text" name="other_relayout_type" id="other_relayout_type"
                                                class="form-control" placeholder="Specify other"
                                                {{ old('relayout_type') != 'Other' ? 'disabled' : '' }}
                                                value="{{ old('other_relayout_type') }}">
                                        </div>
                                    </div>
                                </label>

                                <label class="col-sm-12 col-form-label" for="description">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control auto-resize" id="description" name="description" placeholder="Description Project">{{ old('description') }}</textarea>
                                        <label for="description">Description <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-12 col-form-label" for="purpose">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control auto-resize" id="purpose" name="purpose" placeholder="Reason">{{ old('purpose') }}</textarea>
                                        <label for="purpose">Purpose <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-12 col-form-label" for="lampiran">
                                    <div class="form-floating form-floating-outline">
                                        <input type="file" class="form-control" id="lampiran" name="lampiran"
                                            value="{{ old('lampiran') }}" placeholder="Change Network"
                                            accept=".pdf, .xlsx, .pptx, .docx, image/*" />
                                        <label for="lampiran">Attachment <span class="text-danger">*</span></label>
                                    </div>
                                </label>
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

    {{-- GUIDE MODAL --}}
    <div class="modal fade" id="guideModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>GUIDE</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @php
                        $guide = App\Models\Guide::where('form_name', 'form_relayout')->first();
                        $imageUrl = $guide ? asset('storage/' . $guide->lampiran) : null;
                    @endphp
                    @if ($imageUrl)
                        <img src="{{ $imageUrl }}" alt="Guide Image" id="zoomable-image">
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var image = document.getElementById('zoomable-image');
                                var viewer = new Viewer(image, {
                                    // Viewer options
                                    zoomable: true,
                                    scalable: true,
                                    rotatable: false,
                                    transition: false,
                                    toolbar: true,
                                });
                            });
                        </script>
                    @else
                        <p><b>Belum ada guide</b></p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END GUIDE MODAL --}}
@endsection

@push('styles')
    {{-- STYLE GUIDE MODAL --}}
    <link rel="stylesheet" href="{{ asset('vendor/viewer/viewer.min.css') }}">
    <style>
        #zoomable-image {
            max-width: 100%;
            max-height: 100%;
            cursor: pointer;
        }
    </style>
    {{-- END STYLE GUIDE MODAL --}}
@endpush

@push('scripts')
    {{-- SCRIPT GUIDE MODAL --}}
    <script src="{{ asset('vendor/viewer/viewer.min.js') }}"></script>
    {{-- END SCRIPT GUIDE MODAL --}}
    <script>
        const purpose = document.querySelector('#purpose');

        purpose.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        const description = document.querySelector('#description');

        description.addEventListener('input', function() {
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
            document.getElementById('other_relayout_type').disabled = !this.checked;
        });

        // To handle the page reload with old input
        window.addEventListener('DOMContentLoaded', (event) => {
            if (document.getElementById('Other').checked) {
                document.getElementById('other_relayout_type').disabled = false;
            }
        });
    </script>
    <script>
        document.getElementById('Yes').addEventListener('change', function() {
            document.getElementById('other_down_time').disabled = !this.checked;
        });

        // To handle the page reload with old input
        window.addEventListener('DOMContentLoaded', (event) => {
            if (document.getElementById('Yes').checked) {
                document.getElementById('other_down_time').disabled = false;
            }
        });
    </script>
@endpush
