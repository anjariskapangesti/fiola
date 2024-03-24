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

class SoftwareController extends Controller
{
    public function create()
    {
        $departments = Department::orderBy('name')->get();

        $userDepartment = Auth::user()->departments;

        $auth = User::where('id', Auth::user()->id)
                                    ->whereNull('nohp')
                                    ->count(); 
        
        $data = Software::where('created_by', Auth::user()->id)->where('final_status', 'Finished')->where('is_confirm', 0)->count();
        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else if($data > 0){
            return redirect()->route('website.software.show_data_form')->with('info', 'Please confirm!');
        }else{
            return view('website.pages.software.create', compact(['departments', 'userDepartment']));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_reg' => 'unique',
            'category' => 'required' ,            
            'appname' => 'required' ,
            'installon' => 'required' ,
            'detail' => 'required' ,
            'purpose' => 'required' ,
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

        try
        {
            $form_software = Software::create([
                'no_reg' => $no_reg,
                'category' => $request->category ,
                'appname' => $request->appname ,
                'installon' => $request->installon ,
                'detail' => $request->detail ,
                'purpose' => $request->purpose ,                
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

            $depts = Department::all();
            return redirect()->route('website.software.show_data_form')->with('success', 'Success Create Form');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function show_data_form()
    {
        $depts = Department::all();
        return view('website.pages.software.show_data_form', compact(['depts']));
    }

    public function show_data_form_ajax(Request $request)
    {
        
        $data = Software::orderBy('id', 'DESC')
                        ->where('created_by', Auth::user()->id)
                        ->join('public.users', 'form_software.created_by', '=', 'users.id')
                        ->select('form_software.*', 'users.name as user_name');

        return DataTables::eloquent($data)->make(true);
    }

    public function approve_form(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $software = Software::findOrFail($id);
        if($type=='ok'){
            $software->is_confirm=1;
        }else{
            $software->is_confirm=0;
        }
        $software->save();
        return "Confirm is Saved!";
    }

    // MGR //

    public function show_manager_approval()
    {
        return view('website.pages.software.approval_manager');
    }

    public function show_manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();
        
        $data = Software::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
        ->where('final_status', 'created')
        ->join('public.users', 'form_software.created_by', '=', 'users.id')
        ->select('form_software.*', 'users.name as user_name');

        return DataTables::eloquent($data)->make(true);
    }

    public function show_data_manager_approval()
    {
        $depts = Department::all();
        return view('website.pages.software.show_data_manager_approval', compact(['depts']));
    }

    public function show_data_manager_approval_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();
        
        $data = Software::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
        ->where('is_manager_approve','1')
        ->join('users', 'form_software.created_by', '=', 'users.id')
        ->select('form_software.*', 'users.name as user_name');
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_manager(Request $request)
    {
        $id=$request->id;
        
        $type=$request->type;
        
        $software = Software::findOrFail($id);
        
        if($type=='ok'){
            $software->is_manager_approve=1;
            $software->final_status='Manager Approve';
            $software->manager_note=$request->manager_note;
        }else{
            $software->is_manager_approve=0;
            $software->final_status='Manager Reject';
            $software->manager_note=$request->manager_note;
            $software->is_finish=0;
        }
        $software->manager_approval_date= Carbon::now();
        $software->save();
        return "Request is Saved!";        
    }

    /// ITD APPROVE ///

    public function show_it_approval()
    {
        return view('website.pages.software.approval_it');
    }

    public function show_it_approval_ajax(Request $request)
    {
        $data = Software::where('final_status','Manager Approve')
                        ->join('users', 'form_software.created_by', '=', 'users.id')
                        ->select('form_software.*', 'users.name as user_name');

        return DataTables::eloquent($data)->make(true);
    }

    public function show_data_it_approval()
    {
        $depts = Department::all();
        return view('website.pages.software.show_data_it_approval', compact(['depts']));
    }

    public function show_data_it_approval_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = Software::where('is_it_approve','1')
                        ->join('users', 'form_software.created_by', '=', 'users.id')
                        ->select('form_software.*', 'users.name as user_name');
        
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_it(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $software = Software::findOrFail($id);
        if($type=='ok'){
            $isi = "FORM SOFTWARE\n";
            $isi .= "*TUNGGU APPROVE IT MANAGER*";
            $isi .= "\n\nREQUESTOR";
            $isi .= "\nNama : *" . $software->createdBy->name ."*";        
            
            $isi .= "\n\nApp Name : " . $software->appname;
            $isi .= "\nDetail : " . $software->detail;
            $isi .= "\nPurpose : " . $software->purpose;
            $isi .= "\n\nNote : Dear Pak Ferry, Mohon untuk dicek tunggu approve pada FIOLA. Terimakasih";

            $isi .= "\n\nApproved ITD by : " . Auth::user()->name;
            
            $nomorhpModel = new Alert();
            $nomorhp = $nomorhpModel->getNoHpItMgr();

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
                CURLOPT_POSTFIELDS => 'token='.$token.'&number='.$nomorhp.'&message='.$message,
                ));
                $response = curl_exec($curl);
                curl_close($curl);

            $software->is_it_approve=1;
            $software->final_status='IT Approve';
            $software->it_note=$request->it_note;
        }else{
            $software->is_it_approve=0;
            $software->final_status='IT Reject';
            $software->it_note=$request->it_note;
            $software->is_finish=0;
        }
        $software->it_approval_date= Carbon::now();
        $software->save();
        return "Request is Saved!";
    }

    /// IT MGR ///
    public function show_it_mgr_approval()
    {
        return view('website.pages.software.approval_it_mgr');
    }

    public function show_it_mgr_approval_ajax(Request $request)
    {
        $data = Software::where('final_status','IT Approve')
                        ->join('users', 'form_software.created_by', '=', 'users.id')
                        ->select('form_software.*', 'users.name as user_name');
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_it_mgr(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $software = Software::findOrFail($id);
        if($type=='ok'){
            $software->is_it_mgr_approve=1;
            $software->final_status='IT MGR Approve';
            $software->it_mgr_note=$request->it_mgr_note;
        }else{
            $software->is_it_mgr_approve=0;
            $software->final_status='IT MGR Reject';
            $software->it_mgr_note=$request->it_mgr_note;
            $software->is_finish=0;
        }
        $software->it_mgr_approval_date= Carbon::now();
        $software->save();
        return "Request is Saved!";
    }

    public function show_data_it_mgr_approval()
    {
        $depts = Department::all();
        return view('website.pages.software.show_data_it_mgr_approval', compact(['depts']));
    }

    public function show_data_it_mgr_approval_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = Software::where('is_it_mgr_approve','1')
                        ->join('users', 'form_software.created_by', '=', 'users.id')
                        ->select('form_software.*', 'users.name as user_name');;
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    /// EXECUTION ///
    public function show_execution()
    {
        return view('website.pages.software.approval_execution');
    }

    public function show_execution_ajax(Request $request)
    {
        $data = Software::where('final_status','IT MGR Approve')
                        ->join('users', 'form_software.created_by', '=', 'users.id')
                        ->select('form_software.*', 'users.name as user_name');
                        
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_execution(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $software = Software::findOrFail($id);
        
        $user = $software->createdBy;
        
        if($type=='ok'){
            $isi = "FORM SOFTWARE\n\n";
                
            $isi .= "Category : " . $software->category;
            
            $isi .= "\n\nApp Name : *" . $software->appname ."*";
            $isi .= "\nInstall on : " . $software->installon;
            $isi .= "\nDetail : " . $software->detail;
            $isi .= "\nPurpose : " . $software->purpose;
    
            $isi .= "\n\nStatus : Finished";
    
            $isi .= "\n\nManager Note : " . $software->manager_note;
            $isi .= "\nITD Note : " . $software->it_note;
            $isi .= "\nITD Manager Note : " . $software->it_mgr_note;
            $isi .= "\n\nNote : " . $request->finish_note;
    
            $nomor = $user->nohp;;
    
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
                CURLOPT_POSTFIELDS => 'token='.$token.'&number='.$nomor.'&message='.$message,
                ));
                $response = curl_exec($curl);
                curl_close($curl);
            $software->is_finish=1;
            $software->is_confirm=0;
            $software->final_status='Finished';
            $software->finish_note=$request->finish_note;
        }else{
            $software->is_finish=0;
            $software->final_status='Rejected';
            $software->finish_note=$request->finish_note;
        }
        $software->finish_date= Carbon::now();
        $software->save();

        return "Request is Saved!";
    }

    public function show_data_execution()
    {
        $depts = Department::all();
        return view('website.pages.software.show_data_execution', compact(['depts']));
    }

    public function show_data_execution_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = Software::where('is_finish','1')->orWhere('is_finish','0')
                        ->join('users', 'form_software.created_by', '=', 'users.id')
                        ->select('form_software.*', 'users.name as user_name');
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }
}
