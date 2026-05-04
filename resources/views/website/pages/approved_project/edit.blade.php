@extends('website.layouts.main', ['title' => 'Edit Aktif Project'])

@section('content')
@php
    $project = $approvedProject->project ?? null;

    $tanggalValue = null;

    if (!empty($approvedProject->hari) && !empty($approvedProject->bulan) && !empty($approvedProject->tahun)) {
        $tanggalValue = sprintf(
            '%04d-%02d-%02d',
            (int) $approvedProject->tahun,
            (int) $approvedProject->bulan,
            (int) $approvedProject->hari
        );
    }

    $tanggalAktif = '-';

    if (!empty($approvedProject->hari) && !empty($approvedProject->bulan) && !empty($approvedProject->tahun)) {
        $tanggalAktif = sprintf(
            '%02d/%02d/%04d',
            (int) $approvedProject->bulan,
            (int) $approvedProject->hari,
            (int) $approvedProject->tahun
        );
    }

    $targetProject = null;

    if ($project && !empty($project->reschedule_target_id)) {
        $targetProject = \App\Models\Project::find($project->reschedule_target_id);
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
                    <strong>{{ $approvedProject->nama_project ?? '-' }}</strong>
                    <div class="text-muted">
                        Status: {{ $project->final_status ?? '-' }} |
                        Tanggal Aktif: {{ $tanggalAktif }}
                    </div>
                </div>

                <table class="table table-bordered mb-4">
                    <tr>
                        <th style="width:300px;background:#65a9df;color:#000;">Project Name</th>
                        <td>
                            <input type="text"
                                   name="nama_project"
                                   class="form-control"
                                   value="{{ old('nama_project', $approvedProject->nama_project ?? $project->nama_project ?? '') }}"
                                   required>
                        </td>
                    </tr>

                    <tr>
                        <th style="background:#65a9df;color:#000;">User</th>
                        <td>
                            <input type="text"
                                   name="nama"
                                   class="form-control"
                                   value="{{ old('nama', $approvedProject->nama ?? $project->fullname ?? '') }}"
                                   required>
                        </td>
                    </tr>

                    <tr>
                        <th style="background:#65a9df;color:#000;">Department</th>
                        <td>
                            <input type="text"
                                   name="department"
                                   class="form-control"
                                   value="{{ old('department', $approvedProject->department ?? $project->department ?? '') }}"
                                   required>
                        </td>
                    </tr>

                    <tr>
                        <th style="background:#65a9df;color:#000;">Tanggal Aktif</th>
                        <td>
                            <input type="date"
                                   name="tanggal"
                                   class="form-control"
                                   value="{{ old('tanggal', $tanggalValue) }}"
                                   required>
                        </td>
                    </tr>

                    <tr>
                        <th style="background:#65a9df;color:#000;">No. HP</th>
                        <td>
                            <input type="text"
                                   name="phone"
                                   class="form-control"
                                   value="{{ old('phone', $project->phone ?? '') }}">
                        </td>
                    </tr>

                    <tr>
                        <th style="background:#65a9df;color:#000;">Start Date</th>
                        <td>
                            <input type="date"
                                   name="start_date"
                                   class="form-control"
                                   value="{{ old('start_date', $project && $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '') }}">
                        </td>
                    </tr>

                    <tr>
                        <th style="background:#65a9df;color:#000;">End Date</th>
                        <td>
                            <input type="date"
                                   name="end_date"
                                   class="form-control"
                                   value="{{ old('end_date', $project && $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : '') }}">
                        </td>
                    </tr>

                    <tr>
                        <th style="background:#65a9df;color:#000;">Lampiran</th>
                        <td>
                            @if($project && $project->lampiran)
                                <a href="{{ asset('storage/' . $project->lampiran) }}" target="_blank" class="btn btn-success btn-sm">
                                    <i class="mdi mdi-file-download"></i> VIEW
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th style="background:#65a9df;color:#000;">Kondisi Sebelum Improvement</th>
                        <td>
                            <textarea name="kondisi_sebelum"
                                      class="form-control"
                                      rows="3">{{ old('kondisi_sebelum', $project->kondisi_sebelum ?? '') }}</textarea>
                        </td>
                    </tr>

                    <tr>
                        <th style="background:#65a9df;color:#000;">Kondisi Yang Diharapkan</th>
                        <td>
                            <textarea name="kondisi_target"
                                      class="form-control"
                                      rows="3">{{ old('kondisi_target', $project->kondisi_target ?? '') }}</textarea>
                        </td>
                    </tr>

                    <tr>
                        <th style="background:#65a9df;color:#000;">Benefit Yang Didapat</th>
                        <td>
                            <textarea name="benefit"
                                      class="form-control"
                                      rows="3">{{ old('benefit', $project->benefit ?? '') }}</textarea>
                        </td>
                    </tr>

                    <tr>
                        <th style="background:#65a9df;color:#000;">Additional Support Device</th>
                        <td>
                            <input type="text"
                                   name="alat"
                                   class="form-control"
                                   value="{{ old('alat', $project->alat ?? '') }}">
                        </td>
                    </tr>

                    @if($targetProject)
                        <tr>
                            <td colspan="2" style="border:none;padding-top:20px;">
                                <h5 style="color:#8a6500;">Target Project</h5>
                            </td>
                        </tr>

                        <input type="hidden" name="target_project_id" value="{{ $targetProject->id }}">

                        <tr>
                            <th style="background:#fff3cd;color:#000;">Project Name</th>
                            <td style="background:#fff8e1;">
                                <input type="text"
                                       name="target_nama_project"
                                       class="form-control"
                                       value="{{ old('target_nama_project', $targetProject->nama_project ?? '') }}">
                            </td>
                        </tr>

                        <tr>
                            <th style="background:#fff3cd;color:#000;">User NPK</th>
                            <td style="background:#fff8e1;">
                                <input type="text"
                                       name="target_npk"
                                       class="form-control"
                                       value="{{ old('target_npk', $targetProject->npk ?? '') }}">
                            </td>
                        </tr>

                        <tr>
                            <th style="background:#fff3cd;color:#000;">User Name</th>
                            <td style="background:#fff8e1;">
                                <input type="text"
                                       name="target_fullname"
                                       class="form-control"
                                       value="{{ old('target_fullname', $targetProject->fullname ?? '') }}">
                            </td>
                        </tr>

                        <tr>
                            <th style="background:#fff3cd;color:#000;">Department</th>
                            <td style="background:#fff8e1;">
                                <input type="text"
                                       name="target_department"
                                       class="form-control"
                                       value="{{ old('target_department', $targetProject->department ?? '') }}">
                            </td>
                        </tr>

                        <tr>
                            <th style="background:#fff3cd;color:#000;">Alasan Reschedule</th>
                            <td style="background:#fff8e1;">
                                <textarea name="reschedule_reason"
                                          class="form-control"
                                          rows="3">{{ old('reschedule_reason', $project->reschedule_reason ?? '') }}</textarea>
                            </td>
                        </tr>
                    @endif
                </table>

                <button type="submit" class="btn btn-primary">
                    Update
                </button>
            </form>

        </div>
    </div>

</div>
@endsection