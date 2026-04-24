@extends('website.layouts.main', ['title' => 'Create Device'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-header">Create Device</h5>
                        <a href="{{ route('website.device.list') }}" class="btn btn-primary" style="margin: 1.25rem;">List</a>
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
                        <form method="post" action="{{ route('website.device.store') }}" id="myForm"
                            class="needs-validation" novalidate>
                            @csrf
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" placeholder="Device Name" />
                                <label for="name">Name <span class="text-danger">*</span></label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control" id="cost" name="cost"
                                    value="{{ old('cost') }}" placeholder="1.000.000 - 2.000.000" />
                                <label for="cost">Estimasi Cost <span class="text-danger">*</span></label>
                            </div>

                            <div class="form-floating form-floating-outline mb-4">
                                <textarea class="form-control auto-resize" id="spesifikasi" name="spesifikasi" placeholder="Intel AMD">{{ old('spesifikasi') }}</textarea>
                                <label for="spesifikasi">Spesifikasi <span class="text-danger">*</span></label>
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
@endpush

@push('scripts')
    <script>
        const textarea = document.querySelector('.auto-resize');

        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
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
