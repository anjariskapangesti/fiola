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

    <form method="POST" action="{{ route('website.project_timeline.request_join') }}">
        @csrf

        <select name="request_project_id" class="form-control" required>
            <option value="">-- pilih project finished --</option>
            @foreach($eligibleProjects as $p)
                <option value="{{ $p->id }}">
                    {{ $p->nama_project }} - {{ $p->no_reg }}
                </option>
            @endforeach
        </select>

        <select name="replace_project_id" class="form-control mt-2">
            <option value="">-- pilih project aktif yang diganti --</option>
            @foreach($activeProjects as $p)
                <option value="{{ $p->id }}">
                    {{ $p->nama_project }} - {{ $p->no_reg }}
                </option>
            @endforeach
        </select>

        <textarea name="message" class="form-control mt-2" placeholder="Pesan"></textarea>

        <button class="btn btn-primary mt-2">Kirim</button>
    </form>

    <hr>

    <h5>Request Masuk</h5>

    @forelse($incomingRequests as $r)
        <div class="card p-3 mb-2">
            <div>
                <strong>{{ $r->requester->name ?? '-' }}</strong>
                ingin menggantikan
                <strong>{{ $r->replaceProject->nama_project ?? '-' }}</strong>
                dengan project
                <strong>{{ $r->requestProject->nama_project ?? '-' }}</strong>
            </div>

            @if(!empty($r->message))
                <div class="mt-2 text-muted">
                    {{ $r->message }}
                </div>
            @endif

            <div class="mt-2">
                @if ($r->status == 'pending_owner')
                    <span class="badge bg-warning">Waiting Owner Approval</span>
                @elseif ($r->status == 'pending_manager')
                    <span class="badge bg-info">Waiting Manager Approval</span>
                @elseif ($r->status == 'pending_director')
                    <span class="badge bg-primary">Waiting Director Approval</span>
                @elseif ($r->status == 'approved')
                    <span class="badge bg-success">Approved</span>
                @elseif ($r->status == 'rejected')
                    <span class="badge bg-danger">Rejected</span>
                @else
                    <span class="badge bg-secondary">{{ $r->status }}</span>
                @endif
            </div>

            <div class="mt-2">
                <small>
                    Owner:
                    @if(!empty($r->owner_approved_by))
                        <span class="text-success">✔ {{ $r->ownerApprover->name ?? 'Approved' }}</span>
                    @else
                        <span class="text-muted">Pending</span>
                    @endif
                    <br>

                    Manager:
                    @if(!empty($r->manager_approved_by))
                        <span class="text-success">✔ {{ $r->managerApprover->name ?? 'Approved' }}</span>
                    @else
                        <span class="text-muted">Pending</span>
                    @endif
                    <br>

                    Director:
                    @if(!empty($r->director_approved_by))
                        <span class="text-success">✔ {{ $r->directorApprover->name ?? 'Approved' }}</span>
                    @else
                        <span class="text-muted">Pending</span>
                    @endif
                </small>
            </div>

            <div class="mt-3 d-flex gap-2">
                @if (
                    ($r->status == 'pending_owner' && $r->requested_to == auth()->id()) ||
                    ($r->status == 'pending_manager' && auth()->user()->can('approve_mgr')) ||
                    ($r->status == 'pending_director' && auth()->user()->can('approve_dir'))
                )
                    <form method="POST" action="{{ route('website.project_timeline.approve', $r->id) }}">
                        @csrf

                        @if ($r->status == 'pending_owner')
                            <button class="btn btn-success">Approve Owner</button>
                        @elseif ($r->status == 'pending_manager')
                            <button class="btn btn-success">Approve Manager</button>
                        @elseif ($r->status == 'pending_director')
                            <button class="btn btn-success">Approve Director</button>
                        @else
                            <button class="btn btn-success">Approve</button>
                        @endif
                    </form>

                    <form method="POST" action="{{ route('website.project_timeline.reject', $r->id) }}">
                        @csrf
                        <button class="btn btn-danger">Reject</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="text-muted">Belum ada request masuk.</div>
    @endforelse
</div>
@endsection