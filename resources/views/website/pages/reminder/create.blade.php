@extends('website.layouts.main', ['title' => 'Add Reminder'])

@section('content')
    <div class="pagetitle">
        <h4>Reminder</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Reminder</a></li>
                <li class="breadcrumb-item active"><a href="#">Add Reminder</a></li>
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
            <form method="post" action="{{ route('website.reminder.store') }}" class="needs-validation" novalidate
                id="myForm">
                @csrf
                <div class="col-lg-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">Reminder</h5>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="department_id"><b>Department</b></label>
                                    <select name="department_id" id="department_id" class="form-control">
                                        <option value=""></option>
                                        @foreach ($departments as $department)
                                            @if ($department->id < 19 || $department->id > 27)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label for="user_id"><b>Nama Manager</b></label>
                                    <select name="user_id" id="user_id" class="form-control">
                                        <option value=""></option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                    <button class="btn btn-success" type="submit" id="submitButton">Submit</button>
                    <a href="{{ route('website.reminder.list') }}" class="btn btn-primary">Data
                        Reminder</a>
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
    </script>
    <script>
        $(document).ready(function() {
            $('#department').select2();
        });
    </script>
@endpush
