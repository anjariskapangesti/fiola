<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectTimelineRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProjectTimelineController extends Controller
{
    public function index()
    {
        $projectsApproved = Project::where('is_timeline_active', true)
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->orderBy('start_date', 'asc')
            ->get([
                'id',
                'no_reg',
                'nama_project',
                'fullname',
                'department',
                'start_date',
                'end_date',
                'final_status',
            ]);

        $timelineRows = [];
        $timelineStart = null;
        $timelineEnd = null;

        foreach ($projectsApproved as $project) {
            $startMs = Carbon::parse($project->start_date)->startOfDay()->timestamp * 1000;
            $endMs = Carbon::parse($project->end_date)->endOfDay()->timestamp * 1000;
            $durationDays = Carbon::parse($project->start_date)
                ->diffInDays(Carbon::parse($project->end_date)) + 1;

            $timelineRows[] = [
                'nama_project' => $project->nama_project,
                'no_reg' => $project->no_reg,
                'requestor' => $project->fullname,
                'department' => $project->department,
                'status' => $project->final_status,
                'start_ms' => $startMs,
                'end_ms' => $endMs,
                'start_label' => Carbon::parse($project->start_date)->format('d M Y'),
                'end_label' => Carbon::parse($project->end_date)->format('d M Y'),
                'duration_days' => $durationDays,
            ];

            if ($timelineStart === null || $startMs < $timelineStart) {
                $timelineStart = $startMs;
            }

            if ($timelineEnd === null || $endMs > $timelineEnd) {
                $timelineEnd = $endMs;
            }
        }

        if ($timelineStart !== null && $timelineEnd !== null) {
            $timelineStart = Carbon::createFromTimestampMs($timelineStart)->subDays(2)->timestamp * 1000;
            $timelineEnd = Carbon::createFromTimestampMs($timelineEnd)->addDays(2)->timestamp * 1000;
        }

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

        $incomingRequests = ProjectTimelineRequest::with([
                'requestProject',
                'replaceProject',
                'requester',
                'ownerApprover',
                'managerApprover',
                'directorApprover',
            ])
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('requested_to', Auth::id())
                        ->where('status', 'pending_owner');
                });

                if (Auth::user()->can('approve_mgr')) {
                    $query->orWhere('status', 'pending_manager');
                }

                if (Auth::user()->can('approve_dir')) {
                    $query->orWhere('status', 'pending_director');
                }
            })
            ->latest()
            ->get();

        return view('website.pages.project_timeline.index', compact(
            'activeProjects',
            'eligibleProjects',
            'incomingRequests',
            'timelineRows',
            'timelineStart',
            'timelineEnd'
        ));
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

        if (!$request->replace_project_id) {
            return back()->with('error', 'Project aktif yang diganti wajib dipilih.');
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
            ->whereIn('status', [
                'pending_owner',
                'pending_manager',
                'pending_director',
            ])
            ->exists();

        if ($alreadyPending) {
            return back()->with('error', 'Project ini sudah punya request pending.');
        }

        ProjectTimelineRequest::create([
            'request_project_id' => $project->id,
            'replace_project_id' => $replaceProject->id,
            'requested_by' => Auth::id(),
            'requested_to' => $receiverUserId,
            'status' => 'pending_owner',
            'message' => $request->message,
        ]);

        return back()->with('success', 'Request berhasil dikirim. Menunggu approval owner project.');
    }

    public function approve($id)
    {
        $timelineRequest = ProjectTimelineRequest::with([
                'requestProject',
                'replaceProject',
            ])
            ->findOrFail($id);

        if ($timelineRequest->status === 'pending_owner') {
            if ($timelineRequest->requested_to != Auth::id()) {
                abort(403);
            }

            $timelineRequest->update([
                'status' => 'pending_manager',
                'owner_approved_by' => Auth::id(),
                'owner_approved_at' => now(),
            ]);

            return back()->with('success', 'Owner approval berhasil. Menunggu approval manager.');
        }

        if ($timelineRequest->status === 'pending_manager') {
            if (!Auth::user()->can('approve_mgr')) {
                abort(403);
            }

            $timelineRequest->update([
                'status' => 'pending_director',
                'manager_approved_by' => Auth::id(),
                'manager_approved_at' => now(),
            ]);

            return back()->with('success', 'Manager approval berhasil. Menunggu approval director.');
        }

        if ($timelineRequest->status === 'pending_director') {
            if (!Auth::user()->can('approve_dir')) {
                abort(403);
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
                    'director_approved_by' => Auth::id(),
                    'director_approved_at' => now(),
                    'responded_at' => now(),
                ]);
            });

            return back()->with('success', 'Director approval berhasil. Project baru masuk timeline.');
        }

        return back()->with('error', 'Request ini sudah diproses.');
    }

    public function reject($id)
    {
        $timelineRequest = ProjectTimelineRequest::findOrFail($id);

        $canReject =
            ($timelineRequest->status === 'pending_owner' && $timelineRequest->requested_to == Auth::id()) ||
            ($timelineRequest->status === 'pending_manager' && Auth::user()->can('approve_mgr')) ||
            ($timelineRequest->status === 'pending_director' && Auth::user()->can('approve_dir'));

        if (!$canReject) {
            abort(403);
        }

        $timelineRequest->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Request ditolak.');
    }
}