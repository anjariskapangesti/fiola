<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ApprovedProject;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovedProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->can('can_master')) {
                abort(403, 'Unauthorized');
            }

            return $next($request);
        });
    }

    public function index()
    {
        $approvedProjects = Project::where('final_status', 'Director Approve')
            ->orderBy('created_at', 'desc')
            ->get();

        $activeProjectData = ApprovedProject::whereNotNull('project_id')
            ->get()
            ->keyBy('project_id');

        return view('website.pages.approved_project.index', compact(
            'approvedProjects',
            'activeProjectData'
        ));
    }

    public function create(Request $request)
    {
        $projects = Project::where('final_status', 'Director Approve')
            ->orderBy('created_at', 'desc')
            ->get();

        $alreadyAddedProjectIds = ApprovedProject::whereNotNull('project_id')
            ->pluck('project_id')
            ->toArray();

        return view('website.pages.approved_project.create', compact(
            'projects',
            'alreadyAddedProjectIds'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_project' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'lampiran' => 'required|file|mimes:pdf|max:10240',
            'kondisi_sebelum' => 'required|string',
            'kondisi_target' => 'required|string',
            'benefit' => 'required|string',
            'device' => 'nullable|string|max:255',
            'qty' => 'nullable|numeric',
            'unit' => 'nullable|string|max:255',
        ]);

        $lampiranPath = null;

        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('lampiran_project', 'public');
        }

        $user = Auth::user();

        $alat = null;
        if ($request->device) {
            $alat = $request->device . ' | ' . ($request->unit ?? 'Unit');
        }

        $project = Project::create([
            'npk' => $user->npk ?? null,
            'fullname' => $user->name ?? '-',
            'department' => $user->department ?? '-',
            'phone' => $user->nohp ?? null,
            'nama_project' => $request->nama_project,
            'lampiran' => $lampiranPath,
            'kondisi_sebelum' => $request->kondisi_sebelum,
            'kondisi_target' => $request->kondisi_target,
            'benefit' => $request->benefit,
            'alat' => $alat,
            'cost' => $request->qty,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'final_status' => 'Director Approve',
            'is_dir_approve' => true,
            'is_timeline_active' => true,
            'created_by' => $user->id ?? null,
        ]);

        $tanggal = Carbon::parse($request->start_date);

        ApprovedProject::create([
            'project_id' => $project->id,
            'nama' => $user->name ?? '-',
            'department' => $project->department ?? '-',
            'hari' => $tanggal->format('d'),
            'bulan' => $tanggal->format('m'),
            'tahun' => $tanggal->format('Y'),
            'nama_project' => $request->nama_project,
        ]);

        return redirect()
            ->route('website.approved_project.index')
            ->with('success', 'Project aktif berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $approvedProject = ApprovedProject::findOrFail($id);

        return view('website.pages.approved_project.edit', compact('approvedProject'));
    }

    public function editProject($projectId)
    {
        $project = Project::findOrFail($projectId);

        $tanggal = $project->start_date
            ? Carbon::parse($project->start_date)
            : Carbon::now();

        $approvedProject = ApprovedProject::firstOrCreate(
            [
                'project_id' => $project->id,
            ],
            [
                'nama' => $project->fullname ?? '-',
                'department' => $project->department ?? '-',
                'hari' => $tanggal->format('d'),
                'bulan' => $tanggal->format('m'),
                'tahun' => $tanggal->format('Y'),
                'nama_project' => $project->nama_project ?? '-',
            ]
        );

        return redirect()->route('website.approved_project.edit', $approvedProject->id);
    }

    public function update(Request $request, $id)
    {
        $approvedProject = ApprovedProject::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nama_project' => 'required|string|max:255',

            'phone' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'kondisi_sebelum' => 'nullable|string',
            'kondisi_target' => 'nullable|string',
            'benefit' => 'nullable|string',
            'alat' => 'nullable|string',
            'reschedule_reason' => 'nullable|string',

            'target_project_id' => 'nullable|integer',
            'target_nama_project' => 'nullable|string|max:255',
            'target_npk' => 'nullable|string|max:255',
            'target_fullname' => 'nullable|string|max:255',
            'target_department' => 'nullable|string|max:255',
        ]);

        $tanggal = Carbon::parse($request->tanggal);

        $approvedProject->update([
            'nama' => $request->nama,
            'department' => $request->department,
            'hari' => $tanggal->format('d'),
            'bulan' => $tanggal->format('m'),
            'tahun' => $tanggal->format('Y'),
            'nama_project' => $request->nama_project,
        ]);

        if ($approvedProject->project_id) {
            $project = Project::find($approvedProject->project_id);

            if ($project) {
                $project->update([
                    'nama_project' => $request->nama_project,
                    'fullname' => $request->nama,
                    'department' => $request->department,
                    'phone' => $request->phone,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'kondisi_sebelum' => $request->kondisi_sebelum,
                    'kondisi_target' => $request->kondisi_target,
                    'benefit' => $request->benefit,
                    'alat' => $request->alat,
                    'reschedule_reason' => $request->reschedule_reason,
                    'is_timeline_active' => true,
                    'final_status' => 'Director Approve',
                ]);
            }
        }

        if ($request->filled('target_project_id')) {
            $targetProject = Project::find($request->target_project_id);

            if ($targetProject) {
                $targetProject->update([
                    'nama_project' => $request->target_nama_project,
                    'npk' => $request->target_npk,
                    'fullname' => $request->target_fullname,
                    'department' => $request->target_department,
                ]);
            }
        }

        return redirect()
            ->route('website.approved_project.index')
            ->with('success', 'Data project aktif berhasil diupdate.');
    }

    public function destroy($id)
    {
        $approvedProject = ApprovedProject::findOrFail($id);

        if ($approvedProject->project_id) {
            $project = Project::find($approvedProject->project_id);

            if ($project) {
                $project->update([
                    'final_status' => 'Deleted From Active Project',
                    'is_timeline_active' => false,
                    'timeline_order' => null,
                ]);
            }
        }

        $approvedProject->delete();

        return redirect()
            ->route('website.approved_project.index')
            ->with('success', 'Data project aktif berhasil dihapus dari dashboard.');
    }

    public function destroyProject($id)
    {
        $project = Project::findOrFail($id);

        ApprovedProject::where('project_id', $project->id)->delete();

        $project->update([
            'final_status' => 'Deleted From Active Project',
            'is_timeline_active' => false,
            'timeline_order' => null,
        ]);

        return redirect()
            ->route('website.approved_project.index')
            ->with('success', 'Project berhasil dihapus dari Aktif Project dan Dashboard.');
    }
}