<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vpn;
use App\Models\Department;
use App\Models\User;
use App\Models\Alert;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

use App\Traits\HasAjaxList;

class VpnController extends Controller
{
    use HasAjaxList;

    public function create()
    {
        $departments = Department::orderBy('name')->get();

        $auth = User::where('id', Auth::user()->id)
            ->whereNull('nohp')
            ->count();

        $data = Vpn::where('created_by', Auth::user()->id)
                        ->where(function($query) {
                                $query->where('final_status', 'LIKE', '%Reject%')
                                    ->orWhere('final_status', 'Finished');
                        })
                        ->where('is_confirm', 0)
                        ->count();

        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else if ($data > 0) {
            return redirect()->route('website.vpn.list')->with('info', 'Please confirm!');
        } else {
            return view('website.pages.vpn.create', compact('departments'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_reg' => 'unique',
            'npk' => 'nullable|min:6',
            'fullname' => 'required',
            'department' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'purpose' => 'required',
        ]);

        $year = date('y');
        $month = date('m');
        $lastForm = DB::table('form_vpn')
            ->select('no_reg')
            ->orderBy('no_reg', 'desc')
            ->first();
        $lastNumber = ($lastForm) ? substr($lastForm->no_reg, -3) : '000';

        $lastMonth = ($lastForm) ? substr($lastForm->no_reg, 6, 2) : '00';
        if ($lastMonth !== $month) {
            $lastNumber = '000';
        }
        $newNumber = str_pad((intval($lastNumber) + 1), strlen($lastNumber), '0', STR_PAD_LEFT);
        $no_reg = 'VPN/' . $year . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

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
            $form_vpn = Vpn::create([
                'no_reg' => $no_reg,
                'npk' => $request->npk,
                'fullname' => $request->fullname,
                'department' => $request->department,
                'phone' => $request->phone,
                'email' => $request->email,
                'username' => $request->username,
                'purpose' => $request->purpose,
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
            $form_vpn->save();

            return redirect()->route('website.vpn.list')->with('success', 'Create Successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function edit($id)
    {
        $vpn = Vpn::findOrFail($id);
        $departments = Department::orderBy('name')->get();

        return view('website.pages.vpn.edit', compact('vpn', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'npk' => 'nullable|min:6',
            'fullname' => 'required',
            'department' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'username' => 'required',
            'purpose' => 'required',
        ]);

        $form_vpn = Vpn::findOrFail($id);

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
            $form_vpn->update([
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
            return redirect()->route('website.vpn.list')->with('success', 'Success Edit Form');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function list()
    {
        return view('website.pages.vpn.list');
    }

    public function list_ajax(Request $request)
    {
        return $this->generateAjaxList(\App\Models\Vpn::class, 'form_vpn');
    }

    public function approve_form(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $vpn = Vpn::findOrFail($id);

        if ($type == 'confirm') {
            $vpn->is_confirm = 1;
        } else {
            $vpn->is_confirm = 0;
        }
        $vpn->save();

        return "Confirm Successfully";
    }

    public function delete_form(Request $request)
    {
        $id = $request->id;

        $vpn = Vpn::findOrFail($id);
        $vpn->delete();

        return "Delete Successfully";
    }

    // MGR //

    public function manager_approval()
    {
        return view('website.pages.vpn.manager_approval');
    }

    public function manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Vpn::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->where('final_status', 'created')
            ->join('public.users', 'form_vpn.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_vpn.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_vpn.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_vpn.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_vpn.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_vpn.finish_by', 'finish.id')
                        ->select('form_vpn.*', 'users.name as requestor',
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

        $vpn = Vpn::findOrFail($id);

        if ($type == 'approve') {
            $vpn->is_manager_approve = 1;
            $vpn->final_status = 'Manager Approve';
            $vpn->manager_note = $request->manager_note;
            $vpn->manager_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $vpn->is_manager_approve = 0;
            $vpn->final_status = 'Manager Reject';
            $vpn->manager_note = $request->manager_note;
            $vpn->manager_approve_by = Auth::user()->id;
            $vpn->is_finish = 0;
            $vpn->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $vpn->manager_approval_date = Carbon::now();
        $vpn->save();
        return $return;
    }

    public function manager_approved()
    {
        return view('website.pages.vpn.manager_approved');
    }

    public function manager_approved_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Vpn::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->whereNotNull('is_manager_approve')
            ->join('public.users', 'form_vpn.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_vpn.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_vpn.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_vpn.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_vpn.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_vpn.finish_by', 'finish.id')
                        ->select('form_vpn.*', 'users.name as requestor',
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
        return view('website.pages.vpn.it_approval');
    }

    public function it_approval_ajax(Request $request)
    {
        $data = Vpn::where('final_status', 'Manager Approve')
                        ->join('public.users', 'form_vpn.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_vpn.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_vpn.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_vpn.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_vpn.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_vpn.finish_by', 'finish.id')
                        ->select('form_vpn.*', 'users.name as requestor',
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

        $vpn = Vpn::findOrFail($id);

        if ($type == 'approve') {
            $vpn->is_it_approve = 1;
            $vpn->final_status = 'IT Approve';
            $vpn->it_note = $request->it_note;
            $vpn->it_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $vpn->is_it_approve = 0;
            $vpn->is_confirm = 0;
            $vpn->final_status = 'IT Reject';
            $vpn->it_note = $request->it_note;
            $vpn->is_finish = 0;
            $vpn->it_approve_by = Auth::user()->id;
            $return = "Reject Successfully";
        }
        $vpn->it_approval_date = Carbon::now();
        $vpn->save();

        if ($request->notifikasi == 'Ya') {
            $isi = "FORM VPN\n";
            $isi .= "*TUNGGU APPROVE IT MANAGER*";
            $isi .= "\n\nREQUESTOR";
            $isi .= "\nNama : *" . $vpn->createdBy->name . "*";
            $isi .= "\nDepartment : *" . $vpn->createdBy->departments->pluck('code')->implode(', ') . "*";
            $isi .= "\nPurpose : " . $vpn->purpose;
            $isi .= "\n\nNote : Dear Pak Ferry, Mohon untuk dicek tunggu approve pada FIOLA. Terimakasih";

            $isi .= "\n\nApproved ITD by : " . Auth::user()->name;

            $nomors = Alert::where('role', 'IT Manager')->get();

            foreach ($nomors as $nomor) {
                $token = "793D30579A77D4A0E12648872BFBB085";
                $message = "----------FIOLA----------\n"
                    . $isi
                    . "\n-------------------------";
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://app.fastwa.com/api/v1/4D9AF7CE224B91C9CE14FFDDB55D248D/send_text',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => 'api_key='.$token.'&phone='.$nomor.'&message='.$message,
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                sleep(10);
                echo $response;
            }
        }
        return $return;
    }

    public function it_approved()
    {
        return view('website.pages.vpn.it_approved');
    }

    public function it_approved_ajax(Request $request)
    {
        $data = Vpn::whereNotNull('is_it_approve')
                        ->join('public.users', 'form_vpn.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_vpn.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_vpn.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_vpn.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_vpn.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_vpn.finish_by', 'finish.id')
                        ->select('form_vpn.*', 'users.name as requestor',
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
        return view('website.pages.vpn.it_mgr_approval');
    }

    public function it_mgr_approval_ajax(Request $request)
    {
        $data = Vpn::where('final_status', 'IT Approve')
                        ->join('public.users', 'form_vpn.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_vpn.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_vpn.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_vpn.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_vpn.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_vpn.finish_by', 'finish.id')
                        ->select('form_vpn.*', 'users.name as requestor',
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

        $vpn = Vpn::findOrFail($id);

        if ($type == 'approve') {
            $vpn->is_it_mgr_approve = 1;
            $vpn->final_status = 'IT MGR Approve';
            $vpn->it_mgr_note = $request->it_mgr_note;
            $vpn->it_mgr_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $vpn->is_it_mgr_approve = 0;
            $vpn->final_status = 'IT MGR Reject';
            $vpn->it_mgr_note = $request->it_mgr_note;
            $vpn->it_mgr_approve_by = Auth::user()->id;
            $vpn->is_finish = 0;
            $vpn->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $vpn->it_mgr_approval_date = Carbon::now();
        $vpn->save();
        return $return;
    }

    public function it_mgr_approved()
    {
        return view('website.pages.vpn.it_mgr_approved');
    }

    public function it_mgr_approved_ajax(Request $request)
    {
        $data = Vpn::whereNotNull('is_it_mgr_approve')
                        ->join('public.users', 'form_vpn.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_vpn.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_vpn.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_vpn.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_vpn.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_vpn.finish_by', 'finish.id')
                        ->select('form_vpn.*', 'users.name as requestor',
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
        return view('website.pages.vpn.execution');
    }

    public function execution_ajax(Request $request)
    {
        $data = Vpn::whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                        ->join('public.users', 'form_vpn.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_vpn.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_vpn.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_vpn.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_vpn.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_vpn.finish_by', 'finish.id')
                        ->select('form_vpn.*', 'users.name as requestor',
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

        $vpn = Vpn::findOrFail($id);

        $user = $vpn->createdBy;

        if ($type == 'approve') {
            $vpn->is_finish = 1;
            $vpn->is_confirm = 0;
            $vpn->finish_by = Auth::user()->id;
            $vpn->final_status = 'Finished';
            $vpn->finish_note = $request->finish_note;
            $vpn->finish_date = Carbon::now();
            $return = "Approve Successfully";
        } else if ($type == 'progress') {
            $vpn->is_on_progress = 1;
            $vpn->final_status = 'On Progress';
            $vpn->on_progress_note = $request->on_progress_note;
            $vpn->on_progress_by = Auth::user()->id;
            $vpn->on_progress_date = Carbon::now();
            $return = "Progress Successfully";
        } else {
            $vpn->is_finish = 0;
            $vpn->is_confirm = 0;
            $vpn->final_status = 'Rejected';
            $vpn->finish_note = $request->finish_note;
            $vpn->finish_by = Auth::user()->id;
            $vpn->finish_date = Carbon::now();
            $return = "Reject Successfully";
        }
        $vpn->save();

        if ($request->notifikasi == 'Ya') {
            $isi = "FORM VPN\n\n";

            $isi .= "NPK : *" . $vpn->npk . "*";
            $isi .= "\nName : *" . $vpn->fullname . "*";
            $isi .= "\nDepartment : " . $vpn->department;
            $isi .= "\nPhone : " . $vpn->phone;
            $isi .= "\nEmail : " . $vpn->email;
            $isi .= "\nUsername : " . $vpn->username;
            $isi .= "\nPurpose : " . $vpn->purpose;

            $isi .= "\n\nStatus : *Finished*";

            $isi .= "\n\nManager Note : " . $vpn->manager_note;
            $isi .= "\nITD Note : " . $vpn->it_note;
            $isi .= "\nITD Manager Note : " . $vpn->it_mgr_note;
            $isi .= "\n\nFinish Note : " . $request->finish_note;

            $isi .= "\n\nExecution by : " . Auth::user()->name;

            $nomor = $user->nohp;

            $token = "793D30579A77D4A0E12648872BFBB085";
            $message = "----------FIOLA----------\n"
                . $isi
                . "\n-------------------------";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://app.fastwa.com/api/v1/4D9AF7CE224B91C9CE14FFDDB55D248D/send_text',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'api_key='.$token.'&phone='.$nomor.'&message='.$message,
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            sleep(10);
            echo $response;
        }

        return $return;
    }

    public function finished()
    {
        return view('website.pages.vpn.finished');
    }

    public function finished_ajax(Request $request)
    {
        $data = Vpn::whereNotNull('is_finish')
                        ->join('public.users', 'form_vpn.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_vpn.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_vpn.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_vpn.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_vpn.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_vpn.finish_by', 'finish.id')
                        ->select('form_vpn.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }
}
