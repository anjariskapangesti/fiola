@extends('website.layouts.main', ['title' => 'Add Sub Folder'])

@section('content')
    <div class="pagetitle">
        <h4>Sub Folder</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Sub Folder</a></li>
                <li class="breadcrumb-item active"><a href="#">Add Sub Folder</a></li>
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
            <form method="post" action="{{ route('website.subfolder.store') }}" class="needs-validation" novalidate id="myForm">
                @csrf
                <div class="col-lg-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">A. Sub Folder</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Name" name="name"
                                        maxlength="100" required>
                                </div>
                                <div class="col-md-6">
                                    <select name="folder_id" class="form-control" required>
                                        <option selected disabled value="">-- Choose Folder --</option>
                                        @foreach ($folders as $folder)
                                            <option value="{{ $folder->id }}">{{ $folder->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-success" type="submit" id="submitButton">Submit</button>
                    <a href="{{ route('website.subfolder.show_data_subfolder') }}" class="btn btn-primary">Data
                        Sub Folder</a>
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
