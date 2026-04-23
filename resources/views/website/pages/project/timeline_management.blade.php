@extends('website.layouts.main')

@section('content')
<div class="container">
    <h3>Timeline Management</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h5>Project Aktif</h5>
    @forelse($activeProjects as $p)
        <div class="card p-2 mb-2">
            <strong>{{ $p->nama_project }}</strong><br>
            <small>{{ $p->no_reg }}</small>
        </div>
    @empty
        <div class="text-muted">Belum ada project aktif.</div>
    @endforelse

    <hr>

    <h5>Ajukan Project</h5>

    <form method="POST" action="{{ route('timeline.request') }}">
        @csrf

        <select name="request_project_id" class="form-control" required>
            <option value="">-- pilih project finished --</option>
            @foreach($eligibleProjects as $p)
                <option value="{{ $p->id }}">{{ $p->nama_project }} - {{ $p->no_reg }}</option>
            @endforeach
        </select>

        <select name="replace_project_id" class="form-control mt-2">
            <option value="">-- pilih project aktif yang diganti --</option>
            @foreach($activeProjects as $p)
                <option value="{{ $p->id }}">{{ $p->nama_project }} - {{ $p->no_reg }}</option>
            @endforeach
        </select>

        <textarea name="message" class="form-control mt-2" placeholder="Pesan"></textarea>

        <button class="btn btn-primary mt-2">Kirim</button>
    </form>

    <hr>

    <h5>Request Masuk</h5>

    @forelse($incomingRequests as $r)
        <div class="card p-3 mb-2">
            <strong>{{ $r->requester->name ?? '-' }}</strong>
            ingin menggantikan
            <strong>{{ $r->replaceProject->nama_project ?? '-' }}</strong>
            dengan project
            <strong>{{ $r->requestProject->nama_project ?? '-' }}</strong>

            @if(!empty($r->message))
                <div class="mt-2 text-muted">{{ $r->message }}</div>
            @endif

            <div class="mt-3 d-flex gap-2">
                <form method="POST" action="{{ route('website.project_timeline.approve', $r->id) }}">
                    @csrf
                    <button class="btn btn-success">Yes</button>
                </form>

                <form method="POST" action="{{ route('website.project_timeline.reject', $r->id) }}">
                    @csrf
                    <button class="btn btn-danger">No</button>
                </form>
            </div>
        </div>
    @empty
        <div class="text-muted">Belum ada request masuk.</div>
    @endforelse
</div>
@endsection