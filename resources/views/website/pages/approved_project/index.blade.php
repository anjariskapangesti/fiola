@extends('website.layouts.main', ['title' => 'Aktif Project'])

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Aktif Project</h5>

            <a href="{{ route('website.approved_project.create') }}" class="btn btn-primary me-3">
                Tambah Data
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success mx-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead style="background:#8b4cf6; color:white;">
                        <tr>
                            <th width="60">No</th>
                            <th>Nama Project</th>
                            <th>Requestor</th>
                            <th>Department</th>
                            <th>Kondisi Sebelum Improvement</th>
                            <th>Kondisi yang Diharapkan</th>
                            <th width="160">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($approvedProjects as $index => $project)
                            @php
                                $active = $activeProjectData[$project->id] ?? null;

                                $namaProject = $active->nama_project
                                    ?? $project->nama_project
                                    ?? '-';

                                $requestor = trim(($project->npk ?? '-') . ' / ' . ($project->fullname ?? '-'));

                                $department = $active->department
                                    ?? $project->department
                                    ?? '-';

                                $kondisiSebelum = $project->kondisi_sebelum ?? '-';

                                $kondisiTarget = $project->kondisi_target ?? '-';
                            @endphp

                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>{{ $namaProject }}</td>

                                <td>{{ $requestor }}</td>

                                <td>{{ $department }}</td>

                                <td style="white-space: normal; min-width: 220px;">
                                    {{ \Illuminate\Support\Str::limit($kondisiSebelum, 60) }}
                                </td>

                                <td style="white-space: normal; min-width: 220px;">
                                    {{ \Illuminate\Support\Str::limit($kondisiTarget, 60) }}
                                </td>

                                <td>
                                    @if($active)
                                        <a href="{{ route('website.approved_project.edit', $active->id) }}"
                                           class="btn btn-warning btn-sm"
                                           title="Edit / Detail">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>

                                        <form action="{{ route('website.approved_project.destroy', $active->id) }}"
                                              method="POST"
                                              style="display:inline-block;"
                                              onsubmit="return confirm('Yakin ingin hapus project ini dari Aktif Project dan Dashboard?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-danger btn-sm"
                                                    title="Delete">
                                                <i class="mdi mdi-trash-can"></i>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('website.approved_project.edit_project', $project->id) }}"
                                           class="btn btn-warning btn-sm"
                                           title="Edit / Detail">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>

                                        <form action="{{ route('website.approved_project.destroy_project', $project->id) }}"
                                              method="POST"
                                              style="display:inline-block;"
                                              onsubmit="return confirm('Yakin ingin hapus project ini dari Dashboard?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-danger btn-sm"
                                                    title="Delete">
                                                <i class="mdi mdi-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Belum ada project aktif.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection