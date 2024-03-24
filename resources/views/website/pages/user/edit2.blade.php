@extends('website.layouts.main', ['title' => 'Update User'])

@section('content')
    <div class="pagetitle">
        <h4>User</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">User</a></li>
                <li class="breadcrumb-item active"><a href="#">Update User</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            @if(session('incomplete'))
            <div class="alert alert-warning" role="alert">
                {{ session('incomplete') }}
            </div>
            @elseif(session('complete'))
                <div class="alert alert-success" role="alert">
                    {{ session('complete') }}
                </div>
            @endif
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
            <form method="post" action="{{ route('website.user.update') }}" class="needs-validation" novalidate>
                @csrf
                @method('PUT')


                <div class="col-lg-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">User</h5>
                            <div class="row g-3">                                
                                <div class="col-md-4">
                                    <label for="npk">{{ __('NPK') }}</label>
                                    <input id="npk" type="text" class="form-control @error('npk') is-invalid @enderror"
                                        name="npk" value="{{ old('npk', $user->npk) }}" maxlength="6" placeholder="123456" required autofocus>
                                                
                                    @error('npk')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="name">{{ __('Name') }}</label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name', $user->name) }}" required autofocus onkeyup="formatFullName(this)">
                                                
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="nohp">{{ __('Phone Number') }}</label>
                                    <input id="nohp" type="text"
                                        class="form-control @error('nohp') is-invalid @enderror" name="nohp"
                                        value="{{ old('nohp', $user->nohp) }}" maxlength="14" placeholder="088888888888" required>
    
                                    @error('nohp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="email">{{ __('Email') }}</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email', $user->email) }}" required onkeyup="convertToLowercase(this)" placeholder="example@aiia.co.id" data-toggle="tooltip" data-placement="top" title="Boleh gunakan email external (example@gmail.com)">
                                    
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="password">{{ __('New Password') }}</label>
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        autocomplete="new-password" required>
                                    
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="password-confirm">{{ __('Confirm New Password') }}</label>
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" autocomplete="new-password" required>
                                </div>

                                

                                {{-- <div class="col-md-6">                    
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
                                </div>                                     --}}
                                
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-success" type="submit">Update</button>
                    {{-- <a href="{{ route('website.user.show_data_user') }}" class="btn btn-primary">Data
                        User</a> --}}
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

    // document.addEventListener('DOMContentLoaded', function() {
    //     const addPermissionButton = document.getElementById('add-permission');
    //     const permissionContainer = document.querySelector('.permission-container');

    //     addPermissionButton.addEventListener('click', function() {
    //         const permissionItem = document.createElement('div');
    //         permissionItem.classList.add('permission-item');

    //         const selectHtml = `
    //             <select name="permissions[]" class="form-control" required>
    //                 <option value="">-- Select Permission  --</option>
    //                 @foreach($permissions as $id => $name)
    //                     <option value="{{ $id }}">{{ $name }}</option>
    //                 @endforeach
    //             </select>
                
    //             <button type="button" class="btn btn-danger mt-2 mb-2 remove-permission">Remove Permission</button>
    //         `;

    //         permissionItem.innerHTML = selectHtml;
    //         permissionContainer.appendChild(permissionItem);
    //     });

    //     permissionContainer.addEventListener('click', function(event) {
    //             if (event.target.classList.contains('remove-permission')) {
    //                 const permissionItem = event.target.parentNode;
    //                 permissionContainer.removeChild(permissionItem);
    //             }
    //         });
    // });

    // document.addEventListener('DOMContentLoaded', function() {
    //     const addDepartmentButton = document.getElementById('add-department');
    //     const departmentContainer = document.querySelector('.department-container');

    //     addDepartmentButton.addEventListener('click', function() {
    //         const departmentItem = document.createElement('div');
    //         departmentItem.classList.add('department-item');

    //         const selectHtml = `
    //             <select name="departments[]" class="form-control" required>
    //                 <option value="">-- Select Department  --</option>
    //                 @foreach($departments as $id => $name)
    //                     <option value="{{ $id }}">{{ $name }}</option>
    //                 @endforeach
    //             </select>
                
    //             <button type="button" class="btn btn-danger mt-2 mb-2 remove-department">Remove Department</button>
    //         `;

    //         departmentItem.innerHTML = selectHtml;
    //         departmentContainer.appendChild(departmentItem);
    //     });

    //     departmentContainer.addEventListener('click', function(event) {
    //             if (event.target.classList.contains('remove-department')) {
    //                 const departmentItem = event.target.parentNode;
    //                 departmentContainer.removeChild(departmentItem);
    //             }
    //         });
    // });

    function formatFullName(element) {
            let words = element.value.toLowerCase().split(" ");
            for (let i = 0; i < words.length; i++) {
                words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1);
            }
            element.value = words.join(" ");
        }

    function convertToLowercase(element) {
            element.value = element.value.toLowerCase();
        }
</script>

<script>
    // Aktifkan tooltip Bootstrap
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
