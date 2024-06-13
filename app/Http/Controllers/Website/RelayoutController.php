<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Relayout;
use App\Models\User;
use App\Models\Alert;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

class RelayoutController extends Controller
{
    public function create()
    {
        $auth = User::where('id', Auth::user()->id)
            ->whereNull('nohp')
            ->count();

        $data = Relayout::where('created_by', Auth::user()->id)
                        ->where(function($query) {
                                $query->where('final_status', 'LIKE', '%Reject%')
                                    ->orWhere('final_status', 'Finished');
                        })
                        ->where('is_confirm', 0)
                        ->count();

        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else if ($data > 0) {
            return redirect()->route('website.relayout.list')->with('info', 'Please confirm!');
        } else {
            return view('website.pages.relayout.create');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_reg' => 'unique',
            'budget_type' => 'required',
            'request_type' => 'required',
            'project_name' => 'required',
            'date_finish_plan' => 'required',
            'location' => 'required',
            'relayout_type' => 'required',
            'description' => 'required',
        ]);

        $year = date('y');
        $month = date('m');
        $lastForm = DB::table('form_relayout')
            ->select('no_reg')
            ->orderBy('no_reg', 'desc')
            ->first();
        $lastNumber = ($lastForm) ? substr($lastForm->no_reg, -3) : '000';

