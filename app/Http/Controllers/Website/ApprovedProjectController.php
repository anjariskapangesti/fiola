<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ApprovedProject;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
        $projectTable = (new Project)->getTable();

        $request->validate([
            'project_id' => [
                'required',
                Rule::exists($projectTable, 'id'),
            ],
            'tanggal' => 'required|date',
        ]);

        $project = Project::where('id', $request->project_id)
            ->where('final_status', 'Director Approve')
            ->firstOrFail();

        $tanggal = Carbon::parse($request->tanggal);

        ApprovedProject::updateOrCreate(
            [
                'project_id' => $project->id,
            ],
            [
                'nama' => $project->fullname
                    ?? $project->requestor
                    ?? $project->name
                    ?? '-',

                'department' => $project->department
                    ?? $project->department_name
                    ?? '-',

                'hari' => $tanggal->format('d'),
                'bulan' => $tanggal->format('m'),
                'tahun' => $tanggal->format('Y'),

                'nama_project' => $project->nama_project ?? '-',
            ]
        );

        $project->update([
            'final_status' => 'Director Approve',
            'is_timeline_active' => true,
        ]);

        return redirect()
            ->route('website.approved_project.index')
            ->with('success', 'Project aktif berhasil disimpan.');
    }

    public function edit($id)
    {
        $approvedProject = ApprovedProject::findOrFail($id);

        return view('website.pages.approved_project.edit', compact('approvedProject'));
    }

    public function update(Request $request, $id)
    {
        $approvedProject = ApprovedProject::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nama_project' => 'required|string|max:255',
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
                    'is_timeline_active' => true,
                    'final_status' => 'Director Approve',
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