<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Software;
use App\Models\Department;
use App\Models\User;
use App\Models\Alert;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

use App\Traits\HasAjaxList;

class SoftwareController extends Controller
{
    use HasAjaxList;

    public function create()
    {
        $departments = Department::orderBy('name')->get();

        $auth = User::where('id', Auth::user()->id)
            ->whereNull('nohp')
            ->count();

        $data = Software::where('created_by', Auth::user()->id)
                        ->where(function($query) {
                                $query->where('final_status', 'LIKE', '%Reject%')
                                    ->orWhere('final_status', 'Finished');
                        })
                        ->where('is_confirm', 0)
                        ->count();

        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else if ($data > 0) {
            return redirect()->route('website.software.list')->with('info', 'Please confirm!');
        } else {
            return view('website.pages.software.create', compact('departments'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_reg' => 'unique',
            'category' => 'required',
            'type' => 'required',
            'appname' => 'required',
            'installon' => 'required',
            'detail' => 'required',
            'purpose' => 'required',
        ]);

        $year = date('y');
        $month = date('m');
        $lastForm = DB::table('form_software')
                    ->select('no_reg')
                    ->orderBy('no_reg', 'desc')
                    ->first();
        $lastNumber = ($lastForm) ? substr($lastForm->no_reg, -3) : '000';
        
        $lastMonth = ($lastForm) ? substr($lastForm->no_reg, 6, 2) : '00';            
        if ($lastMonth !== $month){
            $lastNumber = '000';
        }            
        $newNumber = str_pad((intval($lastNumber) + 1), strlen($lastNumber), '0', STR_PAD_LEFT);            
        $no_reg = 'SWR/' . $year . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

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

        try
        {
            $form_software = Software::create([
                'no_reg' => $no_reg,
                'category' => $request->category,
                'type' => $request->type,
                'appname' => $request->appname,
                'installon' => $request->installon,
                'detail' => $request->detail,
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
            
            $form_software->save();

            return redirect()->route('website.software.list')->with('success', 'Create Successfully');
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function edit($id)
    {
        $software = Software::findOrFail($id);

        return view('website.pages.software.edit', compact('software'));
    }

    public function list()
    {
        return view('website.pages.software.list');
    }

    public function list_ajax(Request $request)
    {
        return $this->generateAjaxList(\App\Models\Software::class, 'form_software');
    }

    public function approve_form(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $software = Software::findOrFail($id);

        if ($type == 'confirm') {
            $software->is_confirm = 1;
        } else {
            $software->is_confirm = 0;
        }
        $software->save();

        return "Confirm Successfully";
    }

    public function delete_form(Request $request)
    {
        $id = $request->id;

        $software = Software::findOrFail($id);
        $software->delete();

        return "Delete Successfully";
    }

    // MGR //

    public function manager_approval()
    {
        return view('website.pages.software.manager_approval');
    }

    public function manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Software::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->where('final_status', 'created')
            ->join('public.users', 'form_software.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_software.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_software.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_software.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_software.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_software.finish_by', 'finish.id')
                        ->select('form_software.*', 'users.name as requestor',
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

        $software = Software::findOrFail($id);

        if ($type == 'approve') {
            $software->is_manager_approve = 1;
            $software->final_status = 'Manager Approve';
            $software->manager_note = $request->manager_note;
            $software->manager_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $software->is_manager_approve = 0;
            $software->final_status = 'Manager Reject';
            $software->manager_note = $request->manager_note;
            $software->manager_approve_by = Auth::user()->id;
            $software->is_finish = 0;
            $software->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $software->manager_approval_date = Carbon::now();
        $software->save();
        return $return;
    }

    public function manager_approved()
    {
        return view('website.pages.software.manager_approved');
    }

    public function manager_approved_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $data = Software::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->whereNotNull('is_manager_approve')
            ->join('public.users', 'form_software.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_software.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_software.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_software.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_software.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_software.finish_by', 'finish.id')
                        ->select('form_software.*', 'users.name as requestor',
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
        return view('website.pages.software.it_approval');
    }

    public function it_approval_ajax(Request $request)
    {
        $data = Software::where('final_status', 'Manager Approve')
                        ->join('public.users', 'form_software.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_software.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_software.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_software.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_software.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_software.finish_by', 'finish.id')
                        ->select('form_software.*', 'users.name as requestor',
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

        $software = Software::findOrFail($id);
        
        if ($type == 'approve') {
            $software->is_it_approve = 1;
            $software->final_status = 'IT Approve';
            $software->it_note = $request->it_note;
            $software->it_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $software->is_it_approve = 0;
            $software->final_status = 'IT Reject';
            $software->it_note = $request->it_note;
            $software->is_finish = 0;
            $software->is_confirm = 0;
            $software->it_approve_by = Auth::user()->id;
            $return = "Reject Successfully";
        }
        $software->it_approval_date = Carbon::now();
        $software->save();
        
        if ($request->notifikasi == 'Ya') {
            $isi = "FORM SOFTWARE\n";
            $isi .= "*TUNGGU APPROVE IT MANAGER*";
            $isi .= "\n\nCategory : " . $software->category;
            $isi .= "\n\nType : " . $software->type;
            $isi .= "\n\nREQUESTOR";
            $isi .= "\nNama : *" . $software->createdBy->name . "*";
            $isi .= "\nDepartment : *" . $software->createdBy->departments->pluck('code')->implode(', ') . "*";
            $isi .= "\nPurpose : " . $software->purpose;
            $isi .= "\n\nNote : Dear Pak Ferry, Mohon untuk dicek tunggu approve pada FIOLA. Terimakasih";

            $isi .= "\n\nApproved ITD by : " . Auth::user()->name;

            $nomors = Alert::where('role', 'IT Manager')->get();

            foreach ($nomors as $nomor) {
                $token = env('TOKEN_WA');
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
        return view('website.pages.software.it_approved');
    }

    public function it_approved_ajax(Request $request)
    {
        $data = Software::whereNotNull('is_it_approve')
                        ->join('public.users', 'form_software.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_software.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_software.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_software.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_software.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_software.finish_by', 'finish.id')
                        ->select('form_software.*', 'users.name as requestor',
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
        return view('website.pages.software.it_mgr_approval');
    }

    public function it_mgr_approval_ajax(Request $request)
    {
        $data = Software::where('final_status', 'IT Approve')
                        ->join('public.users', 'form_software.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_software.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_software.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_software.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_software.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_software.finish_by', 'finish.id')
                        ->select('form_software.*', 'users.name as requestor',
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

        $software = Software::findOrFail($id);

        if ($type == 'approve') {
            $software->is_it_mgr_approve = 1;
            $software->final_status = 'IT MGR Approve';
            $software->it_mgr_note = $request->it_mgr_note;
            $software->it_mgr_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else {
            $software->is_it_mgr_approve = 0;
            $software->final_status = 'IT MGR Reject';
            $software->it_mgr_note = $request->it_mgr_note;
            $software->it_mgr_approve_by = Auth::user()->id;
            $software->is_finish = 0;
            $software->is_confirm = 0;
            $return = "Reject Successfully";
        }
        $software->it_mgr_approval_date = Carbon::now();
        $software->save();
        return $return;
    }

    public function it_mgr_approved()
    {
        return view('website.pages.software.it_mgr_approved');
    }

    public function it_mgr_approved_ajax(Request $request)
    {
        $data = Software::whereNotNull('is_it_mgr_approve')
                        ->join('public.users', 'form_software.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_software.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_software.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_software.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_software.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_software.finish_by', 'finish.id')
                        ->select('form_software.*', 'users.name as requestor',
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
        return view('website.pages.software.execution');
    }

    public function execution_ajax(Request $request)
    {
        $data = Software::whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                        ->join('public.users', 'form_software.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_software.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_software.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_software.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_software.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_software.finish_by', 'finish.id')
                        ->select('form_software.*', 'users.name as requestor',
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

        $software = Software::findOrFail($id);

        $user = $software->createdBy;

        if ($type == 'approve') {
            $software->is_finish = 1;
            $software->is_confirm = 0;
            $software->finish_by = Auth::user()->id;
            $software->final_status = 'Finished';
            $software->finish_note = $request->finish_note;
            $software->finish_date = Carbon::now();
            $return = "Approve Successfully";
        } else if ($type == 'progress') {
            $software->is_on_progress = 1;
            $software->final_status = 'On Progress';
            $software->on_progress_note = $request->on_progress_note;
            $software->on_progress_by = Auth::user()->id;
            $software->on_progress_date = Carbon::now();
            $return = "Progress Successfully";
        } else {
            $software->is_finish = 0;
            $software->is_confirm = 0;
            $software->final_status = 'Rejected';
            $software->finish_note = $request->finish_note;
            $software->finish_by = Auth::user()->id;
            $software->finish_date = Carbon::now();
            $return = "Reject Successfully";
        }
        $software->save();

        if ($request->notifikasi == 'Ya') {
            $isi = "FORM software\n\n";
            
            $isi .= "Category : " . $software->category;
            $isi .= "\nType : " . $software->type;
            
            $isi .= "\n\nApplication Name : *" . $software->appname . "*";
            $isi .= "\nDevice Name : *" . $software->installon . "*";
            $isi .= "\nDetails : " . $software->detail;
            $isi .= "\nPurpose : " . $software->purpose;
            
            $isi .= "\n\nStatus : *Finished*";
            
            $isi .= "\n\nManager Note : " . $software->manager_note;
            $isi .= "\nITD Note : " . $software->it_note;
            $isi .= "\nITD Manager Note : " . $software->it_mgr_note;
            $isi .= "\n\nFinish Note : " . $request->finish_note;
            
            $isi .= "\n\nExecution by : " . Auth::user()->name;
            
            $nomor = $user->nohp;
            
            $token = env('TOKEN_WA');
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
        return view('website.pages.software.finished');
    }

    public function finished_ajax(Request $request)
    {
        $data = Software::whereNotNull('is_finish')
                        ->join('public.users', 'form_software.created_by', 'public.users.id')
                        ->leftJoin('public.users as manager', 'form_software.manager_approve_by', 'manager.id')
                        ->leftJoin('public.users as it', 'form_software.it_approve_by', 'it.id')
                        ->leftJoin('public.users as it_mgr', 'form_software.it_mgr_approve_by', 'it_mgr.id')
                        ->leftJoin('public.users as on_progress', 'form_software.on_progress_by', 'on_progress.id')
                        ->leftJoin('public.users as finish', 'form_software.finish_by', 'finish.id')
                        ->select('form_software.*', 'users.name as requestor',
                                    'manager.name as manager_name',
                                    'it.name as it_name',
                                    'it_mgr.name as it_mgr_name',
                                    'on_progress.name as on_progress_name',
                                    'finish.name as finish_name')
                        ->orderBy('created_at', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }
}
