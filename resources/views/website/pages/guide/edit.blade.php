@extends('website.layouts.main', ['title' => 'Edit Guide'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-header">Edit Guide</h5>
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
                        <form method="post" action="{{ route('website.guide.update', $guide->uuid) }}"
                            enctype="multipart/form-data" class="needs-validation" id="myForm" novalidate>
                            @csrf
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control" id="form_name" name="form_name"
                                    value="{{ old('form_name', $guide->form_name) }}" placeholder="Form Name" required />
                                <label for="form_name">Form Name <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="file" class="form-control" id="lampiran" name="lampiran" accept="image/*" />
                                <label for="lampiran">Lampiran (Image) - Kosongkan jika tidak ingin mengganti</label>
                                @if($guide->lampiran)
                                    <small class="text-muted">File saat ini: <a href="{{ asset('storage/' . $guide->lampiran) }}" target="_blank">Lihat Lampiran</a></small>
                                @endif
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

@push('scripts')
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
