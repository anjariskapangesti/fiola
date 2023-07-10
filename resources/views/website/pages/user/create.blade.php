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
                                <div class="col-md-12">
                                    <input type="text" class="form-control" placeholder="Name" name="name"
                                        maxlength="100" required>
                                </div>
                                {{-- <div class="col-md-6">
                                    <select name="departments" class="form-control" required>
                                        <option selected disabled value="">-- Choose Department --</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }} </option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                {{-- <div class="col-md-6">                    
                                    <div class="department-container">
                                        <div class="department-item">
                                            <select name="departments[]" id="departments"  class="form-control" multiple required>
                                                <option value="">-- Select department --</option>
                                                @foreach($departments as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>                                        
                                    </div>
                                </div> --}}

                                

                                <div class="col-md-6">
                                    <input type="email" class="form-control" placeholder="Email" name="email"
                                        maxlength="100" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" placeholder="Password" name="password"
                                        maxlength="100" required>
                                </div>

                                <div class="col-md-6">                    
                                    <div class="department-container">
                                        <div class="department-item mb-3">
                                            <select name="departments[]" class="form-control">
                                                <option value="">-- Select Department --</option>
                                                @foreach($departments as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>                                        
                                    </div>
                                </div>

                                <div class="col-md-6">  
                                    <button type="button" class="btn btn-primary" id="add-department">Add Department</button>
                                </div>  
                                
                                <div class="col-md-6">                    
                                    <div class="permission-container">
                                        <div class="permission-item mb-3">
                                            <select name="permissions[]" class="form-control">
                                                <option value="">-- Select Permission --</option>
                                                @foreach($permissions as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>                                        
                                    </div>
                                </div>

                                <div class="col-md-6">  
                                    <button type="button" class="btn btn-primary" id="add-permission">Add Permission</button>
                                </div>                                    
                                
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-success" type="submit">Submit</button>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addPermissionButton = document.getElementById('add-permission');
        const permissionContainer = document.querySelector('.permission-container');

        addPermissionButton.addEventListener('click', function() {
            const permissionItem = document.createElement('div');
            permissionItem.classList.add('permission-item');

            const selectHtml = `
                <select name="permissions[]" class="form-control" required>
                    <option value="">-- Select Permission  --</option>
                    @foreach($permissions as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                
                <button type="button" class="btn btn-danger mt-2 mb-2 remove-permission">Remove Permission</button>
            `;

            permissionItem.innerHTML = selectHtml;
            permissionContainer.appendChild(permissionItem);
        });

        permissionContainer.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-permission')) {
                    const permissionItem = event.target.parentNode;
                    permissionContainer.removeChild(permissionItem);
                }
            });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addDepartmentButton = document.getElementById('add-department');
        const departmentContainer = document.querySelector('.department-container');

        addDepartmentButton.addEventListener('click', function() {
            const departmentItem = document.createElement('div');
            departmentItem.classList.add('department-item');

            const selectHtml = `
                <select name="departments[]" class="form-control" required>
                    <option value="">-- Select Department  --</option>
                    @foreach($departments as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                
                <button type="button" class="btn btn-danger mt-2 mb-2 remove-department">Remove Department</button>
            `;

            departmentItem.innerHTML = selectHtml;
            departmentContainer.appendChild(departmentItem);
        });

        departmentContainer.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-department')) {
                    const departmentItem = event.target.parentNode;
                    departmentContainer.removeChild(departmentItem);
                }
            });
    });
</script>
@endpush
