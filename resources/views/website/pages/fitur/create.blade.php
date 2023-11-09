@extends('website.layouts.main', ['title' => 'Form Request Fitur'])

@section('content')
    <div class="pagetitle">
        <h4>Request Fitur for Application</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Forms</a></li>
                <li class="breadcrumb-item active"><a href="#">Form Request Fitur</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
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
            <form method="post" action="{{ route('website.vpn.store') }}" class="needs-validation" novalidate>
                @csrf
                <div class="col-lg-12">                                        
                    
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">Applicant Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="NPK" name="npk"
                                        maxlength="6" value="{{ Auth::user()->npk }}" readonly required>
                                    <div class="invalid-feedback">Please enter your NPK</div>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Full Name" name="fullname"
                                        maxlength="60" value="{{ Auth::user()->name }}" readonly required onkeyup="formatFullName(this)">
                                    <div class="invalid-feedback">Please enter your Full Name</div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <select name="department" class="form-control" required>
                                        <option selected disabled value="">-- Choose Department --</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->name }}">{{ $department->name }} </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please choose your department</div>
                                </div> --}}
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Department" name="department"
                                        maxlength="14" value="{{ Auth::user()->departments->pluck('name')->implode(', ') }}" readonly required>
                                    <div class="invalid-feedback">Please enter your Department</div>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" placeholder="Email@aiia.co.id" name="email" maxlength="60" value="{{ Auth::user()->email }}" readonly required onkeyup="updateUsername(this)">
                                    <div class="invalid-feedback">Please enter your email</div>
                                </div>

                                <div class="col-md-12">
                                    <input type="file" class="form-control" placeholder="Lampiran" name="lampiran" required>
                                    <div class="invalid-feedback">Please enter your Lampiran</div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px;"
                                            name="purpose" maxlength="100" required></textarea>
                                        <label for="floatingTextarea">Purpose</label>
                                        <div class="invalid-feedback">Please fill your purpose</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>   

                    {{-- <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">Remarks</h5>
                            <div class="row g-3 mb-3">
                                <div class="col-md-12">
                                    <b>1. Kami berkomitmen menjaga informasi rahasia berkaitan dengan dibukanya akses terhadap jaringan AIIA menggunakan VPN kepada pihak mana pun juga tanpa terkecuali, tanpa persetujuan tertulis dari Manajemen PT. Aisin Indonesia Automotive</b>
                                    <br>We are committed to keep confidential information in access to  accordance with opened the access to AIIA networks using VPN to any party without exception, without the express written consent of the Management of PT. Aisin Indonesia Automotive.
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-12">
                                    <b>2. Kami berkomitmen tidak membocorkan username dan password login VPN serta tidak menyalahgunakan hak akses yang diberikan kepada kami untuk kepentingan diluar kepentingan perusahaan.</b>
                                    <br>We are committed not to leak username and password vpn account and we does not abuse the access rights for interests beyond the company's interests.
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-12">
                                    <b>3. Kami bersedia bertanggung jawab secara hukum bila terjadi kebocoran informasi rahasia dan/atau penyalahgunaan hak akses yang kami terima yang mengakibatkan kerugian bagi perusahaan baik selama menjadi karyawan ataupun setelah hubungan kerja dengan PT. Aisin Indonesia Automotive berakhir.</b>
                                    <br>We are liable legally if there are leakage of confidential information and / or misuse of access rights that it can cause loss for the company either during as the employee or after have working relationship with PT. Aisin Indonesia Automotive is over.
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <b>4. Otorisasi penggunaan VPN ini secara umum hanya untuk Manager Up. Namun untuk kebutuhan khusus, manager diperbolehkan menunjuk staff untuk memperoleh otorisasi penggunaan VPN ini.</b>
                                    <br>Commonly, this authorization is only for manager up. However for special purposes, manager is allowed to assign a staf to get authorization of using the VPN.
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    
                    <div class="card">
                        <div class="card-body">
                            @include('website.layouts.approval_flow')
                        </div>
                    </div>
                    {{-- <input type="hidden" name="created_dept" value="{{ $userDepartment->id }}"> --}}
                    <button class="btn btn-success" type="submit">Save & Submit Request</button>
                </div>
            </form>
        </div>
    </section>

@endsection

@push('styles')
    <link href="{{ asset('vendor/bs-step/bs-step.css') }}" rel="stylesheet">
@endpush

@push('scripts')
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
