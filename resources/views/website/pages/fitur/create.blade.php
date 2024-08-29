@extends('website.layouts.main', ['title' => 'Create Form Fitur'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between mb-2">
            <h4 class="py-1">Request Fitur for Application (FRM-ITD-S13-047-00)</h4>
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
                <form method="post" enctype="multipart/form-data" action="{{ route('website.fitur.store') }}"
                    class="needs-validation" id="myForm" novalidate>
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
                                            value="{{ Auth::user()->npk }}" placeholder="000000" readonly
                                            style="background-color: #efeff0;" />
                                        <label for="npk_pic">NPK <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-6 col-form-label" for="fullname_pic">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="fullname_pic" name="fullname_pic"
                                            value="{{ Auth::user()->name }}" placeholder="Device Name" readonly
                                            style="background-color: #efeff0;" />
                                        <label for="fullname_pic">Name <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-6 col-form-label" for="department_pic">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="department_pic" name="department_pic"
                                            value="{{ Auth::user()->departments->pluck('name')->implode(', ') }}"
                                            placeholder="Department Name" readonly style="background-color: #efeff0;" />
                                        <label for="department_pic">Department <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-6 col-form-label" for="phone_pic">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="phone_pic" name="phone_pic"
                                            value="{{ Auth::user()->nohp }}" placeholder="081234567890" readonly
                                            style="background-color: #efeff0;" />
                                        <label for="phone_pic">Phone Number <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    {{-- FITUR INFORMATION --}}
                    <div class="card mb-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-header">Fitur Information</h5>
                        </div>
                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <div class="user-information">
                                <div class="row" id="div-fitur">
                                    <label class="col-sm-12 col-form-label" for="aplikasi">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="aplikasi" name="aplikasi"
                                                value="{{ old('aplikasi') }}" placeholder="" required />
                                            <label for="aplikasi">Nama Aplikasi <span class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi Nama Aplikasi</div>
                                        </div>
                                    </label>
                                    <label class="col-sm-12 col-form-label" for="nama_fitur">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="nama_fitur" name="nama_fitur"
                                                value="{{ old('nama_fitur') }}" placeholder="" required />
                                            <label for="nama_fitur">Nama Fitur <span class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi Nama Fitur</div>
                                        </div>
                                    </label>
                                    <label class="col-sm-12 col-form-label" for="lampiran">
                                        <div class="form-floating form-floating-outline">
                                            <input type="file" class="form-control" id="lampiran" name="lampiran"
                                                value="{{ old('lampiran') }}" placeholder="" accept=".pdf" required />
                                            <label for="lampiran">File PDF Konsep <span
                                                    class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi File PDF Konsep</div>
                                        </div>
                                    </label>
                                    <label class="col-sm-12 col-form-label" for="kondisi_sebelum">
                                        <div class="form-floating form-floating-outline">
                                            <textarea class="form-control auto-resize" id="kondisi_sebelum" name="kondisi_sebelum" placeholder="" required>{{ old('kondisi_sebelum') }}</textarea>
                                            <label for="kondisi_sebelum">Kondisi Sebelum Improvement <span
                                                    class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi Kondisi Sebelum Improvement</div>
                                        </div>
                                    </label>
                                    <label class="col-sm-12 col-form-label" for="kondisi_target">
                                        <div class="form-floating form-floating-outline">
                                            <textarea class="form-control auto-resize" id="kondisi_target" name="kondisi_target" placeholder="" required>{{ old('kondisi_target') }}</textarea>
                                            <label for="kondisi_target">Kondisi yang diharapkan <span
                                                    class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi Kondisi yang diharapkan</div>
                                        </div>
                                    </label>
                                    <label class="col-sm-12 col-form-label" for="benefit">
                                        <div class="form-floating form-floating-outline">
                                            <textarea class="form-control auto-resize" id="benefit" name="benefit" placeholder="" required>{{ old('benefit') }}</textarea>
                                            <label for="benefit">Benefit yang didapat <span
                                                    class="text-danger">*</span></label>
                                            <div class="invalid-feedback">*Mohon isi Benefit yang didapat</div>
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

    <div class="modal fade" id="alatModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>List Alat dan Harga</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="search-input" placeholder="Search...">
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Alat</th>
                                <th>Harga</th>
                                <th>Spesifikasi</th>
                            </tr>
                        </thead>
                        <tbody id="device-table-body">
                            <!-- Rows will be inserted here by JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="previous-button" onclick="previousPage()"
                        disabled>Previous</button>
                    <button type="button" class="btn btn-primary" id="next-button" onclick="nextPage()">Next</button>
                </div>
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
                        $guide = App\Models\Guide::where('form_name', 'form_fitur')->first();
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
                        <i class="menu-icon tf-icons mdi mdi-cursor-pointer">Klik gambar untuk memperbesar</i><i
                            class="menu-icon tf-icons mdi mdi-magnify-plus"></i>
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
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css"> --}}
    <link rel="stylesheet" href="{{ asset('vendor/viewer/viewer.min.css') }}">
    <style>
        #zoomable-image {
            max-width: 100%;
            max-height: 100%;
            cursor: pointer;
        }
    </style>
@endpush

@push('scripts')
    {{-- <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}
    {{-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script> --}}
    {{-- SCRIPT GUIDE MODAL --}}
    <script src="{{ asset('vendor/viewer/viewer.min.js') }}"></script>
    {{-- END SCRIPT GUIDE MODAL --}}
    <script>
        $(document).ready(function() {

            @if (session()->has('success'))
                toastr['success']("{{ Session('success') }}")
            @endif
        })
    </script>
    <script>
        const kondisi_target = document.querySelector('#kondisi_target');

        kondisi_target.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        const kondisi_sebelum = document.querySelector('#kondisi_sebelum');

        kondisi_sebelum.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        const benefit = document.querySelector('#benefit');

        benefit.addEventListener('input', function() {
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
@endpush
