<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\IncidentReport;
use App\Models\Department;
use App\Models\User;
use App\Models\Alert;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

class IncidentReportController extends Controller
{
    public function create()
    {
        $auth = User::where('id', Auth::user()->id)
            ->whereNull('nohp')
            ->count();
            
        $departments = Department::orderBy('name')->get();

        $data = IncidentReport::where('created_by', Auth::user()->id)
                        ->where(function($query) {
                                $query->where('final_status', 'LIKE', '%Reject%')
                                    ->orWhere('final_status', 'Finished');
                        })
                        ->where('is_confirm', 0)
                        ->count();

        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else if ($data > 0) {
            return redirect()->route('website.incident_report.list')->with('info', 'Please confirm!');
        } else {
            return view('website.pages.incident_report.create', compact('departments'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_reg' => 'unique',
            'kategori' => 'required',
            'penyebab' => 'required',
            'aktual_keparahan' => 'required',
            'penemu' => 'required',
        ]);

        $year = date('y');
        $month = date('m');
        $lastForm = DB::table('form_incident_report')
            ->select('no_reg')
            ->orderBy('no_reg', 'desc')
            ->first();
        $lastNumber = ($lastForm) ? substr($lastForm->no_reg, -3) : '000';

        $lastMonth = ($lastForm) ? substr($lastForm->no_reg, 6, 2) : '00';
        if ($lastMonth !== $month) {
            $lastNumber = '000';
        }
        $newNumber = str_pad((intval($lastNumber) + 1), strlen($lastNumber), '0', STR_PAD_LEFT);
        $no_reg = 'INC/' . $year . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

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
        } elseif (Auth::user()->can('approve_mgr') || Auth::user()->can('approve_gm') || Auth::user()->can('approve_dir')  || Auth::user()->can('approve_vp') || Auth::user()->can('approve_pres')) {
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
            $form_incident_report = IncidentReport::create([
                'no_reg' => $no_reg,
                'kategori' => $request->kategori,
                'penyebab' => $request->penyebab,
                'aktual_keparahan' => $request->aktual_keparahan,
                'penemu' => $request->penemu,
                'department' => $request->department,
                'tanggal_penemuan' => $request->tanggal_penemuan,
                'device' => $request->device,
                'dampak_awal' => $request->dampak_awal,
                'kronologi' => $request->kronologi,
                'dampak_luas' => $request->dampak_luas,
                'root_cause' => $request->root_cause,
                'potensi_kelemahan' => $request->potensi_kelemahan,
                'staff_corrective_action' => $request->staff_corrective_action,
                'tanggal_mulai_corrective_action' => $request->tanggal_mulai_corrective_action,
                'tanggal_berakhir_corrective_action' => $request->tanggal_berakhir_corrective_action,
                'corrective_action' => $request->corrective_action,
                'dampak_lanjutan_corrective_action' => $request->dampak_lanjutan_corrective_action,
                'kondisi_bisnis_corrective_action' => $request->kondisi_bisnis_corrective_action,
                'staff_preventive_action' => $request->staff_preventive_action,
                'tanggal_mulai_preventive_action' => $request->tanggal_mulai_preventive_action,
                'tanggal_berakhir_preventive_action' => $request->tanggal_berakhir_preventive_action,
                'preventive_action' => $request->preventive_action,
                'dampak_lanjutan_preventive_action' => $request->dampak_lanjutan_preventive_action,
                'kondisi_bisnis_preventive_action' => $request->kondisi_bisnis_preventive_action,

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
            $form_incident_report->save();

            return redirect()->route('website.incident_report.list')->with('success', 'Create Successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function edit($id)
    {
        $incident_report = IncidentReport::findOrFail($id);

        return view('website.pages.incident_report.edit', compact('incident_report'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'budget_type' => 'required',
            'form_type' => 'required',
            'npk' => 'required|min:6',
            'fullname' => 'required',
            'department' => 'required',
            'phone' => 'required',
            'purpose' => 'required',
            'ad_name' => 'required',
        ]);

        $form_incident_report = IncidentReport::findOrFail($id);

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
            $form_incident_report->update([
                'budget_type' => $request->budget_type,
                'form_type' => $request->form_type,
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

            return redirect()->route('website.incident_report.list')->with('success', 'Success Edit Form');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function list()
    {
        return view('website.pages.incident_report.list');
    }

    public function list_ajax(Request $request)
    {
        $data = IncidentReport::where('created_by', Auth::user()->id)
                        ->join('public.users', 'form_incident_report.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_incident_report.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_incident_report.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_incident_report.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_incident_report.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_incident_report.finish_by', 'finish.id')
                        ->select('form_incident_report.*', 'users.name as requestor',
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

        $incident_report = IncidentReport::findOrFail($id);

        if ($type == 'confirm') {
            $incident_report->is_confirm = 1;
        } else {
            $incident_report->is_confirm = 0;
        }
        $incident_report->save();

        return "Confirm Successfully";
    }

    public function delete_form(Request $request)
    {
        $id = $request->id;

        $incident_report = IncidentReport::findOrFail($id);
        $incident_report->delete();

        return "Delete Successfully";
    }

    // MGR //

    public function manager_approval()
    {
        return view('website.pages.incident_report.manager_approval');
    }

    public function manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = IncidentReport::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->where('final_status', 'created')
            ->join('public.users', 'form_incident_report.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_incident_report.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_incident_report.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_incident_report.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_incident_report.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_incident_report.finish_by', 'finish.id')
                        ->select('form_incident_report.*', 'users.name as requestor',
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

        $incident_report = IncidentReport::findOrFail($id);

        if ($type == 'approve') {
            $incident_report->is_manager_approve = 1;
            $incident_report->final_status = 'Manager Approve';
            $incident_report->manager_note = $request->manager_note;
            $incident_report->manager_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $incident_report->is_manager_approve = 0;
            $incident_report->final_status = 'Manager Reject';
            $incident_report->manager_note = $request->manager_note;
            $incident_report->manager_approve_by = Auth::user()->id;
            $incident_report->is_finish = 0;
            $incident_report->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $incident_report->manager_approval_date = Carbon::now();
        $incident_report->save();
        return $return;
    }

    public function manager_approved()
    {
        return view('website.pages.incident_report.manager_approved');
    }

    public function manager_approved_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = IncidentReport::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->whereNotNull('is_manager_approve')
            ->join('public.users', 'form_incident_report.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_incident_report.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_incident_report.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_incident_report.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_incident_report.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_incident_report.finish_by', 'finish.id')
                        ->select('form_incident_report.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
            ->orderBy('manager_approval_date', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }

    // MGR //

    public function gm_approval()
    {
        return view('website.pages.incident_report.gm_approval');
    }

    public function gm_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = IncidentReport::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->where('final_status', 'created')
            ->join('public.users', 'form_incident_report.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_incident_report.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_incident_report.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_incident_report.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_incident_report.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_incident_report.finish_by', 'finish.id')
                        ->select('form_incident_report.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
            ->orderBy('created_at', 'ASC');

        return DataTables::eloquent($data)->make(true);
    }

    public function gm_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $incident_report = IncidentReport::findOrFail($id);

        if ($type == 'approve') {
            $incident_report->is_gm_approve = 1;
            $incident_report->final_status = 'Manager Approve';
            $incident_report->gm_note = $request->gm_note;
            $incident_report->gm_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $incident_report->is_gm_approve = 0;
            $incident_report->final_status = 'Manager Reject';
            $incident_report->gm_note = $request->gm_note;
            $incident_report->gm_approve_by = Auth::user()->id;
            $incident_report->is_finish = 0;
            $incident_report->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $incident_report->gm_approval_date = Carbon::now();
        $incident_report->save();
        return $return;
    }

    public function gm_approved()
    {
        return view('website.pages.incident_report.gm_approved');
    }

    public function gm_approved_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = IncidentReport::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->whereNotNull('is_manager_approve')
            ->join('public.users', 'form_incident_report.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_incident_report.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_incident_report.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_incident_report.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_incident_report.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_incident_report.finish_by', 'finish.id')
                        ->select('form_incident_report.*', 'users.name as requestor',
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
        return view('website.pages.incident_report.it_approval');
    }

    public function it_approval_ajax(Request $request)
    {
        $data = IncidentReport::where('final_status', 'Manager Approve')
                        ->join('public.users', 'form_incident_report.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_incident_report.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_incident_report.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_incident_report.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_incident_report.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_incident_report.finish_by', 'finish.id')
                        ->select('form_incident_report.*', 'users.name as requestor',
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

        $incident_report = IncidentReport::findOrFail($id);
        
        if ($type == 'approve') {
            $incident_report->is_it_approve = 1;
            $incident_report->final_status = 'IT Approve';
            $incident_report->it_note = $request->it_note;
            $incident_report->it_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $incident_report->is_it_approve = 0;
            $incident_report->is_confirm = 0;
            $incident_report->final_status = 'IT Reject';
            $incident_report->it_note = $request->it_note;
            $incident_report->is_finish = 0;
            $incident_report->it_approve_by = Auth::user()->id;
            $return = "Reject Successfully";
        }
        $incident_report->it_approval_date = Carbon::now();
        $incident_report->save();
        
        if ($request->notifikasi == 'Ya') {
            $isi = "FORM NETWORK\n";
            $isi .= "*TUNGGU APPROVE IT MANAGER*";
            $isi .= "\n\nType : " . $incident_report->form_type;
            $isi .= "\n\nREQUESTOR";
            $isi .= "\nNama : *" . $incident_report->createdBy->name . "*";
            $isi .= "\nDepartment : *" . $incident_report->createdBy->departments->pluck('code')->implode(', ') . "*";
            $isi .= "\nPurpose : " . $incident_report->purpose;
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
        return view('website.pages.incident_report.it_approved');
    }

    public function it_approved_ajax(Request $request)
    {
        $data = IncidentReport::whereNotNull('is_it_approve')
                        ->join('public.users', 'form_incident_report.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_incident_report.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_incident_report.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_incident_report.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_incident_report.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_incident_report.finish_by', 'finish.id')
                        ->select('form_incident_report.*', 'users.name as requestor',
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
        return view('website.pages.incident_report.it_mgr_approval');
    }

    public function it_mgr_approval_ajax(Request $request)
    {
        $data = IncidentReport::where('final_status', 'IT Approve')
                        ->join('public.users', 'form_incident_report.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_incident_report.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_incident_report.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_incident_report.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_incident_report.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_incident_report.finish_by', 'finish.id')
                        ->select('form_incident_report.*', 'users.name as requestor',
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

        $incident_report = IncidentReport::findOrFail($id);

        if ($type == 'approve') {
            $incident_report->is_it_mgr_approve = 1;
            $incident_report->final_status = 'IT MGR Approve';
            $incident_report->it_mgr_note = $request->it_mgr_note;
            $incident_report->it_mgr_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $incident_report->is_it_mgr_approve = 0;
            $incident_report->final_status = 'IT MGR Reject';
            $incident_report->it_mgr_note = $request->it_mgr_note;
            $incident_report->it_mgr_approve_by = Auth::user()->id;
            $incident_report->is_finish = 0;
            $incident_report->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $incident_report->it_mgr_approval_date = Carbon::now();
        $incident_report->save();
        return $return;
    }

    public function it_mgr_approved()
    {
        return view('website.pages.incident_report.it_mgr_approved');
    }

    public function it_mgr_approved_ajax(Request $request)
    {
        $data = IncidentReport::whereNotNull('is_it_mgr_approve')
                        ->join('public.users', 'form_incident_report.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_incident_report.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_incident_report.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_incident_report.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_incident_report.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_incident_report.finish_by', 'finish.id')
                        ->select('form_incident_report.*', 'users.name as requestor',
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
        return view('website.pages.incident_report.execution');
    }

    public function execution_ajax(Request $request)
    {
        $data = IncidentReport::whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                        ->join('public.users', 'form_incident_report.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_incident_report.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_incident_report.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_incident_report.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_incident_report.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_incident_report.finish_by', 'finish.id')
                        ->select('form_incident_report.*', 'users.name as requestor',
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

        $incident_report = IncidentReport::findOrFail($id);

        $user = $incident_report->createdBy;

        if ($type == 'approve') {
            $incident_report->is_finish = 1;
            $incident_report->is_confirm = 0;
            $incident_report->finish_by = Auth::user()->id;
            $incident_report->final_status = 'Finished';
            $incident_report->finish_note = $request->finish_note;
            $incident_report->finish_date = Carbon::now();
            $return = "Approve Successfully";
        } else if ($type == 'progress') {
            $incident_report->is_on_progress = 1;
            $incident_report->final_status = 'On Progress';
            $incident_report->on_progress_note = $request->on_progress_note;
            $incident_report->on_progress_by = Auth::user()->id;
            $incident_report->on_progress_date = Carbon::now();
            $return = "Progress Successfully";
        } else {
            $incident_report->is_finish = 0;
            $incident_report->is_confirm = 0;
            $incident_report->final_status = 'Rejected';
            $incident_report->finish_note = $request->finish_note;
            $incident_report->finish_by = Auth::user()->id;
            $incident_report->finish_date = Carbon::now();
            $return = "Reject Successfully";
        }
        $incident_report->save();

        if ($request->notifikasi == 'Ya') {
            $isi = "FORM NETWORK\n\n";
            
            $isi .= "Project Name : *" . $incident_report->project_name . "*";
            $isi .= "\nDate Access : " . $incident_report->date_access_start . " - " . $incident_report->date_access_end;
            $isi .= "\nRack that is accessed : " . $incident_report->rack;
            $isi .= "\nDevice that is accessed : " . $incident_report->device;
            $isi .= "\nNeed Down Time : " . $incident_report->down_time;
            $isi .= "\nPurpose : " . $incident_report->purpose;
            
            $isi .= "\n\nStatus : *Finished*";
            
            $isi .= "\n\nManager Note : " . $incident_report->manager_note;
            $isi .= "\nITD Note : " . $incident_report->it_note;
            $isi .= "\nITD Manager Note : " . $incident_report->it_mgr_note;
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
        return view('website.pages.incident_report.finished');
    }

    public function finished_ajax(Request $request)
    {
        $data = IncidentReport::whereNotNull('is_finish')
                        ->join('public.users', 'form_incident_report.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_incident_report.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_incident_report.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_incident_report.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_incident_report.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_incident_report.finish_by', 'finish.id')
                        ->select('form_incident_report.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }
}
