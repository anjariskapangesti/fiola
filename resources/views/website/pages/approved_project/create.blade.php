@extends('website.layouts.main', ['title' => 'Tambah Aktif Project'])

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <form action="{{ route('website.approved_project.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card mb-4">
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

                <div class="mb-3">
                    <label class="form-label">Nama Project <span class="text-danger">*</span></label>
                    <input type="text"
                           name="nama_project"
                           class="form-control"
                           value="{{ old('nama_project') }}"
                           required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Date <span class="text-danger">*</span></label>
                        <input type="date"
                               name="start_date"
                               class="form-control"
                               value="{{ old('start_date') }}"
                               required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Date <span class="text-danger">*</span></label>
                        <input type="date"
                               name="end_date"
                               class="form-control"
                               value="{{ old('end_date') }}"
                               required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">File PDF Konsep <span class="text-danger">*</span></label>
                    <input type="file"
                           name="lampiran"
                           class="form-control"
                           accept="application/pdf"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kondisi Sebelum Improvement <span class="text-danger">*</span></label>
                    <textarea name="kondisi_sebelum"
                              class="form-control"
                              rows="3"
                              required>{{ old('kondisi_sebelum') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kondisi yang Diharapkan <span class="text-danger">*</span></label>
                    <textarea name="kondisi_target"
                              class="form-control"
                              rows="3"
                              required>{{ old('kondisi_target') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Benefit yang Didapat <span class="text-danger">*</span></label>
                    <textarea name="benefit"
                              class="form-control"
                              rows="3"
                              required>{{ old('benefit') }}</textarea>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <h5 class="card-header">Additional Support Device</h5>

            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Device</label>
                        <input type="text"
                               name="device"
                               class="form-control"
                               value="{{ old('device') }}"
                               placeholder="Device">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">QTY</label>
                        <input type="number"
                               name="qty"
                               class="form-control"
                               value="{{ old('qty') }}"
                               placeholder="QTY">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">Unit</label>
                        <input type="text"
                               name="unit"
                               class="form-control"
                               value="{{ old('unit', 'Unit') }}"
                               placeholder="Unit">
                    </div>

                    <div class="col-md-2 mb-3">
                        <button type="button" class="btn btn-success w-100">
                            +
                        </button>
                    </div>
                </div>

                <button type="button" class="btn btn-primary">
                    List Alat dan Harga
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            Simpan
        </button>
    </form>
</div>
@endsection