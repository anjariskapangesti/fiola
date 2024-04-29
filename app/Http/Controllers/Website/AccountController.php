<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Account;
use App\Models\Department;
use App\Models\User;
use App\Models\Alert;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\TaskReminder;

class AccountController extends Controller
{
    public function create()
    {
        $departments = Department::orderBy('name')->get();

        $auth = User::where('id', Auth::user()->id)
            ->whereNull('nohp')
            ->count();

        $data = Account::where('created_by', Auth::user()->id)->where('final_status', 'Finished')->where('is_confirm', 0)->count();

        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else if ($data > 0) {
            return redirect()->route('website.account.list')->with('info', 'Please confirm!');
        } else {
            return view('website.pages.account.create', compact('departments'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_reg' => 'unique',
            'budget_type' => 'required',
            'form_type' => 'required',
            'npk' => 'nullable|min:6|required_if:form_type,Registration|required_if:form_type,Change',
            'fullname' => 'required_if:form_type,Registration|required_if:form_type,Change',
            'department' => 'required_if:form_type,Registration|required_if:form_type,Change',
            'phone' => 'required_if:form_type,Registration|required_if:form_type,Change',
            'purpose' => 'required_if:form_type,Registration',
            'ad_name' => 'required_if:form_type,Registration',
        ]);

        $year = date('y');
        $month = date('m');
        $lastForm = DB::table('form_account')
            ->select('no_reg')
            ->orderBy('no_reg', 'desc')
            ->first();
        $lastNumber = ($lastForm) ? substr($lastForm->no_reg, -3) : '000';

        $lastMonth = ($lastForm) ? substr($lastForm->no_reg, 6, 2) : '00';
        if ($lastMonth !== $month) {
            $lastNumber = '000';
        }
        $newNumber = str_pad((intval($lastNumber) + 1), strlen($lastNumber), '0', STR_PAD_LEFT);
        $no_reg = 'ACC/' . $year . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        if ($request->is_email == false) {
            $request->is_email = 0;
        } else {
            $request->is_email = 1;
        }

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
            $form_account = Account::create([
                'no_reg' => $no_reg,
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
                'is_email' => $request->is_email,
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
            $form_account->save();

            return redirect()->route('website.account.list')->with('success', 'Create Successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function edit($id)
    {
        $account = Account::findOrFail($id);
        $departments = Department::orderBy('name')->get();

        return view('website.pages.account.edit', compact('account', 'departments'));
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

        $form_account = Account::findOrFail($id);

        if ($request->is_email == false) {
            $request->is_email = 0;
        } else {
            $request->is_email = 1;
        }

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
            $form_account->update([
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
                'is_email' => $request->is_email,
                'created_by' => Auth::user()->id,
                'created_dept' => Auth::user()->departments->pluck('id')->first(),
                'final_status' => $finalStatus,
                'is_manager_approve' => $isManagerApprove,
                'manager_approval_date' => $managerApprovalDate,
            ]);

            $depts = Department::all();
            return redirect()->route('website.account.list')->with('success', 'Success Edit Form');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function list()
    {
        return view('website.pages.account.list');
    }

    public function list_ajax(Request $request)
    {
        $data = Account::where('created_by', Auth::user()->id)
                        ->join('public.users', 'form_account.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_account.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_account.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_account.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_account.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_account.finish_by', 'finish.id')
                        ->select('form_account.*', 'users.name as requestor',
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
        $account = Account::findOrFail($id);
        if ($type == 'ok') {
            $account->is_confirm = 1;
        } else {
            $account->is_confirm = 0;
        }
        $account->save();
        return "Confirm Successfully";
    }

    public function delete_form(Request $request)
    {
        $id = $request->id;

        $account = Account::findOrFail($id);
        $account->delete();

        return "Delete Successfully";
    }

    // MGR //

    public function manager_approval()
    {
        return view('website.pages.account.manager_approval');
    }

    public function manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Account::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->where('final_status', 'created')
            ->join('public.users', 'form_account.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_account.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_account.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_account.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_account.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_account.finish_by', 'finish.id')
                        ->select('form_account.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
            ->orderBy('created_at', 'ASC');

        return DataTables::eloquent($data)->make(true);
    }

    public function manager_approved()
    {
        $depts = Department::all();
        return view('website.pages.account.manager_approved', compact(['depts']));
    }

    public function manager_approved_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Account::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->whereNotNull('is_manager_approve')
            ->join('public.users', 'form_account.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_account.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_account.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_account.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_account.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_account.finish_by', 'finish.id')
                        ->select('form_account.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
            ->orderBy('manager_approval_date', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }

    public function manager_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $account = Account::findOrFail($id);

        if ($type == 'approve') {
            $account->is_manager_approve = 1;
            $account->final_status = 'Manager Approve';
            $account->manager_note = $request->manager_note;
            $account->manager_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $account->is_manager_approve = 0;
            $account->final_status = 'Manager Reject';
            $account->manager_note = $request->manager_note;
            $account->manager_approve_by = Auth::user()->id;
            $account->is_finish = 0;
            $return = "Reject Successfully";
        }
        $account->manager_approval_date = Carbon::now();
        $account->save();
        return $return;
    }

    /// ITD APPROVE ///

    public function it_approval()
    {
        return view('website.pages.account.it_approval');
    }

    public function it_approval_ajax(Request $request)
    {
        $data = Account::where('final_status', 'Manager Approve')
                        ->join('public.users', 'form_account.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_account.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_account.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_account.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_account.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_account.finish_by', 'finish.id')
                        ->select('form_account.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'ASC');

        return DataTables::eloquent($data)->make(true);
    }

    public function it_approved()
    {
        return view('website.pages.account.it_approved');
    }

    public function it_approved_ajax(Request $request)
    {
        $data = Account::where('is_it_approve', '1')
                        ->join('public.users', 'form_account.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_account.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_account.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_account.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_account.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_account.finish_by', 'finish.id')
                        ->select('form_account.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('manager_approval_date', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }

    public function it_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $account = Account::findOrFail($id);
        
        if ($type == 'approve') {
            $account->is_it_approve = 1;
            $account->final_status = 'IT Approve';
            $account->it_note = $request->it_note;
            $account->it_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $account->is_it_approve = 0;
            $account->final_status = 'IT Reject';
            $account->it_note = $request->it_note;
            $account->is_finish = 0;
            $account->it_approve_by = Auth::user()->id;
            $return = "Reject Successfully";
        }
        $account->it_approval_date = Carbon::now();
        $account->save();
        
        // if ($request->notifikasi == 'Ya') {
        //     $isi = "FORM ACCOUNT\n";
        //     $isi .= "*TUNGGU APPROVE IT MANAGER*";
        //     $isi .= "\n\nType : " . $account->form_type;
        //     $isi .= "\n\nREQUESTOR";
        //     $isi .= "\nNama : *" . $account->createdBy->name . "*";
        //     $isi .= "\nDepartment : *" . $account->department . "*";
        //     $isi .= "\nPurpose : " . $account->purpose;
        //     $isi .= "\n\nNote : Dear Pak Ferry, Mohon untuk dicek tunggu approve pada FIOLA. Terimakasih";

        //     $isi .= "\n\nApproved ITD by : " . Auth::user()->name;

        //     $nomors = Alert::where('role', 'IT Manager')->get();

        //     foreach ($nomors as $nomor) {
        //         $token = "v2n49drKeWNoRDN4jgqcdsR8a6bcochcmk6YphL6vLcCpRZdV1";
        //         $message = sprintf("----------FIOLA----------%c$isi%c------------------------- ", 10, 10);
        //         $curl = curl_init();
        //         curl_setopt_array($curl, array(
        //             CURLOPT_URL => 'https://app.ruangwa.id/api/send_message',
        //             CURLOPT_RETURNTRANSFER => true,
        //             CURLOPT_ENCODING => '',
        //             CURLOPT_MAXREDIRS => 10,
        //             CURLOPT_TIMEOUT => 0,
        //             CURLOPT_FOLLOWLOCATION => true,
        //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //             CURLOPT_CUSTOMREQUEST => 'POST',
        //             CURLOPT_POSTFIELDS => 'token=' . $token . '&number=' . $nomor->nohp . '&message=' . $message,
        //         ));

        //         $response = curl_exec($curl);
        //         curl_close($curl);
        //     }
        // }
        return $return;
    }

    /// IT MGR ///
    public function it_mgr_approval()
    {
        return view('website.pages.account.it_mgr_approval');
    }

    public function it_mgr_approval_ajax(Request $request)
    {
        $data = Account::where('final_status', 'IT Approve')
                        ->join('public.users', 'form_account.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_account.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_account.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_account.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_account.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_account.finish_by', 'finish.id')
                        ->select('form_account.*', 'users.name as requestor',
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
        $account = Account::findOrFail($id);
        if ($type == 'approve') {
            $account->is_it_mgr_approve = 1;
            $account->final_status = 'IT MGR Approve';
            $account->it_mgr_note = $request->it_mgr_note;
            $account->it_mgr_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $account->is_it_mgr_approve = 0;
            $account->final_status = 'IT MGR Reject';
            $account->it_mgr_note = $request->it_mgr_note;
            $account->it_mgr_approve_by = Auth::user()->id;
            $account->is_finish = 0;
            $return = "Reject Successfully";
        }
        $account->it_mgr_approval_date = Carbon::now();
        $account->save();
        return $return;
    }

    public function show_data_it_mgr_approval()
    {
        $depts = Department::all();
        return view('website.pages.account.show_data_it_mgr_approval', compact(['depts']));
    }

    public function show_data_it_mgr_approval_ajax(Request $request)
    {
        $data = Account::where('is_it_mgr_approve', '1')
            ->join('users', 'form_account.created_by', '=', 'users.id')
            ->select('form_account.*', 'users.name as requestor');

        return DataTables::eloquent($data)->make(true);
    }

    /// EXECUTION ///
    public function execution()
    {
        return view('website.pages.account.execution');
    }

    public function execution_ajax(Request $request)
    {
        $data = Account::whereIn('final_status', ['IT MGR Approve', 'On Progress'])
            ->join('public.users', 'form_account.created_by', '=', 'users.id')
            ->select('form_account.*', 'users.name as requestor');

        return DataTables::eloquent($data)->make(true);
    }

    public function execution_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;
        $account = Account::findOrFail($id);

        $user = $account->createdBy;

        if ($type == 'ok') {
            $isi = "FORM ACCOUNT\n\n";

            $isi .= "Budget Type : " . $account->budget_type;
            $isi .= "\nForm Type : " . $account->form_type;

            $isi .= "\n\nNPK : *" . $account->npk . "*";
            $isi .= "\nName : *" . $account->fullname . "*";
            $isi .= "\nDepartment : " . $account->department;
            $isi .= "\nPhone : " . $account->phone;
            $isi .= "\nEmail : " . $request->email_address;
            $isi .= "\nPurpose : " . $account->purpose;

            $isi .= "\n\nStatus : Finished";

            $isi .= "\n\nManager Note : " . $account->manager_note;
            $isi .= "\nITD Note : " . $account->it_note;
            $isi .= "\nITD Manager Note : " . $account->it_mgr_note;
            $isi .= "\n\nNote : " . $request->finish_note;

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

            $account->ad_name = $request->ad_name;
            $account->email_address = $request->email_address;
            $account->is_finish = 1;
            $account->is_confirm = 0;
            $account->finish_by = Auth::user()->id;
            $account->final_status = 'Finished';
            $account->finish_note = 'Done';
        } else if ($type == 'On Progress') {
            $account->is_on_progress = 1;
            $account->final_status = 'On Progress';
            $account->on_progress_note = $request->on_progress_note;
        } else {
            $account->is_finish = 0;
            $account->final_status = 'Rejected';
            $account->finish_note = $request->finish_note;
            $account->finish_by = Auth::user()->id;
        }
        $account->finish_date = Carbon::now();
        $account->save();

        return "Request is Saved!";
    }

    public function show_data_execution()
    {
        $depts = Department::all();
        return view('website.pages.account.show_data_execution', compact(['depts']));
    }

    public function show_data_execution_ajax(Request $request)
    {
        $data = Account::where('is_finish', '1')->orWhere('is_finish', '0')
            ->join('users', 'form_account.created_by', '=', 'users.id')
            ->select('form_account.*', 'users.name as requestor');

        return DataTables::eloquent($data)->make(true);
    }
}
