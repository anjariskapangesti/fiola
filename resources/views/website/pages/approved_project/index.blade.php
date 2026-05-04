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
                            <th>Nama</th>
                            <th>Department</th>
                            <th width="160">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($approvedProjects as $index => $project)
                            @php
                                $active = $activeProjectData[$project->id] ?? null;

                                $namaUser = $active->nama
                                    ?? $project->fullname
                                    ?? $project->requestor
                                    ?? $project->name
                                    ?? '-';

                                $department = $active->department
                                    ?? $project->department
                                    ?? $project->department_name
                                    ?? '-';

                                $namaProject = $active->nama_project
                                    ?? $project->nama_project
                                    ?? '-';
                            @endphp

                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>{{ $namaProject }}</td>

                                <td>{{ $namaUser }}</td>

                                <td>{{ $department }}</td>

                                <td>
                                    @if($active)
                                        <a href="{{ route('website.approved_project.edit', $active->id) }}"
                                           class="btn btn-warning btn-sm"
                                           title="Edit">
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
                                        <a href="{{ route('website.approved_project.create', ['project_id' => $project->id]) }}"
                                           class="btn btn-warning btn-sm"
                                           title="Edit / Atur Data">
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
                                <td colspan="5" class="text-center text-muted">
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