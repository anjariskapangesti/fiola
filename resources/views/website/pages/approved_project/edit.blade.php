@extends('website.layouts.main', ['title' => 'Edit Aktif Project'])

@section('content')
@php
    $tanggalValue = null;

    if (!empty($approvedProject->hari) && !empty($approvedProject->bulan) && !empty($approvedProject->tahun)) {
        $tanggalValue = sprintf(
            '%04d-%02d-%02d',
            (int) $approvedProject->tahun,
            (int) $approvedProject->bulan,
            (int) $approvedProject->hari
        );
    }
@endphp

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Edit Aktif Project</h5>

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

            <form action="{{ route('website.approved_project.update', $approvedProject->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text"
                           name="nama"
                           class="form-control"
                           value="{{ old('nama', $approvedProject->nama) }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <input type="text"
                           name="department"
                           class="form-control"
                           value="{{ old('department', $approvedProject->department) }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date"
                           name="tanggal"
                           class="form-control"
                           value="{{ old('tanggal', $tanggalValue) }}"
                           required>
                    <small class="text-muted">Format akan ditampilkan sebagai mm/dd/yyyy</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Project</label>
                    <input type="text"
                           name="nama_project"
                           class="form-control"
                           value="{{ old('nama_project', $approvedProject->nama_project) }}"
                           required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Update
                </button>
            </form>
        </div>
    </div>
</div>
@endsection