@extends('website.layouts.main', ['title' => 'Form VPN'])

@section('content')
    <div class="pagetitle">
        <h4>PERMIT TO USE VIRTUAL PRIVATE NETWORK (VPN) (FRM-ITD-S13-035-00)</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Forms</a></li>
                <li class="breadcrumb-item active"><a href="#">Form VPN</a></li>
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
            <form method="post" action="{{ route('website.account.update', ['id' => $account->id]) }}"
                class="needs-validation" novalidate>
                @csrf
                <div class="col-lg-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">General</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="budget_type" id="budgetType1"
                                            value="budget" required
                                            {{ $account->budget_type === 'budget' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="budgetType1" data-toggle="tooltip"
                                            data-placement="top">Budget</label>
                                        <div class="invalid-feedback">Please select budget type</div>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="budget_type" id="budgetType2"
                                            value="unbudget" {{ $account->budget_type === 'unbudget' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="budgetType2" data-toggle="tooltip"
                                            data-placement="top">Un Budget</label>
                                        <div class="invalid-feedback">-</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="form_type"
                                            id="inlineRadioType1" value="registration" required
                                            {{ $account->form_type === 'registration' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inlineRadioType1" data-toggle="tooltip"
                                            data-placement="top" title="Baru">Registration</label>
                                        <div class="invalid-feedback">Please select request type</div>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="form_type"
                                            id="inlineRadioType3" value="deletion"
                                            {{ $account->form_type === 'deletion' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inlineRadioType3" data-toggle="tooltip"
                                            data-placement="top" title="Hapus">Deletion</label>
                                        <div class="invalid-feedback">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">User Information (Data User baru yang akan dibuat)</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="NPK (000000)" name="npk"
                                        maxlength="6" required data-toggle="tooltip" data-placement="top"
                                        title="6 Digit NPK" value="{{ $account->npk }}">
                                    <div class="invalid-feedback">Please enter your NPK</div>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Full Name" name="fullname"
                                        maxlength="60" required onkeyup="formatFullName(this)"
                                        value="{{ $account->fullname }}" data-toggle="tooltip" data-placement="top"
                                        title="Nama Lengkap">
                                    <div class="invalid-feedback">Please enter your Full Name</div>
                                </div>
                                <div class="col-md-6">
                                    <select name="department" class="form-control" required data-toggle="tooltip"
                                        data-placement="top" title="Pilih Department">
                                        <option selected value="{{ $account->department }}">{{ $account->department }}
                                        </option>
                                        @foreach ($departments as $department)
                                            @if ($department->id < 19 || $department->id > 27)
                                                <option value="{{ $department->name }}">{{ $department->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please choose your department</div>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Phone Number (0812345678910)"
                                        name="phone" maxlength="14" required data-toggle="tooltip"
                                        data-placement="top" title="No. HP" value="{{ $account->phone }}">
                                    <div class="invalid-feedback">Please enter your phone number</div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px;"
                                            name="purpose" maxlength="100" required data-toggle="tooltip" data-placement="top" title="Alasan membuat akun">{{ $account->purpose }}</textarea>
                                        <label for="floatingTextarea">Purpose</label>
                                        <div class="invalid-feedback">Please fill your purpose</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">Email & Active Directory</h5>
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <input type="text" class="form-control"
                                        placeholder="Login Username (FirstName.LastName)" name="ad_name" maxlength="60"
                                        required onkeyup="convertToLowercase(this)" data-toggle="tooltip"
                                        data-placement="top" title="2 Kata (depan.belakang)"
                                        value="{{ $account->ad_name }}">
                                    <div class="invalid-feedback">Please enter your username</div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault"
                                            name="is_email" value="true" {{ $account->is_email ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexSwitchCheckDefault"
                                            data-toggle="tooltip" data-placement="top"
                                            title="Buatkan Email Outlook">Create Email for Outlook (Mail address will be
                                            decided by ITD)</label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-check-label text-danger" for="note_ad_name">*Username may change
                                        depending on the availability on the server</label>
                                </div>
                            </div>
                        </div>
                    </div>

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
        // Aktifkan tooltip Bootstrap
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
