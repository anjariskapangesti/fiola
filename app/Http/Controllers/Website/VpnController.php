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

class VpnController extends Controller
{
    public function create()
    {
        $departments = Department::orderBy('name')->get();

        $userDepartment = Auth::user()->createdDepartments;

        $auth = User::where('id', Auth::user()->id)
                                    ->whereNull('nohp')
                                    ->count(); 
        
        $data = Vpn::where('created_by', Auth::user()->id)->where('final_status', 'Finished')->where('is_confirm', 0)->count();
        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else if($data > 0){
            return redirect()->route('website.vpn.show_data_form')->with('info', 'Please confirm!');
        }else{
            return view('website.pages.vpn.create', compact(['departments', 'userDepartment']));
        }
    }

    public function store(Request $request)
    {
        $request->validate([  
            'no_reg' => 'unique',
            'npk' => 'required' ,          
            'fullname' => 'required' ,
            'department' => 'required' ,
            'phone' => 'required' ,
            'email' => 'required' ,
            'username' => 'required' ,
            'purpose' => 'required' ,
        ]);

        $year = date('y');
        $month = date('m');
        $lastForm = DB::table('form_vpn')
                      ->select('no_reg')
                      ->orderBy('no_reg', 'desc')
                      ->first();
        $lastNumber = ($lastForm) ? substr($lastForm->no_reg, -3) : '000';
        
        $lastMonth = ($lastForm) ? substr($lastForm->no_reg, 6, 2) : '00';            
        if ($lastMonth !== $month){
            $lastNumber = '000';
        }            
        $newNumber = str_pad((intval($lastNumber) + 1), strlen($lastNumber), '0', STR_PAD_LEFT);            
        $no_reg = 'VPN/' . $year . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

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

        try
        {
            $form_vpn = Vpn::create([
                'no_reg' => $no_reg,
                'npk' => $request->npk ,
                'fullname' => $request->fullname ,
                'department' => $request->department ,
                'phone' => $request->phone ,
                'email' => $request->email ,
                'username' => $request->username ,
                'purpose' => $request->purpose ,                
                'created_by' => Auth::user()->id,
                'created_dept' => Auth::user()->departments->pluck('id')->first(),
                'final_status' => $finalStatus,
                'is_manager_approve' => $isManagerApprove,
                'manager_approval_date' => $managerApprovalDate,            
            ]);
            
            $form_vpn->save();

            $depts = Department::all();
            return redirect()->route('website.vpn.show_data_form')->with('success', 'Success Create Form');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function show_data_form()
    {
        $depts = Department::all();
        return view('website.pages.vpn.show_data_form', compact(['depts']));
    }

    public function show_data_form_ajax(Request $request)
    {
        
        $data = Vpn::orderBy('id', 'DESC')
                        ->where('created_by', Auth::user()->id)
                        ->join('users', 'form_vpn.created_by', '=', 'users.id')
                        ->select('form_vpn.*', 'users.name as user_name');

        return DataTables::eloquent($data)->make(true);
    }

    public function approve_form(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $vpn = Vpn::findOrFail($id);
        if($type=='ok'){
            $vpn->is_confirm=1;
        }else{
            $vpn->is_confirm=0;
        }
        $vpn->save();
        return "Confirm is Saved!";
    }

    // MGR //

    public function show_manager_approval()
    {
        return view('website.pages.vpn.approval_manager');
    }

    public function show_manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();
        
        $data = Vpn::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
        ->where('final_status', 'created')
        ->join('users', 'form_vpn.created_by', '=', 'users.id')
        ->select('form_vpn.*', 'users.name as user_name');
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    public function show_data_manager_approval()
    {
        $depts = Department::all();
        return view('website.pages.vpn.show_data_manager_approval', compact(['depts']));
    }

    public function show_data_manager_approval_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();
        
        $data = Vpn::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
        ->where('is_manager_approve','1')
        ->join('users', 'form_vpn.created_by', '=', 'users.id')
        ->select('form_vpn.*', 'users.name as user_name');
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_manager(Request $request)
    {
        $id=$request->id;
        
        $type=$request->type;
        
        $vpn = Vpn::findOrFail($id);
        
        if($type=='ok'){
            $vpn->is_manager_approve=1;
            $vpn->final_status='Manager Approve';
            $vpn->manager_note=$request->manager_note;
        }else{
            $vpn->is_manager_approve=0;
            $vpn->final_status='Manager Reject';
            $vpn->manager_note=$request->manager_note;
            $vpn->is_finish=0;
        }
        $vpn->manager_approval_date= Carbon::now();
        $vpn->save();
        return "Request is Saved!";        
    }

    /// ITD APPROVE ///

    public function show_it_approval()
    {
        return view('website.pages.vpn.approval_it');
    }

    public function show_it_approval_ajax(Request $request)
    {
        $data = Vpn::where('final_status','Manager Approve')
                        ->join('users', 'form_vpn.created_by', '=', 'users.id')
                        ->select('form_vpn.*', 'users.name as user_name');

        return DataTables::eloquent($data)->make(true);
    }

    public function show_data_it_approval()
    {
        $depts = Department::all();
        return view('website.pages.vpn.show_data_it_approval', compact(['depts']));
    }

    public function show_data_it_approval_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = Vpn::where('is_it_approve','1')
                        ->join('users', 'form_vpn.created_by', '=', 'users.id')
                        ->select('form_vpn.*', 'users.name as user_name');
        
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_it(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $vpn = Vpn::findOrFail($id);
        if($type=='ok'){
            $isi = "FORM VPN\n";
            $isi .= "*TUNGGU APPROVE IT MANAGER*";
            $isi .= "\n\nREQUESTOR";
            $isi .= "\nNama : *" . $vpn->fullname ."*";        
            
            $isi .= "\n\nDepartment : " . $vpn->department;
            $isi .= "\nEmail : " . $vpn->email;
            $isi .= "\nNote : Dear Pak Ferry, Mohon untuk dicek tunggu approve pada FIOLA. Terimakasih";

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

            $vpn->is_it_approve=1;
            $vpn->final_status='IT Approve';
            $vpn->it_note=$request->it_note;
        }else{
            $vpn->is_it_approve=0;
            $vpn->final_status='IT Reject';
            $vpn->it_note=$request->it_note;
            $vpn->is_finish=0;
        }
        $vpn->it_approval_date= Carbon::now();
        $vpn->save();
        return "Request is Saved!";
    }

    /// IT MGR ///
    public function show_it_mgr_approval()
    {
        return view('website.pages.vpn.approval_it_mgr');
    }

    public function show_it_mgr_approval_ajax(Request $request)
    {
        $data = Vpn::where('final_status','IT Approve')
                        ->join('users', 'form_vpn.created_by', '=', 'users.id')
                        ->select('form_vpn.*', 'users.name as user_name');
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_it_mgr(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $vpn = Vpn::findOrFail($id);
        if($type=='ok'){
            $vpn->is_it_mgr_approve=1;
            $vpn->final_status='IT MGR Approve';
            $vpn->it_mgr_note=$request->it_mgr_note;
        }else{
            $vpn->is_it_mgr_approve=0;
            $vpn->final_status='IT MGR Reject';
            $vpn->it_mgr_note=$request->it_mgr_note;
            $vpn->is_finish=0;
        }
        $vpn->it_mgr_approval_date= Carbon::now();
        $vpn->save();
        return "Request is Saved!";
    }

    public function show_data_it_mgr_approval()
    {
        $depts = Department::all();
        return view('website.pages.vpn.show_data_it_mgr_approval', compact(['depts']));
    }

    public function show_data_it_mgr_approval_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = Vpn::where('is_it_mgr_approve','1')
                        ->join('users', 'form_vpn.created_by', '=', 'users.id')
                        ->select('form_vpn.*', 'users.name as user_name');;
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    /// EXECUTION ///
    public function show_execution()
    {
        return view('website.pages.vpn.approval_execution');
    }

    public function show_execution_ajax(Request $request)
    {
        $data = Vpn::where('final_status','IT MGR Approve')
                        ->join('users', 'form_vpn.created_by', '=', 'users.id')
                        ->select('form_vpn.*', 'users.name as user_name');
                        
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_execution(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $vpn = Vpn::findOrFail($id);
        
        $user = $vpn->createdBy;
        
        if($type=='ok'){
            $isi = "FORM VPN\n";
        
            $isi .= "\nNPK : *" . $vpn->npk ."*";
            $isi .= "\nName : *" . $vpn->fullname ."*";
            $isi .= "\nDepartment : " . $vpn->department;
            $isi .= "\nPhone : " . $vpn->phone;
            $isi .= "\nEmail : " . $vpn->email;
            $isi .= "\nUsername : " . $vpn->username;
            $isi .= "\nPurpose : " . $vpn->purpose;
    
            $isi .= "\n\nStatus : Finished";
    
            $isi .= "\n\nManager Note : " . $vpn->manager_note;
            $isi .= "\nITD Note : " . $vpn->it_note;
            $isi .= "\nITD Manager Note : " . $vpn->it_mgr_note;
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
                
            $vpn->is_finish=1;
            $vpn->is_confirm=0;
            $vpn->final_status='Finished';
            $vpn->finish_note=$request->finish_note;
        }else{
            $vpn->is_finish=0;
            $vpn->final_status='Rejected';
            $vpn->finish_note=$request->finish_note;
        }
        $vpn->finish_date= Carbon::now();
        $vpn->save();

        return "Request is Saved!";
    }

    public function show_data_execution()
    {
        $depts = Department::all();
        return view('website.pages.vpn.show_data_execution', compact(['depts']));
    }

    public function show_data_execution_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = Vpn::where('is_finish','1')->orWhere('is_finish','0')
                        ->join('users', 'form_vpn.created_by', '=', 'users.id')
                        ->select('form_vpn.*', 'users.name as user_name');
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }
}
