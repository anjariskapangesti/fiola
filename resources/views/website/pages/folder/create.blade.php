@extends('website.layouts.main', ['title' => 'Add Folder'])

@section('content')
    <div class="pagetitle">
        <h4>Folder</h4>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="#">Folder</a></li>
                <li class="breadcrumb-item active"><a href="#">Add Folder</a></li>
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
            <form method="post" action="{{ route('website.folder.store') }}" class="needs-validation" novalidate>
                @csrf
                <div class="col-lg-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">A. Folder</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Name" name="name"
                                        maxlength="100" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-success" type="submit">Submit</button>
                    <a href="{{ route('website.folder.show_data_folder') }}" class="btn btn-primary">Data
                        Folder</a>
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
