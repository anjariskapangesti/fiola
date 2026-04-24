@extends('website.layouts.main', ['title' => 'Create Form VPN'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between mb-2">
            <h4 class="py-1">PERMIT TO USE VIRTUAL PRIVATE NETWORK (VPN) (FRM-ITD-S13-035-00)</h4>
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
                <form method="post" action="{{ route('website.vpn.store') }}" class="needs-validation" id="myForm"
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
                            <h5 class="card-header">User Information</h5>
                        </div>
                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <div class="row mb-3">
                                <label class="col-sm-6 col-form-label" for="npk">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="npk" name="npk"
                                            value="{{ old('npk') }}" placeholder="000000" maxlength="6" />
                                        <label for="npk">NPK <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-6 col-form-label" for="fullname">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="fullname" name="fullname"
                                            value="{{ old('fullname') }}" placeholder="Full Name"
                                            onkeyup="formatFullName(this)" />
                                        <label for="fullname">Name <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-6 col-form-label" for="department">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="department" name="department"
                                            aria-label="Select">
                                            <option selected disabled value="">-- Choose Department --</option>
                                            @foreach ($departments as $department)
                                                @php
                                                    $selected = '';
                                                    if (old('department') && old('department') == $department->name) {
                                                        $selected = 'selected';
                                                    }
                                                @endphp
                                                <option value="{{ $department->name }}" {{ $selected }}>
                                                    {{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="department">Department Name <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-6 col-form-label" for="phone">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            value="{{ old('phone') }}" placeholder="081234567890" maxlength="15" />
                                        <label for="phone">Phone Number <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-6 col-form-label" for="email">
                                    <div class="form-floating form-floating-outline">
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ old('email') }}" placeholder="user@aiia.co.id"
                                            onkeyup="updateUsername(this)" />
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-6 col-form-label" for="username">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="username" name="username"
                                            value="{{ old('username') }}" placeholder="user" readonly
                                            style="background-color: #efeff0    ;" />
                                        <label for="username">Username <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                                <label class="col-sm-12 col-form-label" for="purpose">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control auto-resize" id="purpose" name="purpose" placeholder="Reason">{{ old('purpose') }}</textarea>
                                        <label for="purpose">Purpose <span class="text-danger">*</span></label>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">Remarks</h5>
                            <div class="row g-3 mb-3">
                                <div class="col-md-12">
                                    <b>1. Kami berkomitmen menjaga informasi rahasia berkaitan dengan dibukanya akses
                                        terhadap jaringan AIIA menggunakan VPN kepada pihak mana pun juga tanpa terkecuali,
                                        tanpa persetujuan tertulis dari Manajemen PT. Aisin Indonesia Automotive</b>
                                    <br>We are committed to keep confidential information in access to accordance with
                                    opened the access to AIIA networks using VPN to any party without exception, without the
                                    express written consent of the Management of PT. Aisin Indonesia Automotive.
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-12">
                                    <b>2. Kami berkomitmen tidak membocorkan username dan password login VPN serta tidak
                                        menyalahgunakan hak akses yang diberikan kepada kami untuk kepentingan diluar
                                        kepentingan perusahaan.</b>
                                    <br>We are committed not to leak username and password vpn account and we does not abuse
                                    the access rights for interests beyond the company's interests.
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-12">
                                    <b>3. Kami bersedia bertanggung jawab secara hukum bila terjadi kebocoran informasi
                                        rahasia dan/atau penyalahgunaan hak akses yang kami terima yang mengakibatkan
                                        kerugian bagi perusahaan baik selama menjadi karyawan ataupun setelah hubungan kerja
                                        dengan PT. Aisin Indonesia Automotive berakhir.</b>
                                    <br>We are liable legally if there are leakage of confidential information and / or
                                    misuse of access rights that it can cause loss for the company either during as the
                                    employee or after have working relationship with PT. Aisin Indonesia Automotive is over.
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <b>4. Otorisasi penggunaan VPN ini secara umum hanya untuk Manager Up. Namun untuk
                                        kebutuhan khusus, manager diperbolehkan menunjuk staff untuk memperoleh otorisasi
                                        penggunaan VPN ini.</b>
                                    <br>Commonly, this authorization is only for manager up. However for special purposes,
                                    manager is allowed to assign a staf to get authorization of using the VPN.
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
                        $guide = App\Models\Guide::where('form_name', 'form_vpn')->first();
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
        const textarea = document.querySelector('.auto-resize');

        textarea.addEventListener('input', function() {
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
@endpush
