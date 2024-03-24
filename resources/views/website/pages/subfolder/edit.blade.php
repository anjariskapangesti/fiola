@extends('website.layouts.main', ['title' => 'Edit Subfolder'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-header">Edit Subfolder</h5>
                        <a href="{{ route('website.subfolder.list') }}" class="btn btn-primary"
                            style="margin: 1.25rem;">List</a>
                    </div>
                    <div class="card-body demo-vertical-spacing demo-only-element">
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
                        <form method="post" action="{{ route('website.subfolder.update', ['id' => $subfolder->id]) }}"
                            class="needs-validation" novalidate>
                            @csrf
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $subfolder->name) }}" placeholder="00_FOLDER_NAME" />
                                <label for="name">Name <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-floating form-floating-outline mb-4">
                                <select class="form-select" id="folder_id" name="folder_id" aria-label="Select">
                                    <option value=""
                                        {{ old('folder_id', $subfolder->folder_id) == '' ? 'selected' : '' }}>-- Select --
                                    </option>
                                    @foreach ($folders as $folder)
                                        <option value="{{ $folder->id }}"
                                            {{ old('folder_id', $subfolder->folder_id) == $folder->id ? 'selected' : '' }}>
                                            {{ $folder->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="folder_id">Folder Name <span class="text-danger">*</span></label>
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
@endpush
