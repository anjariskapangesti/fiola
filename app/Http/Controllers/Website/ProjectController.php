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
            return redirect()->route('website.project.list')->with('info', 'Please confirm!');
        } else {
            return view('website.pages.project.create', compact(['devices']));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'npk_pic' => 'required',
            'fullname_pic' => 'required',
            'department_pic' => 'required',
            'phone_pic' => 'required',
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

        if (Auth::user()->hasDepartment('ITD') && Auth::user()->can('approve_mgr')) {
            $finalStatus = 'IT MGR Approve';
            $isItManagerApprove = 1;
            $itManagerApprovalDate = Carbon::now();
        } elseif (
            Auth::user()->can('approve_mgr') ||
            Auth::user()->can('approve_gm') ||
            Auth::user()->can('approve_dir') ||
            Auth::user()->can('approve_vp') ||
            Auth::user()->can('approve_pres')
        ) {
            $finalStatus = 'Manager Approve';
            $isManagerApprove = 1;
            $managerApprovalDate = Carbon::now();
        } elseif (Auth::user()->hasDepartment('ITD')) {
            $finalStatus = 'IT Approve';
            $isItApprove = 1;
            $itApprovalDate = Carbon::now();
        } else {
            $finalStatus = 'created';
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
                'npk' => $request->npk_pic,
                'fullname' => $request->fullname_pic,
                'department' => $request->department_pic,
                'phone' => $request->phone_pic,
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
            ]);

            return redirect()->route('website.project.list')->with('success', 'Create Successfully');
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

        return "Confirm Successfully";
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

        $data = Project::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
                $query->where('created_dept', $firstDepartmentId)
                    ->orWhere('created_dept', $lastDepartmentId);
            })
            ->where('final_status', 'created')
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
            $return = "Approve Successfully";
        } else {
            $project->is_manager_approve = 0;
            $project->final_status = 'Manager Reject';
            $project->manager_note = $request->manager_note;
            $project->manager_approve_by = Auth::user()->id;
            $project->is_finish = 0;
            $project->is_confirm = 0;
            $return = "Reject Successfully";
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
        $data = Project::where('final_status', 'Manager Approve')
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
            $project->is_confirm = 0;
            $project->final_status = 'IT Reject';
            $project->it_note = $request->it_note;
            $project->is_finish = 0;
            $project->it_approve_by = Auth::user()->id;
            $return = "Reject Successfully";
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
        $data = Project::where('final_status', 'IT Approve')
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
            $return = "Approve Successfully";
        } else {
            $project->is_it_mgr_approve = 0;
            $project->final_status = 'IT MGR Reject';
            $project->it_mgr_note = $request->it_mgr_note;
            $project->it_mgr_approve_by = Auth::user()->id;
            $project->is_finish = 0;
            $project->is_confirm = 0;
            $return = "Reject Successfully";
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

    public function execution()
    {
        return view('website.pages.project.execution');
    }

    public function execution_ajax(Request $request)
    {
        $data = Project::whereIn('final_status', ['IT MGR Approve', 'On Progress'])
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
            $return = "Approve Successfully";
        } elseif ($request->type == 'progress') {
            $project->is_on_progress = 1;
            $project->final_status = 'On Progress';
            $project->on_progress_note = $request->on_progress_note;
            $project->on_progress_by = Auth::user()->id;
            $project->on_progress_date = Carbon::now();
            $return = "Progress Successfully";
        } else {
            $project->is_finish = 0;
            $project->is_confirm = 0;
            $project->final_status = 'Rejected';
            $project->finish_note = $request->finish_note;
            $project->finish_by = Auth::user()->id;
            $project->finish_date = Carbon::now();
            $return = "Reject Successfully";
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
}