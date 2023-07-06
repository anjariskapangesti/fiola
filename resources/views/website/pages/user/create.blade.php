@extends('website.layouts.main', ['title' => 'Add Department'])

@section('content')
    <div class="pagetitle">
        <h4>User</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">User</a></li>
                <li class="breadcrumb-item active"><a href="#">Add User</a></li>
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
            <form method="post" action="{{ route('website.user.store') }}" class="needs-validation" novalidate>
                @csrf
                <div class="col-lg-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">A. User</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Name" name="name"
                                        maxlength="100" required>
                                </div>
                                <div class="col-md-6">
                                    <select name="dept_id" class="form-control" required>
                                        <option selected disabled value="">-- Choose Department --</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" placeholder="Email" name="email"
                                        maxlength="100" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" placeholder="Password" name="password"
                                        maxlength="100" required>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control" id="permission_id" name="permission_id">
                                        <option selected disabled value="">-- Choose Permission --</option>
                                        @foreach ($permissions as $permission)
                                            <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                    <button class="btn btn-success" type="submit">Save & Submit Request</button>
                    <a href="{{ route('website.user.show_data_user') }}" class="btn btn-primary">Data
                        User</a>
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
@endpush
