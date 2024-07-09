@extends('website.layouts.main', ['title' => 'Edit User'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-header">Edit User</h5>
                    </div>
                    <div class="card-body demo-vertical-spacing demo-only-element">
                        @if (session('incomplete'))
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
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <form method="post" action="{{ route('website.user.update') }}" class="needs-validation"
                            novalidate>
                            @csrf
                            @method('PUT')
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control" id="npk" name="npk"
                                    value="{{ old('npk', $user->npk) }}" placeholder="000000" readonly
                                    style="background-color: #efeff0; opacity: 1;" />
                                <label for="npk">NPK <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $user->name) }}" placeholder="Nama Lengkap"
                                    onkeyup="formatFullName(this)">
                                <label for="name">Name <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control" id="email" name="email"
                                    value="{{ old('email', $user->email) }}" placeholder="nama@aiia.co.id" />
                                <label for="email">Email <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control" id="nohp" name="nohp"
                                    value="{{ old('nohp', $user->nohp) }}" placeholder="081234567890" />
                                <label for="nohp">Phone Number <span class="text-danger">*</span></label>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="radio" name="change_password" value="1" id="change_password_yes">
                                    <span>Change Password</span>
                                </label>
                                <label>
                                    <input type="radio" name="change_password" value="0" id="change_password_no"
                                        checked>
                                    <span>Do Not Change Password</span>
                                </label>
                            </div>

                            <div id="password_field" style="display: none;">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="password" class="form-control" id="password" name="password"
                                        autocomplete="new-password" placeholder="***********" required>
                                    <label for="password">New Password</label>
                                </div>
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" autocomplete="new-password" placeholder="***********"
                                        required>
                                    <label for="password_confirmation">Confirm New Password</label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
    <script>
        document.querySelectorAll('input[name="change_password"]').forEach((radio) => {
            radio.addEventListener('change', function() {
                const passwordField = document.getElementById('password_field');
                if (this.value === '1') {
                    passwordField.style.display = 'block';
                } else {
                    passwordField.style.display = 'none';
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            @if (session()->has('success'))
                toastr['success']("{{ Session('success') }}")
            @endif
        })

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
@endpush
