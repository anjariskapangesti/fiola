@extends('website.layouts.main', ['title' => 'Create Form Network'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-1">Network Configuration Change (FRM-ITD-S13-007-01)</h4>
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
                <form method="post" action="{{ route('website.network.store') }}" class="needs-validation" id="myForm"
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
                            <h5 class="card-header">Configuration Information</h5>
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
                                <label class="col-sm-6 col-form-label" for="date_access_start">
                                    <div class="form-floating form-floating-outline">
                                        <input type="datetime-local" class="form-control" id="date_access_start"
                                            name="date_access_start" value="{{ old('date_access_start') }}" />
                                        <label for="date_access_start">Date Access Start <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-6 col-form-label" for="date_access_end">
                                    <div class="form-floating form-floating-outline">
                                        <input type="datetime-local" class="form-control" id="date_access_end"
                                            name="date_access_end" value="{{ old('date_access_end') }}" />
                                        <label for="date_access_end">Date Access End <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-12 col-form-label" for="rack">
                                    <div class="col-md p-1">
                                        <small class="text-light fw-medium d-block">Rack that is accessed <span
                                                class="text-danger">*</span></small>
                                        <div class="form-check form-check-inline mt-3">
                                            <input class="form-check-input" type="radio" name="rack" id="Rack 1"
                                                value="Rack 1" {{ old('rack') == 'Rack 1' ? 'checked' : '' }} required />
                                            <label class="form-check-label" for="Rack 1">Rack 1</label>
                                            <div class="invalid-feedback">*Mohon pilih Rack Number</div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rack" id="Rack 2"
                                                value="Rack 2" {{ old('rack') == 'Rack 2' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Rack 2">Rack 2</label>
                                            <div class="invalid-feedback">*</div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rack" id="Rack 3"
                                                value="Rack 3" {{ old('rack') == 'Rack 3' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Rack 3">Rack 3</label>
                                            <div class="invalid-feedback">*</div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rack" id="Rack 4"
                                                value="Rack 4" {{ old('rack') == 'Rack 4' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Rack 4">Rack 4</label>
                                            <div class="invalid-feedback">*</div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rack" id="Rack 5"
                                                value="Rack 5" {{ old('rack') == 'Rack 5' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Rack 5">Rack 5</label>
                                            <div class="invalid-feedback">*</div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rack" id="Rack 6"
                                                value="Rack 6" {{ old('rack') == 'Rack 6' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Rack 6">Rack 6</label>
                                            <div class="invalid-feedback">*</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="col-sm-12 col-form-label" for="device">
                                    <div class="col-md p-1">
                                        <small class="text-light fw-medium d-block">Device that is accessed <span
                                                class="text-danger">*</span></small>
                                        <div class="form-check form-check-inline mt-3">
                                            <input class="form-check-input" type="radio" name="device" id="Firewall"
                                                value="Firewall" {{ old('device') == 'Firewall' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Firewall">Firewall</label>
                                        </div>
                                        <div class="form-check form-check-inline mt-3">
                                            <input class="form-check-input" type="radio" name="device"
                                                id="Internet Router" value="Internet Router"
                                                {{ old('device') == 'Internet Router' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Internet Router">Internet Router</label>
                                        </div>
                                        <div class="form-check form-check-inline ">
                                            <input class="form-check-input" type="radio" name="device"
                                                id="Distribution Switch" value="Distribution Switch"
                                                {{ old('device') == 'Distribution Switch' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Distribution Switch">Distribution
                                                Switch</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="device"
                                                id="Core Switch" value="Core Switch"
                                                {{ old('device') == 'Core Switch' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Core Switch">Core Switch</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="device"
                                                id="AZ Net Router" value="AZ Net Router"
                                                {{ old('device') == 'AZ Net Router' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="AZ Net Router">AZ Net Router</label>
                                        </div>
                                        <div class="form-check form-check-inline d-flex align-items-center">
                                            <input class="form-check-input" type="radio" name="device" id="Other"
                                                value="Other" {{ old('device') == 'Other' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Other"
                                                style="margin-left: 10px; margin-right: 10px;">Other</label>
                                            <input type="text" name="other_device" id="other_device"
                                                class="form-control" placeholder="Specify other"
                                                {{ old('device') != 'Other' ? 'disabled' : '' }}
                                                value="{{ old('other_device') }}">
                                        </div>
                                    </div>
                                </label>
                                <label class="col-sm-12 col-form-label" for="down_time">
                                    <div class="col-md p-1">
                                        <small class="text-light fw-medium d-block">Need Down Time <span
                                                class="text-danger">*</span></small>
                                        <div class="form-check form-check-inline mt-3">
                                            <input class="form-check-input" type="radio" name="down_time"
                                                id="No" value="No"
                                                {{ old('down_time') == 'No' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="No">No</label>
                                        </div>
                                        <div class="form-check form-check-inline d-flex align-items-center">
                                            <input class="form-check-input" type="radio" name="down_time"
                                                id="Yes" value="Yes"
                                                {{ old('down_time') == 'Yes' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="Yes"
                                                style="margin-left: 10px; margin-right: 10px;">Yes, down time = </label>
                                            <input type="text" name="other_down_time" id="other_down_time"
                                                style="width: 100px" class="form-control" placeholder="Duration"
                                                {{ old('down_time') != 'Yes' ? 'disabled' : '' }}
                                                value="{{ old('other_down_time') }}"> Minutes
                                        </div>
                                    </div>
                                </label>
                                <label class="col-sm-12 col-form-label" for="detail">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control auto-resize" id="detail" name="detail" placeholder="Application details">{{ old('detail') }}</textarea>
                                        <label for="detail">Details <span class="text-danger">*</span></label>
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
                                            accept=".pdf, .xlsx, .pptx, .docx" />
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
@endsection

@push('styles')
@endpush

@push('scripts')
    <script>
        const purpose = document.querySelector('#purpose');

        purpose.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        const detail = document.querySelector('#detail');

        detail.addEventListener('input', function() {
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
            document.getElementById('other_device').disabled = !this.checked;
        });

        // To handle the page reload with old input
        window.addEventListener('DOMContentLoaded', (event) => {
            if (document.getElementById('Other').checked) {
                document.getElementById('other_device').disabled = false;
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
