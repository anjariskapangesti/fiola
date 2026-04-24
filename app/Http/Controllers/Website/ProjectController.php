<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\User;
use App\Models\Alert;
use App\Models\Device;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

use App\Traits\HasAjaxList;

class ProjectController extends Controller
{
    use HasAjaxList;

    public function check_month_limit(Request $request)
    {
        $startDate = Carbon::parse($request->start_date);
        $month = $startDate->month;
        $year = $startDate->year;

        $projects = Project::whereYear('start_date', $year)
            ->whereMonth('start_date', $month)
            ->whereNotIn('final_status', ['Rejected', 'Manager Reject', 'IT Reject', 'IT MGR Reject'])
            ->get();

        if ($projects->count() >= 2) {
            return response()->json([
                'status' => 'full',
                'projects' => $projects
            ]);
        }

        return response()->json(['status' => 'available']);
    }

    public function create()
    {
        $devices = Device::all();

        $auth = User::where('id', Auth::user()->id)
            ->whereNull('nohp')
            ->count();

        $data = Project::where('created_by', Auth::user()->id)
            ->where(function ($query) {
                $query->where('final_status', 'LIKE', '%Reject%')
                    ->orWhere('final_status', 'Finished');
            })
            ->where('is_confirm', 0)
            ->count();

        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } elseif ($data > 0) {
            return redirect()->route('website.project.list')->with('info', 'Harap konfirmasi!');
        } else {
            return view('website.pages.project.create', compact(['devices']));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_project' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'lampiran' => 'required|mimes:pdf',
            'kondisi_sebelum' => 'required',
            'kondisi_target' => 'required',
            'benefit' => 'required',
        ]);

        $year = date('y');
        $month = date('m');

        $lastForm = DB::table('form_project')
            ->select('no_reg')
            ->orderBy('no_reg', 'desc')
            ->first();

        $lastNumber = $lastForm ? substr($lastForm->no_reg, -3) : '000';
        $lastMonth = $lastForm ? substr($lastForm->no_reg, 6, 2) : '00';

        if ($lastMonth !== $month) {
            $lastNumber = '000';
        }

        $newNumber = str_pad((intval($lastNumber) + 1), 3, '0', STR_PAD_LEFT);
        $no_reg = 'PRJ/' . $year . $month . '/' . $newNumber;

        $isManagerApprove = null;
        $managerApprovalDate = null;
        $isItApprove = null;
        $itApprovalDate = null;
        $isItManagerApprove = null;
        $itManagerApprovalDate = null;

        if (
            Auth::user()->can('approve_mgr') ||
            Auth::user()->can('approve_gm') ||
            Auth::user()->can('approve_dir') ||
            Auth::user()->can('approve_vp') ||
            Auth::user()->can('approve_pres')
        ) {
            $finalStatus = 'Manager Approve';
            $isManagerApprove = 1;
            $managerApprovalDate = Carbon::now();
        } else {
            $finalStatus = $request->is_reschedule ? 'Waiting Target Response' : 'created';
        }

        try {
            $photoFileName = null;

            if ($request->hasFile('lampiran')) {
                $photoExtension = $request->lampiran->getClientOriginalExtension();
                $photoFileName = 'PRJ_' . $year . $month . '_' . $newNumber . '.' . $photoExtension;
                $request->lampiran->storeAs('lampiran', $photoFileName, 'public');
            }

            $alatSelected = $request->input('device');
            $qtySelected = $request->input('qty');
            $combinedDescriptionString = null;

            if (is_array($alatSelected) && is_array($qtySelected) && count($alatSelected) === count($qtySelected)) {
                $combinedDescriptions = [];

                foreach ($alatSelected as $index => $alat) {
                    if (!empty($alat)) {
                        $qty = $qtySelected[$index] ?? '';
                        $combinedDescriptions[] = $alat . ' | ' . $qty . ' Unit';
                    }
                }

                $combinedDescriptionString = !empty($combinedDescriptions)
                    ? implode("\n", $combinedDescriptions)
                    : null;
            }

            Project::create([
                'no_reg' => $no_reg,
                'npk' => Auth::user()->npk,
                'fullname' => Auth::user()->name,
                'department' => Auth::user()->departments->pluck('name')->implode(', '),
                'phone' => Auth::user()->nohp,
                'aplikasi' => $request->aplikasi,
                'nama_project' => $request->nama_project,
                'start_date' => request()->input('start_date'),
                'end_date'   => request()->input('end_date'),
                'lampiran' => $photoFileName,
                'kondisi_sebelum' => $request->kondisi_sebelum,
                'kondisi_target' => $request->kondisi_target,
                'benefit' => $request->benefit,
                'alat' => $combinedDescriptionString,
                'created_by' => Auth::user()->id,
                'created_dept' => Auth::user()->departments->pluck('id')->first(),
                'final_status' => $finalStatus,
                'is_manager_approve' => $isManagerApprove,
                'is_it_approve' => $isItApprove,
                'is_it_mgr_approve' => $isItManagerApprove,
                'manager_approval_date' => $managerApprovalDate,
                'it_approval_date' => $itApprovalDate,
                'it_mgr_approval_date' => $itManagerApprovalDate,
                'is_reschedule' => $request->is_reschedule ?? 0,
                'reschedule_target_id' => $request->reschedule_target_id,
                'is_dir_approve' => null,
            ]);

            return redirect()->route('website.project.list')->with('success', 'Berhasil Dibuat');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function list()
    {
        return view('website.pages.project.list');
    }

    public function list_ajax(Request $request)
    {
        $data = Project::join('users', 'form_project.created_by', '=', 'users.id')
            ->leftJoin('users as manager', 'form_project.manager_approve_by', '=', 'manager.id')
            ->leftJoin('users as it', 'form_project.it_approve_by', '=', 'it.id')
            ->leftJoin('users as it_mgr', 'form_project.it_mgr_approve_by', '=', 'it_mgr.id')
            ->leftJoin('users as on_progress', 'form_project.on_progress_by', '=', 'on_progress.id')
            ->leftJoin('users as finish', 'form_project.finish_by', '=', 'finish.id')
            ->select(
                'form_project.*',
                'users.name as requestor',
                'manager.name as manager_name',
                'it.name as it_name',
                'it_mgr.name as it_mgr_name',
                'on_progress.name as on_progress_name',
                'finish.name as finish_name'
            )
            ->orderBy('form_project.created_at', 'desc');

        return DataTables::eloquent($data)->make(true);
    }

    public function approve_form(Request $request)
    {
        $project = Project::findOrFail($request->id);

        if ($request->type == 'confirm') {
            $project->is_confirm = 1;
        } else {
            $project->is_confirm = 0;
        }

        $project->save();

        return "Berhasil Dikonfirmasi";
    }

    public function manager_approval()
    {
        return view('website.pages.project.manager_approval');
    }

    public function manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Project::query();
        
        // If user is IT Manager or Director, they can see all project requests
        // Otherwise, restrict to their own departments
        if (!Auth::user()->can('ITDMGR') && !Auth::user()->can('approve_dir')) {
            $data = $data->where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
                $query->where('created_dept', $firstDepartmentId)
                    ->orWhere('created_dept', $lastDepartmentId);
            });
        }

        $data = $data->where('final_status', 'created')
            ->join('users', 'form_project.created_by', 'users.id')
            ->leftJoin('users as manager', 'form_project.manager_approve_by', 'manager.id')
            ->leftJoin('users as it', 'form_project.it_approve_by', 'it.id')
            ->leftJoin('users as it_mgr', 'form_project.it_mgr_approve_by', 'it_mgr.id')
            ->leftJoin('users as on_progress', 'form_project.on_progress_by', 'on_progress.id')
            ->leftJoin('users as finish', 'form_project.finish_by', 'finish.id')
            ->select(
                'form_project.*',
                'users.name as requestor',
                'manager.name as manager_name',
                'it.name as it_name',
                'it_mgr.name as it_mgr_name',
                'on_progress.name as on_progress_name',
                'finish.name as finish_name'
            )
            ->orderBy('created_at', 'ASC');

        return DataTables::eloquent($data)->make(true);
    }

    public function manager_approve(Request $request)
    {
        $project = Project::findOrFail($request->id);

        if ($request->type == 'approve') {
            $project->is_manager_approve = 1;
            $project->final_status = 'Manager Approve';
            $project->manager_note = $request->manager_note;
            $project->manager_approve_by = Auth::user()->id;
            $return = "Berhasil Disetujui";
        } else {
            $project->is_manager_approve = 0;
            $project->manager_note = $request->manager_note;
            $project->manager_approve_by = Auth::user()->id;
            
            if ($project->is_reschedule) {
                $project->final_status = 'Manager Reject (Reschedule)';
                $return = "Berhasil Ditolak (Berlanjut ke ITD)";
            } else {
                $project->final_status = 'Manager Reject';
                $project->is_finish = 0;
                $project->is_confirm = 0;
                $return = "Berhasil Ditolak";
            }
        }

        $project->manager_approval_date = Carbon::now();
        $project->save();

        return $return;
    }

    public function manager_approved()
    {
        return view('website.pages.project.manager_approved');
    }

    public function manager_approved_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Project::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
                $query->where('created_dept', $firstDepartmentId)
                    ->orWhere('created_dept', $lastDepartmentId);
            })
            ->whereNotNull('is_manager_approve')
            ->join('users', 'form_project.created_by', 'users.id')
            ->leftJoin('users as manager', 'form_project.manager_approve_by', 'manager.id')
            ->leftJoin('users as it', 'form_project.it_approve_by', 'it.id')
            ->leftJoin('users as it_mgr', 'form_project.it_mgr_approve_by', 'it_mgr.id')
            ->leftJoin('users as on_progress', 'form_project.on_progress_by', 'on_progress.id')
            ->leftJoin('users as finish', 'form_project.finish_by', 'finish.id')
            ->select(
                'form_project.*',
                'users.name as requestor',
                'manager.name as manager_name',
                'it.name as it_name',
                'it_mgr.name as it_mgr_name',
                'on_progress.name as on_progress_name',
                'finish.name as finish_name'
            )
            ->orderBy('manager_approval_date', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }

    public function it_approval()
    {
        return view('website.pages.project.it_approval');
    }

    public function it_approval_ajax(Request $request)
    {
        $data = Project::whereIn('final_status', ['Manager Approve', 'Manager Reject (Reschedule)'])
            ->join('users', 'form_project.created_by', 'users.id')
            ->leftJoin('users as manager', 'form_project.manager_approve_by', 'manager.id')
            ->leftJoin('users as it', 'form_project.it_approve_by', 'it.id')
            ->leftJoin('users as it_mgr', 'form_project.it_mgr_approve_by', 'it_mgr.id')
            ->leftJoin('users as on_progress', 'form_project.on_progress_by', 'on_progress.id')
            ->leftJoin('users as finish', 'form_project.finish_by', 'finish.id')
            ->select(
                'form_project.*',
                'users.name as requestor',
                'manager.name as manager_name',
                'it.name as it_name',
                'it_mgr.name as it_mgr_name',
                'on_progress.name as on_progress_name',
                'finish.name as finish_name'
            )
            ->orderBy('created_at', 'ASC');

        return DataTables::eloquent($data)->make(true);
    }

    public function it_approve(Request $request)
    {
        $project = Project::findOrFail($request->id);

        if ($request->type == 'approve') {
            $project->is_it_approve = 1;
            $project->final_status = 'IT Approve';
            $project->it_note = $request->it_note;
            $project->it_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $project->is_it_approve = 0;
            $project->it_note = $request->it_note;
            $project->it_approve_by = Auth::user()->id;
            
            if ($project->is_reschedule) {
                $project->final_status = 'IT Reject (Reschedule)';
                $return = "Berhasil Ditolak (Berlanjut ke IT MGR)";
            } else {
                $project->is_confirm = 0;
                $project->final_status = 'IT Reject';
                $project->is_finish = 0;
                $return = "Reject Successfully";
            }
        }

        $project->it_approval_date = Carbon::now();
        $project->save();

        return $return;
    }

    public function it_approved()
    {
        return view('website.pages.project.it_approved');
    }

    public function it_approved_ajax(Request $request)
    {
        $data = Project::whereNotNull('is_it_approve')
            ->join('users', 'form_project.created_by', 'users.id')
            ->leftJoin('users as manager', 'form_project.manager_approve_by', 'manager.id')
            ->leftJoin('users as it', 'form_project.it_approve_by', 'it.id')
            ->leftJoin('users as it_mgr', 'form_project.it_mgr_approve_by', 'it_mgr.id')
            ->leftJoin('users as on_progress', 'form_project.on_progress_by', 'on_progress.id')
            ->leftJoin('users as finish', 'form_project.finish_by', 'finish.id')
            ->select(
                'form_project.*',
                'users.name as requestor',
                'manager.name as manager_name',
                'it.name as it_name',
                'it_mgr.name as it_mgr_name',
                'on_progress.name as on_progress_name',
                'finish.name as finish_name'
            )
            ->orderBy('manager_approval_date', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }

    public function it_mgr_approval()
    {
        return view('website.pages.project.it_mgr_approval');
    }

    public function it_mgr_approval_ajax(Request $request)
    {
        $data = Project::whereIn('final_status', ['IT Approve', 'IT Reject (Reschedule)'])
            ->join('users', 'form_project.created_by', 'users.id')
            ->leftJoin('users as manager', 'form_project.manager_approve_by', 'manager.id')
            ->leftJoin('users as it', 'form_project.it_approve_by', 'it.id')
            ->leftJoin('users as it_mgr', 'form_project.it_mgr_approve_by', 'it_mgr.id')
            ->leftJoin('users as on_progress', 'form_project.on_progress_by', 'on_progress.id')
            ->leftJoin('users as finish', 'form_project.finish_by', 'finish.id')
            ->select(
                'form_project.*',
                'users.name as requestor',
                'manager.name as manager_name',
                'it.name as it_name',
                'it_mgr.name as it_mgr_name',
                'on_progress.name as on_progress_name',
                'finish.name as finish_name'
            )
            ->orderBy('created_at', 'ASC');

        return DataTables::eloquent($data)->make(true);
    }

    public function it_mgr_approve(Request $request)
    {
        $project = Project::findOrFail($request->id);

        if ($request->type == 'approve') {
            $project->is_it_mgr_approve = 1;
            $project->final_status = 'IT MGR Approve';
            $project->it_mgr_note = $request->it_mgr_note;
            $project->it_mgr_approve_by = Auth::user()->id;

            // Activate timeline for standard projects (reschedule activated by Director)
            if (!$project->is_reschedule) {
                $project->is_timeline_active = true;
                $maxOrder = Project::where('is_timeline_active', true)->max('timeline_order');
                $project->timeline_order = $maxOrder ? ($maxOrder + 1) : 1;
            }

            $return = "Approve Successfully";
        } else {
            $project->is_it_mgr_approve = 0;
            $project->it_mgr_note = $request->it_mgr_note;
            $project->it_mgr_approve_by = Auth::user()->id;
            
            if ($project->is_reschedule) {
                $project->final_status = 'IT MGR Reject (Reschedule)';
                $return = "Berhasil Ditolak (Berlanjut ke Direktur)";
            } else {
                $project->final_status = 'IT MGR Reject';
                $project->is_finish = 0;
                $project->is_confirm = 0;
                $return = "Reject Successfully";
            }
        }

        $project->it_mgr_approval_date = Carbon::now();
        $project->save();

        return $return;
    }

    public function it_mgr_approved()
    {
        return view('website.pages.project.it_mgr_approved');
    }

    public function it_mgr_approved_ajax(Request $request)
    {
        $data = Project::whereNotNull('is_it_mgr_approve')
            ->join('users', 'form_project.created_by', 'users.id')
            ->leftJoin('users as manager', 'form_project.manager_approve_by', 'manager.id')
            ->leftJoin('users as it', 'form_project.it_approve_by', 'it.id')
            ->leftJoin('users as it_mgr', 'form_project.it_mgr_approve_by', 'it_mgr.id')
            ->leftJoin('users as on_progress', 'form_project.on_progress_by', 'on_progress.id')
            ->leftJoin('users as finish', 'form_project.finish_by', 'finish.id')
            ->select(
                'form_project.*',
                'users.name as requestor',
                'manager.name as manager_name',
                'it.name as it_name',
                'it_mgr.name as it_mgr_name',
                'on_progress.name as on_progress_name',
                'finish.name as finish_name'
            )
            ->orderBy('created_at', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }

    public function dir_approval()
    {
        return view('website.pages.project.dir_approval');
    }

    public function dir_approval_ajax(Request $request)
    {
        $data = Project::whereIn('final_status', ['IT MGR Approve', 'IT MGR Reject (Reschedule)'])
            ->join('users', 'form_project.created_by', 'users.id')
            ->leftJoin('users as manager', 'form_project.manager_approve_by', 'manager.id')
            ->leftJoin('users as it', 'form_project.it_approve_by', 'it.id')
            ->leftJoin('users as it_mgr', 'form_project.it_mgr_approve_by', 'it_mgr.id')
            ->select(
                'form_project.*',
                'users.name as requestor',
                'manager.name as manager_name',
                'it.name as it_name',
                'it_mgr.name as it_mgr_name'
            )
            ->orderBy('created_at', 'ASC');

        return DataTables::eloquent($data)->make(true);
    }

    public function dir_approve(Request $request)
    {
        $project = Project::findOrFail($request->id);

        if ($request->type == 'approve') {
            $project->is_dir_approve = 1;
            $project->final_status = 'Director Approve';
            $project->dir_note = $request->dir_note;
            $project->dir_approve_by = Auth::user()->id;
            $project->dir_approval_date = Carbon::now();
            
            // Activate timeline for the new project
            $project->is_timeline_active = true;
            $maxOrder = Project::where('is_timeline_active', true)->max('timeline_order');
            $project->timeline_order = $maxOrder ? ($maxOrder + 1) : 1;
            
            $project->save();

            // Reschedule the target project
            if ($project->reschedule_target_id) {
                $oldProject = Project::find($project->reschedule_target_id);
                if ($oldProject) {
                    $updateData = [
                        'final_status' => 'Rescheduled',
                        'is_timeline_active' => false,
                        'timeline_order' => null
                    ];
                    
                    if ($project->target_response == 'yes' && $project->target_reschedule_start_date) {
                        $updateData['start_date'] = $project->target_reschedule_start_date;
                        $updateData['end_date'] = $project->target_reschedule_end_date;
                        $updateData['is_timeline_active'] = true;
                        $updateData['final_status'] = 'Director Approve';
                    }
                    
                    $oldProject->update($updateData);
                }
            }

            $return = "Berhasil Disetujui. Project dapat berlanjut ke tahap pelaksanaan.";
        } else {
            $project->is_dir_approve = 0;
            $project->final_status = 'Director Reject';
            $project->dir_note = $request->dir_note;
            $project->dir_approve_by = Auth::user()->id;
            $project->dir_approval_date = Carbon::now();
            $project->is_finish = 0;
            $project->is_confirm = 0;
            $project->save();
            $return = "Berhasil Ditolak. Project baru ditolak.";
        }

        return $return;
    }

    public function dir_approved()
    {
        return view('website.pages.project.dir_approved');
    }

    public function dir_approved_ajax(Request $request)
    {
        $data = Project::whereNotNull('is_dir_approve')
            ->join('users', 'form_project.created_by', 'users.id')
            ->leftJoin('users as manager', 'form_project.manager_approve_by', 'manager.id')
            ->leftJoin('users as it', 'form_project.it_approve_by', 'it.id')
            ->leftJoin('users as it_mgr', 'form_project.it_mgr_approve_by', 'it_mgr.id')
            ->leftJoin('users as director', 'form_project.dir_approve_by', 'director.id')
            ->select(
                'form_project.*',
                'users.name as requestor',
                'manager.name as manager_name',
                'it.name as it_name',
                'it_mgr.name as it_mgr_name',
                'director.name as director_name'
            )
            ->orderBy('dir_approval_date', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }

    public function execution()
    {
        return view('website.pages.project.execution');
    }

    public function execution_ajax(Request $request)
    {
        $data = Project::whereIn('final_status', ['Director Approve', 'On Progress'])
            ->join('users', 'form_project.created_by', 'users.id')
            ->leftJoin('users as manager', 'form_project.manager_approve_by', 'manager.id')
            ->leftJoin('users as it', 'form_project.it_approve_by', 'it.id')
            ->leftJoin('users as it_mgr', 'form_project.it_mgr_approve_by', 'it_mgr.id')
            ->leftJoin('users as on_progress', 'form_project.on_progress_by', 'on_progress.id')
            ->leftJoin('users as finish', 'form_project.finish_by', 'finish.id')
            ->select(
                'form_project.*',
                'users.name as requestor',
                'manager.name as manager_name',
                'it.name as it_name',
                'it_mgr.name as it_mgr_name',
                'on_progress.name as on_progress_name',
                'finish.name as finish_name'
            )
            ->orderBy('created_at', 'ASC');

        return DataTables::eloquent($data)->make(true);
    }

    public function execution_approve(Request $request)
    {
        $project = Project::findOrFail($request->id);
        $user = $project->createdBy;

        if ($request->type == 'approve') {
            $project->is_finish = 1;
            $project->is_confirm = 0;
            $project->finish_by = Auth::user()->id;
            $project->final_status = 'Finished';
            $project->finish_note = $request->finish_note;
            $project->finish_date = Carbon::now();
            $return = "Berhasil Diselesaikan";
        } elseif ($request->type == 'progress') {
            $project->is_on_progress = 1;
            $project->final_status = 'On Progress';
            $project->on_progress_note = $request->on_progress_note;
            $project->on_progress_by = Auth::user()->id;
            $project->on_progress_date = Carbon::now();
            $return = "Berhasil Diperbarui ke On Progress";
        } else {
            $project->is_finish = 0;
            $project->is_confirm = 0;
            $project->final_status = 'Rejected';
            $project->finish_note = $request->finish_note;
            $project->finish_by = Auth::user()->id;
            $project->finish_date = Carbon::now();
            $return = "Berhasil Ditolak";
        }

        $project->save();

        return $return;
    }

    public function finished()
    {
        return view('website.pages.project.finished');
    }

    public function finished_ajax(Request $request)
    {
        $data = Project::whereNotNull('is_finish')
            ->join('users', 'form_project.created_by', 'users.id')
            ->leftJoin('users as manager', 'form_project.manager_approve_by', 'manager.id')
            ->leftJoin('users as it', 'form_project.it_approve_by', 'it.id')
            ->leftJoin('users as it_mgr', 'form_project.it_mgr_approve_by', 'it_mgr.id')
            ->leftJoin('users as on_progress', 'form_project.on_progress_by', 'on_progress.id')
            ->leftJoin('users as finish', 'form_project.finish_by', 'finish.id')
            ->select(
                'form_project.*',
                'users.name as requestor',
                'manager.name as manager_name',
                'it.name as it_name',
                'it_mgr.name as it_mgr_name',
                'on_progress.name as on_progress_name',
                'finish.name as finish_name'
            )
            ->orderBy('created_at', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }
    public function reschedule_notifications()
    {
        return view('website.pages.project.reschedule_notifications');
    }

    public function reschedule_notifications_ajax(Request $request)
    {
        $myProjectsIds = Project::where('created_by', Auth::user()->id)->pluck('id');
        $data = Project::whereIn('reschedule_target_id', $myProjectsIds)
            ->where('target_response', 'pending')
            ->join('users', 'form_project.created_by', 'users.id')
            ->select('form_project.*', 'users.name as requestor')
            ->orderBy('created_at', 'ASC');

        return DataTables::eloquent($data)->make(true);
    }

    public function target_respond(Request $request)
    {
        $project = Project::findOrFail($request->id);
        $targetProject = Project::find($project->reschedule_target_id);

        if (!$targetProject || $targetProject->created_by != Auth::user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($request->type == 'yes') {
            $project->target_response = 'yes';
            $project->target_response_date = Carbon::now();

            $nextSlot = $this->getNextAvailableSlot($targetProject->start_date);
            if ($nextSlot) {
                $project->target_reschedule_start_date = $nextSlot['start_date'];
                $project->target_reschedule_end_date = $nextSlot['end_date'];
            }

            $project->final_status = 'created'; // Move to Manager Approval
            $return = "Anda menyetujui reschedule. Permintaan berlanjut ke persetujuan Manager.";
        } else {
            $project->target_response = 'no';
            $project->target_response_date = Carbon::now();
            $project->final_status = 'created'; // Still move to Manager Approval, but with "No" response
            $return = "Anda menolak reschedule. Permintaan tetap berlanjut melalui rantai persetujuan hingga Direktur.";
        }

        $project->save();
        return $return;
    }

    private function getNextAvailableSlot($startDate)
    {
        $currentDate = Carbon::parse($startDate);
        $countYears = 0;

        while ($countYears < 2) {
            $count = Project::whereYear('start_date', $currentDate->year)
                ->whereMonth('start_date', $currentDate->month)
                ->where('is_timeline_active', true)
                ->count();

            if ($count < 2) {
                $newStart = $currentDate->copy()->startOfMonth();
                $newEnd = $newStart->copy()->addDays(14);
                return [
                    'start_date' => $newStart,
                    'end_date' => $newEnd
                ];
            }

            $currentDate->addMonth();
            if ($currentDate->month == 1) $countYears++;
        }

        return null;
    }
}
