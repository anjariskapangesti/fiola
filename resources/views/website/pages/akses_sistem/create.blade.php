@extends('website.layouts.main', ['title' => 'Create Form Akses Sistem'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-1">Akses Sistem</h4>
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
                <form method="post" action="{{ route('website.akses_sistem.store') }}" class="needs-validation"
                    id="myForm" novalidate>
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
                            <h5 class="card-header">System Access Information</h5>
                        </div>
                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <div class="row mb-3">
                                <label class="col-sm-12 col-form-label" for="nama">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            value="{{ old('nama') }}" placeholder="Nama Lengkap" />
                                        <label for="nama">Nama Pemohon <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-12 col-form-label" for="asal_instansi">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="asal_instansi" name="asal_instansi"
                                            value="{{ old('asal_instansi') }}" placeholder="Nama Lengkap" />
                                        <label for="asal_instansi">Asal Instansi <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-12 col-form-label" for="keperluan">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control auto-resize" id="keperluan" name="keperluan" placeholder="">{{ old('keperluan') }}</textarea>
                                        <label for="keperluan">Keperluan <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-12 col-form-label" for="akses">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control auto-resize" id="akses" name="akses" placeholder="">{{ old('akses') }}</textarea>
                                        <label for="akses">Akses <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-header">Approval Flow</h5>
                        </div>
                        <div class="card-body demo-vertical-spacing demo-only-element table-responsive">
                            <div class="md-stepper-horizontal orange">
                                <div class="md-step blinking {{ Route::is('*create') ? 'active' : '' }}">
                                    <div class="md-step-circle"><span>1</span></div>
                                    <div class="md-step-title">Submit Request</div>
                                    <div class="md-step-optional">This step</div>
                                    <div class="md-step-bar-left"></div>
                                    <div class="md-step-bar-right"></div>
                                </div>
                                <div class="md-step {{ Route::is('*create') ? 'active' : '' }}">
                                    <div class="md-step-circle"><span>2</span></div>
                                    <div class="md-step-title">Approval Manager</div>
                                    <div class="md-step-optional">Request Approval to your Manager</div>
                                    <div class="md-step-bar-left"></div>
                                    <div class="md-step-bar-right"></div>
                                </div>
                                <div class="md-step {{ Route::is('*create') ? 'active' : '' }}">
                                    <div class="md-step-circle"><span>3</span></div>
                                    <div class="md-step-title">Approval GM</div>
                                    <div class="md-step-optional"></div>
                                    <div class="md-step-bar-left"></div>
                                    <div class="md-step-bar-right"></div>
                                </div>
                                <div class="md-step {{ Route::is('*create') ? 'active' : '' }}">
                                    <div class="md-step-circle"><span>4</span></div>
                                    <div class="md-step-title">Approval BOD</div>
                                    <div class="md-step-bar-left"></div>
                                    <div class="md-step-bar-right"></div>
                                </div>
                                <div class="md-step {{ Route::is('*create') ? 'active' : '' }}">
                                    <div class="md-step-circle"><span>5</span></div>
                                    <div class="md-step-title">Approval ITD</div>
                                    <div class="md-step-bar-left"></div>
                                    <div class="md-step-bar-right"></div>
                                </div>
                                <div class="md-step {{ Route::is('*create') ? 'active' : '' }}">
                                    <div class="md-step-circle"><span>6</span></div>
                                    <div class="md-step-title">Approval ITD Manager</div>
                                    <div class="md-step-bar-left"></div>
                                    <div class="md-step-bar-right"></div>
                                </div>
                                <div class="md-step {{ Route::is('*create') ? 'active' : '' }}">
                                    <div class="md-step-circle"><span>7</span></div>
                                    <div class="md-step-title">Execution</div>
                                    <div class="md-step-optional"></div>
                                    <div class="md-step-bar-left"></div>
                                    <div class="md-step-bar-right"></div>
                                </div>
                                <div class="md-step {{ Route::is('*create') ? 'active' : '' }}">
                                    <div class="md-step-circle"><span>8</span></div>
                                    <div class="md-step-title">Finished</div>
                                    <div class="md-step-optional">Creator received notification</div>
                                    <div class="md-step-bar-left"></div>
                                    <div class="md-step-bar-right"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @push('styles')
                        <style>
                            @keyframes blink {
                                0% {
                                    background-color: rgb(250, 200, 4);
                                }

                                50% {
                                    background-color: rgb(255, 219, 89);
                                }

                                100% {
                                    background-color: rgb(250, 200, 4);
                                }
                            }

                            .blinking {
                                animation: blink 2s infinite;
                            }
                        </style>
                    @endpush

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
        const akses = document.querySelector('#akses');

        akses.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        const keperluan = document.querySelector('#keperluan');

        keperluan.addEventListener('input', function() {
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