        $lastMonth = ($lastForm) ? substr($lastForm->no_reg, 6, 2) : '00';
        if ($lastMonth !== $month) {
            $lastNumber = '000';
        }
        $newNumber = str_pad((intval($lastNumber) + 1), strlen($lastNumber), '0', STR_PAD_LEFT);
        $no_reg = 'REL/' . $year . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

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
        } elseif (Auth::user()->can('approve_mgr') || Auth::user()->can('approve_gm') || Auth::user()->can('approve_vp') || Auth::user()->can('approve_pres')) {
            $finalStatus = 'Manager Approve';
            $isManagerApprove = 1;
            $managerApprovalDate = Carbon::now();
        } elseif (Auth::user()->hasDepartment('ITD')) {
            $finalStatus = 'IT Approve';
            $isItApprove = 1;
            $itApprovalDate = Carbon::now();
        } else {
            $finalStatus = 'created';
            $isManagerApprove = null;
            $managerApprovalDate = null;
        }

        try {
            if ($request->hasFile('lampiran')) {
                $lampiranExtension = $request->lampiran->getClientOriginalExtension();
                $lampiranFileName = 'REL_' . $year . $month . '_' . str_pad($newNumber, 3, '0', STR_PAD_LEFT) . '.' . $lampiranExtension;
                $lampiramPath = $request->lampiran->storeAs('lampiran', $lampiranFileName, 'public');
            }

            $form_relayout = Relayout::create([
                'no_reg' => $no_reg,
                'budget_type' => $request->budget_type,
                'request_type' => $request->request_type,
                'project_name' => $request->project_name,
                'date_finish_plan' => $request->date_finish_plan,
                'location' => $request->location,
                'relayout_type' => $request->relayout_type === 'Other' ? $request->other_relayout_type : $request->relayout_type,
                'description' => $request->description,
                'purpose' => $request->purpose,
                'lampiran' => $lampiranFileName,
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
            $form_relayout->save();

            return redirect()->route('website.relayout.list')->with('success', 'Create Successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function edit($id)
    {
        $relayout = Relayout::findOrFail($id);

        return view('website.pages.relayout.edit', compact('relayout'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'budget_type' => 'required',
            'request_type' => 'required',
            'npk' => 'required|min:6',
            'fullname' => 'required',
            'department' => 'required',
            'phone' => 'required',
            'purpose' => 'required',
            'ad_name' => 'required',
        ]);

        $form_relayout = Relayout::findOrFail($id);

        if (Auth::user()->can('can_approve_mgr')) {
            $finalStatus = 'Manager Approve';
            $isManagerApprove = 1;
            $managerApprovalDate = Carbon::now();
        } elseif (Auth::user()->can('can_approve_executives')) {
            $finalStatus = 'Manager Approve';
            $isManagerApprove = 1;
            $managerApprovalDate = Carbon::now();
        } else {
            $finalStatus = 'created';
            $isManagerApprove = null;
            $managerApprovalDate = null;
        }

        try {
            $form_relayout->update([
                'budget_type' => $request->budget_type,
                'request_type' => $request->request_type,
                'npk' => $request->npk,
                'fullname' => $request->fullname,
                'department' => $request->department,
                'phone' => $request->phone,
                'company' => $request->company,
                'expired_date' => $request->expired_date,
                'purpose' => $request->purpose,
                'ad_name' => $request->ad_name,
                'created_by' => Auth::user()->id,
                'created_dept' => Auth::user()->departments->pluck('id')->first(),
                'final_status' => $finalStatus,
                'is_manager_approve' => $isManagerApprove,
                'manager_approval_date' => $managerApprovalDate,
            ]);

            return redirect()->route('website.relayout.list')->with('success', 'Success Edit Form');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function list()
    {
        return view('website.pages.relayout.list');
    }

    public function list_ajax(Request $request)
    {
        $data = Relayout::where('created_by', Auth::user()->id)
                        ->join('public.users', 'form_relayout.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_relayout.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_relayout.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_relayout.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_relayout.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_relayout.finish_by', 'finish.id')
                        ->select('form_relayout.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }

    public function approve_form(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $relayout = Relayout::findOrFail($id);

        if ($type == 'confirm') {
            $relayout->is_confirm = 1;
        } else {
            $relayout->is_confirm = 0;
        }
        $relayout->save();

        return "Confirm Successfully";
    }

    public function delete_form(Request $request)
    {
        $id = $request->id;

        $relayout = Relayout::findOrFail($id);
        $relayout->delete();

        return "Delete Successfully";
    }

    // MGR //

    public function manager_approval()
    {
        return view('website.pages.relayout.manager_approval');
    }

    public function manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Relayout::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->where('final_status', 'created')
            ->join('public.users', 'form_relayout.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_relayout.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_relayout.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_relayout.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_relayout.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_relayout.finish_by', 'finish.id')
                        ->select('form_relayout.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
            ->orderBy('created_at', 'ASC');

        return DataTables::eloquent($data)->make(true);
    }

    public function manager_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $relayout = Relayout::findOrFail($id);

        if ($type == 'approve') {
            $relayout->is_manager_approve = 1;
            $relayout->final_status = 'Manager Approve';
            $relayout->manager_note = $request->manager_note;
            $relayout->manager_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $relayout->is_manager_approve = 0;
            $relayout->final_status = 'Manager Reject';
            $relayout->manager_note = $request->manager_note;
            $relayout->manager_approve_by = Auth::user()->id;
            $relayout->is_finish = 0;
            $relayout->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $relayout->manager_approval_date = Carbon::now();
        $relayout->save();
        return $return;
    }

    public function manager_approved()
    {
        return view('website.pages.relayout.manager_approved');
    }

    public function manager_approved_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Relayout::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->whereNotNull('is_manager_approve')
            ->join('public.users', 'form_relayout.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_relayout.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_relayout.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_relayout.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_relayout.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_relayout.finish_by', 'finish.id')
                        ->select('form_relayout.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
            ->orderBy('manager_approval_date', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }

    /// ITD APPROVE ///

    public function it_approval()
    {
        return view('website.pages.relayout.it_approval');
    }

    public function it_approval_ajax(Request $request)
    {
        $data = Relayout::where('final_status', 'Manager Approve')
                        ->join('public.users', 'form_relayout.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_relayout.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_relayout.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_relayout.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_relayout.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_relayout.finish_by', 'finish.id')
                        ->select('form_relayout.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'ASC');

        return DataTables::eloquent($data)->make(true);
    }

    public function it_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $relayout = Relayout::findOrFail($id);
        
        if ($type == 'approve') {
            $relayout->is_it_approve = 1;
            $relayout->final_status = 'IT Approve';
            $relayout->it_note = $request->it_note;
            $relayout->it_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $relayout->is_it_approve = 0;
            $relayout->is_confirm = 0;
            $relayout->final_status = 'IT Reject';
            $relayout->it_note = $request->it_note;
            $relayout->is_finish = 0;
            $relayout->it_approve_by = Auth::user()->id;
            $return = "Reject Successfully";
        }
        $relayout->it_approval_date = Carbon::now();
        $relayout->save();
        
        if ($request->notifikasi == 'Ya') {
            $isi = "FORM RELAYOUT\n";
            $isi .= "*TUNGGU APPROVE IT MANAGER*";
            $isi .= "\n\nType : " . $relayout->request_type;
            $isi .= "\n\nREQUESTOR";
            $isi .= "\nNama : *" . $relayout->createdBy->name . "*";
            $isi .= "\nDepartment : *" . $relayout->createdBy->departments->pluck('code')->implode(', ') . "*";
            $isi .= "\nPurpose : " . $relayout->purpose;
            $isi .= "\n\nNote : Dear Pak Ferry, Mohon untuk dicek tunggu approve pada FIOLA. Terimakasih";

            $isi .= "\n\nApproved ITD by : " . Auth::user()->name;

            $nomors = Alert::where('role', 'IT Manager')->get();

            foreach ($nomors as $nomor) {
                $token = "v2n49drKeWNoRDN4jgqcdsR8a6bcochcmk6YphL6vLcCpRZdV1";
                $message = sprintf("----------FIOLA----------%c$isi%c------------------------- ", 10, 10);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://app.ruangwa.id/api/send_message',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => 'token=' . $token . '&number=' . $nomor->nohp . '&message=' . $message,
                ));

                $response = curl_exec($curl);
                curl_close($curl);
            }
        }
        return $return;
    }

    public function it_approved()
    {
        return view('website.pages.relayout.it_approved');
    }

    public function it_approved_ajax(Request $request)
    {
        $data = Relayout::whereNotNull('is_it_approve')
                        ->join('public.users', 'form_relayout.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_relayout.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_relayout.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_relayout.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_relayout.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_relayout.finish_by', 'finish.id')
                        ->select('form_relayout.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('manager_approval_date', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }

    /// IT MGR ///
    public function it_mgr_approval()
    {
        return view('website.pages.relayout.it_mgr_approval');
    }

    public function it_mgr_approval_ajax(Request $request)
    {
        $data = Relayout::where('final_status', 'IT Approve')
                        ->join('public.users', 'form_relayout.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_relayout.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_relayout.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_relayout.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_relayout.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_relayout.finish_by', 'finish.id')
                        ->select('form_relayout.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'ASC');

        return DataTables::eloquent($data)->make(true);
    }

    public function it_mgr_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $relayout = Relayout::findOrFail($id);

        if ($type == 'approve') {
            $relayout->is_it_mgr_approve = 1;
            $relayout->final_status = 'IT MGR Approve';
            $relayout->it_mgr_note = $request->it_mgr_note;
            $relayout->it_mgr_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $relayout->is_it_mgr_approve = 0;
            $relayout->final_status = 'IT MGR Reject';
            $relayout->it_mgr_note = $request->it_mgr_note;
            $relayout->it_mgr_approve_by = Auth::user()->id;
            $relayout->is_finish = 0;
            $relayout->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $relayout->it_mgr_approval_date = Carbon::now();
        $relayout->save();
        return $return;
    }

    public function it_mgr_approved()
    {
        return view('website.pages.relayout.it_mgr_approved');
    }

    public function it_mgr_approved_ajax(Request $request)
    {
        $data = Relayout::whereNotNull('is_it_mgr_approve')
                        ->join('public.users', 'form_relayout.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_relayout.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_relayout.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_relayout.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_relayout.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_relayout.finish_by', 'finish.id')
                        ->select('form_relayout.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }

    /// EXECUTION ///
    public function execution()
    {
        return view('website.pages.relayout.execution');
    }

    public function execution_ajax(Request $request)
    {
        $data = Relayout::whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                        ->join('public.users', 'form_relayout.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_relayout.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_relayout.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_relayout.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_relayout.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_relayout.finish_by', 'finish.id')
                        ->select('form_relayout.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'ASC');

        return DataTables::eloquent($data)->make(true);
    }

    public function execution_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $relayout = Relayout::findOrFail($id);

        $user = $relayout->createdBy;

        if ($type == 'approve') {
            $relayout->is_finish = 1;
            $relayout->is_confirm = 0;
            $relayout->finish_by = Auth::user()->id;
            $relayout->final_status = 'Finished';
            $relayout->finish_note = $request->finish_note;
            $relayout->finish_date = Carbon::now();
            $return = "Approve Successfully";
        } else if ($type == 'progress') {
            $relayout->is_on_progress = 1;
            $relayout->final_status = 'On Progress';
            $relayout->on_progress_note = $request->on_progress_note;
            $relayout->on_progress_by = Auth::user()->id;
            $relayout->on_progress_date = Carbon::now();
            $return = "Progress Successfully";
        } else {
            $relayout->is_finish = 0;
            $relayout->is_confirm = 0;
            $relayout->final_status = 'Rejected';
            $relayout->finish_note = $request->finish_note;
            $relayout->finish_by = Auth::user()->id;
            $relayout->finish_date = Carbon::now();
            $return = "Reject Successfully";
        }
        $relayout->save();

        if ($request->notifikasi == 'Ya') {
            $isi = "FORM RELAYOUT\n\n";

            $isi .= "Budget Type : " . $relayout->budget_type;
            $isi .= "\nRequest Type : " . $relayout->request_type;
            
            $isi .= "\n\nProject Name : *" . $relayout->project_name . "*";
            $isi .= "\nLocation : " . $relayout->location;
            $isi .= "\nRelayout Type : " . $relayout->relayout_type;
            $isi .= "\nDescription : " . $relayout->description;
            $isi .= "\nPurpose : " . $relayout->purpose;
            
            $isi .= "\n\nStatus : *Finished*";
            
            $isi .= "\n\nManager Note : " . $relayout->manager_note;
            $isi .= "\nITD Note : " . $relayout->it_note;
            $isi .= "\nITD Manager Note : " . $relayout->it_mgr_note;
            $isi .= "\n\nFinish Note : " . $request->finish_note;
            
            $isi .= "\n\nExecution by : " . Auth::user()->name;
            
            $nomor = $user->nohp;
            
            $token = "v2n49drKeWNoRDN4jgqcdsR8a6bcochcmk6YphL6vLcCpRZdV1";
            $message = sprintf("----------FIOLA----------%c$isi%c------------------------- ", 10, 10);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://app.ruangwa.id/api/send_message',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'token=' . $token . '&number=' . $nomor . '&message=' . $message,
            ));
            $response = curl_exec($curl);
            curl_close($curl);
        }

        return $return;
    }

    public function finished()
    {
        return view('website.pages.relayout.finished');
    }

    public function finished_ajax(Request $request)
    {
        $data = Relayout::whereNotNull('is_finish')
                        ->join('public.users', 'form_relayout.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_relayout.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_relayout.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_relayout.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_relayout.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_relayout.finish_by', 'finish.id')
                        ->select('form_relayout.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }
}
