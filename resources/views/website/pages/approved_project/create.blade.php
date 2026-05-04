@extends('website.layouts.main', ['title' => 'Tambah Aktif Project'])

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">

        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Tambah Aktif Project</h5>

            <a href="{{ route('website.approved_project.index') }}" class="btn btn-secondary me-3">
                Kembali
            </a>
        </div>

        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('website.approved_project.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text"
                           name="nama"
                           class="form-control"
                           value="{{ old('nama') }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <input type="text"
                           name="department"
                           class="form-control"
                           value="{{ old('department') }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date"
                           name="tanggal"
                           class="form-control"
                           value="{{ old('tanggal') }}"
                           required>
                    <small class="text-muted">Format akan ditampilkan sebagai mm/dd/yyyy</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Project</label>
                    <input type="text"
                           name="nama_project"
                           class="form-control"
                           value="{{ old('nama_project') }}"
                           required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </form>

        </div>
    </div>
</div>
@endsection