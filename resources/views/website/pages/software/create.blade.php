@extends('website.layouts.main', ['title' => 'Create Form Software'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between mb-2">
            <h4 class="py-1">Standard Setting Change (Software Installation) Form (FRM-ITD-S13-005-00)</h4>
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
                <form method="post" action="{{ route('website.software.store') }}" class="needs-validation" id="myForm"
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
                                    <small class="text-light fw-medium d-block">Category <span
                                            class="text-danger">*</span></small>
                                    <div class="form-check form-check-inline mt-3">
                                        <input class="form-check-input" type="radio" name="category" id="OS"
                                            value="OS" {{ old('category') == 'OS' ? 'checked' : '' }} required />
                                        <label class="form-check-label" for="OS">OS</label>
                                        <div class="invalid-feedback">*Mohon pilih Category</div>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="category" id="Software"
                                            value="Software" {{ old('category') == 'Software' ? 'checked' : '' }} />
                                        <label class="form-check-label" for="Software">Software</label>
                                        <div class="invalid-feedback">*</div>
                                    </div>
                                </div>
                                <div class="col-md p-1">
                                    <small class="text-light fw-medium d-block">Type <span
                                            class="text-danger">*</span></small>
                                    <div class="form-check form-check-inline mt-3">
                                        <input class="form-check-input" type="radio" name="type" id="Install"
                                            value="Install" {{ old('type') == 'Install' ? 'checked' : '' }} />
                                        <label class="form-check-label" for="Install">Install</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="type" id="Uninstall"
                                            value="Uninstall" {{ old('type') == 'Uninstall' ? 'checked' : '' }} disabled />
                                        <label class="form-check-label" for="Uninstall">Uninstall</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="install-section form-section" style="display: none;">
                        <div class="card mb-4">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-header">Device Information</h5>
                            </div>
                            <div class="card-body demo-vertical-spacing demo-only-element">
                                <div class="row mb-3">
                                    <label class="col-sm-6 col-form-label" for="appname">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="appname" name="appname"
                                                value="{{ old('appname') }}" placeholder="Microsoft Office" />
                                            <label for="appname">Application Name <span
                                                    class="text-danger">*</span></label>
                                        </div>
                                    </label>
                                    <label class="col-sm-6 col-form-label" for="installon">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="installon" name="installon"
                                                value="{{ old('installon') }}" placeholder="NTB-001 / CPU-001"
                                                onkeyup="convertToUppercase(this)" />
                                            <label for="installon">Device Name <span class="text-danger">*</span></label>
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
                                            <textarea class="form-control auto-resize" id="purpose" name="purpose" placeholder="Reason for installation">{{ old('purpose') }}</textarea>
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
                        $guide = App\Models\Guide::where('form_name', 'form_software')->first();
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
        document.addEventListener("DOMContentLoaded", function() {
            const radioButtons = document.querySelectorAll('input[name="type"]');
            const changeFormElements = document.querySelectorAll(
                '.change-section input, .change-section select, .change-section textarea');
            const deletionFormElements = document.querySelectorAll(
                '.deletion-section input, .deletion-section select, .deletion-section textarea');
            const oldFormType = '{{ old('type') }}';

            // Menampilkan section berdasarkan old('type')
            if (oldFormType === 'Install') {
                document.querySelector('.install-section').style.display = 'block';
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
                    const installDiv = document.querySelector('.install-section');
                    const changeDiv = document.querySelector('.change-section');
                    const deletionDiv = document.querySelector('.deletion-section');
                    hideAllSections();

                    if (radio.value === 'Install') {
                        installDiv.style.display = 'block';
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

        function convertToUppercase(element) {
            element.value = element.value.toUpperCase();
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
@endpush
