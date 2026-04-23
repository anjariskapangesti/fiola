<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectTimelineRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectTimelineController extends Controller
{
    public function index()
    {
        $activeProjects = Project::where('is_timeline_active', true)
            ->where('final_status', 'Finished')
            ->orderBy('timeline_order', 'asc')
            ->get();

        $eligibleProjects = Project::where('final_status', 'Finished')
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->where('is_timeline_active', false)
            ->orderBy('finish_date', 'desc')
            ->get();

        $incomingRequests = ProjectTimelineRequest::with(['requestProject', 'replaceProject', 'requester'])
            ->where('requested_to', Auth::id())
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('website.project.timeline_management', compact(
            'activeProjects',
            'eligibleProjects',
            'incomingRequests'
        ));
    }

    public function chooseReplace($projectId)
    {
        $project = Project::findOrFail($projectId);

        if ($project->final_status !== 'Finished') {
            return redirect()->route('website.project.list')->with('error', 'Project belum finished.');
        }

        if ($project->is_timeline_active) {
            return redirect()->route('website.home')->with('info', 'Project sudah ada di timeline.');
        }

        $activeProjects = Project::where('is_timeline_active', true)
            ->where('final_status', 'Finished')
            ->orderBy('timeline_order', 'asc')
            ->get();

        return view('website.project.choose_replace_timeline', compact('project', 'activeProjects'));
    }

    public function sendRequestAfterFinish(Request $request, $projectId)
    {
        $request->validate([
            'replace_project_id' => 'required|exists:form_project,id',
            'message' => 'nullable|string|max:1000',
        ]);

        $project = Project::findOrFail($projectId);

        if ($project->final_status !== 'Finished') {
            return back()->with('error', 'Hanya project finished yang bisa diajukan ke timeline.');
        }

        if ($project->is_timeline_active) {
            return back()->with('error', 'Project ini sudah aktif di timeline.');
        }

        $replaceProject = Project::findOrFail($request->replace_project_id);

        if (!$replaceProject->is_timeline_active) {
            return back()->with('error', 'Project target bukan project aktif timeline.');
        }

        $receiverUserId = $replaceProject->finish_by ?: $replaceProject->created_by;

        if (!$receiverUserId) {
            return back()->with('error', 'User penerima request tidak ditemukan.');
        }

        $alreadyPending = ProjectTimelineRequest::where('request_project_id', $project->id)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyPending) {
            return back()->with('error', 'Project ini sudah punya request pending.');
        }

        ProjectTimelineRequest::create([
            'request_project_id' => $project->id,
            'replace_project_id' => $replaceProject->id,
            'requested_by' => Auth::id(),
            'requested_to' => $receiverUserId,
            'status' => 'pending',
            'message' => $request->message,
        ]);

        return redirect()->route('website.home')->with('success', 'Request penggantian timeline berhasil dikirim.');
    }

    public function requestJoin(Request $request)
    {
        $request->validate([
            'request_project_id' => 'required|exists:form_project,id',
            'replace_project_id' => 'nullable|exists:form_project,id',
            'message' => 'nullable|string|max:1000',
        ]);

        $project = Project::findOrFail($request->request_project_id);

        if ($project->final_status !== 'Finished') {
            return back()->with('error', 'Hanya project finished yang bisa masuk timeline.');
        }

        if (!$project->start_date || !$project->end_date) {
            return back()->with('error', 'Project harus punya start date dan end date.');
        }

        if ($project->is_timeline_active) {
            return back()->with('error', 'Project ini sudah ada di timeline.');
        }

        $activeCount = Project::where('is_timeline_active', true)
            ->where('final_status', 'Finished')
            ->count();

        if ($activeCount < 2) {
            $maxOrder = Project::where('is_timeline_active', true)->max('timeline_order');

            $project->update([
                'is_timeline_active' => true,
                'timeline_order' => $maxOrder ? ($maxOrder + 1) : 1,
            ]);

            return back()->with('success', 'Project langsung masuk timeline karena slot masih tersedia.');
        }

        $replaceProject = Project::findOrFail($request->replace_project_id);

        if (!$replaceProject->is_timeline_active) {
            return back()->with('error', 'Project pengganti harus project aktif di timeline.');
        }

        $receiverUserId = $replaceProject->finish_by ?: $replaceProject->created_by;

        if (!$receiverUserId) {
            return back()->with('error', 'User penerima request tidak ditemukan.');
        }

        $alreadyPending = ProjectTimelineRequest::where('request_project_id', $project->id)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyPending) {
            return back()->with('error', 'Project ini sudah punya request pending.');
        }

        ProjectTimelineRequest::create([
            'request_project_id' => $project->id,
            'replace_project_id' => $replaceProject->id,
            'requested_by' => Auth::id(),
            'requested_to' => $receiverUserId,
            'status' => 'pending',
            'message' => $request->message,
        ]);

        return back()->with('success', 'Request penggantian timeline berhasil dikirim.');
    }

    public function approve($id)
    {
        $timelineRequest = ProjectTimelineRequest::with(['requestProject', 'replaceProject'])
            ->findOrFail($id);

        if ($timelineRequest->requested_to != Auth::id()) {
            abort(403);
        }

        if ($timelineRequest->status !== 'pending') {
            return back()->with('error', 'Request ini sudah diproses.');
        }

        DB::transaction(function () use ($timelineRequest) {
            $oldProject = $timelineRequest->replaceProject;
            $newProject = $timelineRequest->requestProject;

            if ($oldProject) {
                $oldProject->update([
                    'is_timeline_active' => false,
                    'timeline_order' => null,
                ]);
            }

            $newProject->update([
                'is_timeline_active' => true,
                'timeline_order' => 1,
            ]);

            $activeProjects = Project::where('is_timeline_active', true)
                ->where('final_status', 'Finished')
                ->orderBy('updated_at', 'asc')
                ->get();

            $order = 1;
            foreach ($activeProjects as $project) {
                $project->update([
                    'timeline_order' => $order,
                ]);
                $order++;
            }

            $timelineRequest->update([
                'status' => 'approved',
                'responded_at' => now(),
            ]);
        });

        return back()->with('success', 'Request disetujui. Project baru masuk timeline.');
    }

    public function reject($id)
    {
        $timelineRequest = ProjectTimelineRequest::findOrFail($id);

        if ($timelineRequest->requested_to != Auth::id()) {
            abort(403);
        }

        if ($timelineRequest->status !== 'pending') {
            return back()->with('error', 'Request ini sudah diproses.');
        }

        $timelineRequest->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Request ditolak.');
    }
}