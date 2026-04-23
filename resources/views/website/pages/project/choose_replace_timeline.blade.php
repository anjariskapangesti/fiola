@extends('website.layouts.main')

@section('content')
<div class="container">
    <h3>Pilih Project yang Akan Digantikan</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card p-3 mb-3">
        <h5>Project Baru Selesai</h5>
        <p><strong>No Reg:</strong> {{ $project->no_reg }}</p>
        <p><strong>Project Name:</strong> {{ $project->nama_project }}</p>
        <p><strong>Requestor:</strong> {{ $project->fullname }}</p>
        <p><strong>Periode:</strong> {{ optional($project->start_date)->format('Y-m-d') }} s/d {{ optional($project->end_date)->format('Y-m-d') }}</p>
    </div>

    <form method="POST" action="{{ route('website.project_timeline.send_request_after_finish', $project->id) }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Pilih project aktif yang ingin digantikan</label>
            <select name="replace_project_id" class="form-control" required>
                <option value="">-- pilih project aktif --</option>
                @foreach($activeProjects as $active)
                    <option value="{{ $active->id }}">
                        {{ $active->no_reg }} - {{ $active->nama_project }} ({{ $active->fullname }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Pesan</label>
            <textarea name="message" class="form-control" rows="3" placeholder="Contoh: apakah project ini bisa di-reschedule?"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Kirim Request</button>
        <a href="{{ route('website.home') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection