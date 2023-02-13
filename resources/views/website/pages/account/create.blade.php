@extends('website.layouts.main')
@section('title', 'Account Registration/Change/Deletion Form')

@section('content')
    <div class="pagetitle">
        <h4>Account Registration/Change/Deletion Form (FRM-ITD-S13-001-00)</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Forms</a></li>
                <li class="breadcrumb-item active"><a href="#">Form Account</a></li>
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
            <form method="post" action="{{ route('website.account.store') }}" class="needs-validation" novalidate>
                @csrf
                <div class="col-lg-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">A. General</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="budget_type" id="budgetType1"
                                            value="budget">
                                        <label class="form-check-label" for="budgetType1">Budget</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="budget_type" id="budgetType2"
                                            value="unbudget">
                                        <label class="form-check-label" for="budgetType2">Un Budget</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="form_type" id="inlineRadioType1"
                                            value="registration">
                                        <label class="form-check-label" for="inlineRadioType1">Registration</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="form_type" id="inlineRadioType2"
                                            value="change">
                                        <label class="form-check-label" for="inlineRadioType2">Change</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="form_type" id="inlineRadioType3"
                                            value="deletion">
                                        <label class="form-check-label" for="inlineRadioType3">Deletion</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">B. User Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="NPK" name="npk" maxlength="6" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Full Name" name="fullname" maxlength="60" required>
                                </div>
                                <div class="col-md-6">
                                    <select name="department" class="form-control" required>
                                        <option selected disabled value="">-- Choose Department --</option>
                                        @foreach($depts as $dept)
                                        <option value="{{ $dept->name }}">{{ $dept->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Mobile Phone" name="phone" maxlength="12" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Company Name (If External)" name="company" maxlength="100">
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="inputGroupPrepend">Expired Date</span>
                                        <input type="date" name="expired_date" class="form-control">
                                        <div class="invalid-feedback">Please enter your email</div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px;" name="purpose" maxlength="100" required></textarea>
                                        <label for="floatingTextarea">Purpose</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">C. Email & Active Directory</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Login Username (FirstName.LastName)" name="ad_name" maxlength="60" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="is_email" value="false">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Create Email (Mail
                                            address will be decided by ITD)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">D. Approval Flow</h5>
                            <div class="md-stepper-horizontal orange">
                                <div class="md-step active">
                                  <div class="md-step-circle"><span>1</span></div>
                                  <div class="md-step-title">Submit Request</div>
                                  <div class="md-step-optional">This step</div>
                                  <div class="md-step-bar-left"></div>
                                  <div class="md-step-bar-right"></div>
                                </div>
                                <div class="md-step active">
                                  <div class="md-step-circle"><span>2</span></div>
                                  <div class="md-step-title">Approval Manager</div>
                                  <div class="md-step-optional">Request Approveal to your Manager</div>
                                  <div class="md-step-bar-left"></div>
                                  <div class="md-step-bar-right"></div>
                                </div>
                                <div class="md-step active">
                                  <div class="md-step-circle"><span>3</span></div>
                                  <div class="md-step-title">Approval ITD</div>
                                  <div class="md-step-bar-left"></div>
                                  <div class="md-step-bar-right"></div>
                                </div>
                                <div class="md-step active">
                                  <div class="md-step-circle"><span>4</span></div>
                                  <div class="md-step-title">Approval MGR ITD</div>
                                  <div class="md-step-bar-left"></div>
                                  <div class="md-step-bar-right"></div>
                                </div>
                                <div class="md-step active">
                                    <div class="md-step-circle"><span>5</span></div>
                                    <div class="md-step-title">Execution</div>
                                    <div class="md-step-bar-left"></div>
                                    <div class="md-step-bar-right"></div>
                                  </div>
                              </div>

                        </div>
                    </div>
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

@endpush
