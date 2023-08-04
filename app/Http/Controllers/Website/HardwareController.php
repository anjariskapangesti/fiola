<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Hardware;
use App\Models\Department;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

class HardwareController extends Controller
{
    public function create()
    {
        $departments = Department::orderBy('name')->get();

        $userDepartment = Auth::user()->createdDepartments;

        $auth = User::where('id', Auth::user()->id)
                                    ->whereNull('nohp')
                                    ->count(); 
        
        $data = Hardware::where('created_by', Auth::user()->id)->where('final_status', 'Finished')->where('is_confirm', 0)->count();
        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else if($data > 0){
            return redirect()->route('website.hardware.show_data_form')->with('info', 'Please confirm!');
        }else{
            return view('website.pages.hardware.create', compact(['departments', 'userDepartment']));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_reg' => 'unique',
            'category' => 'required' ,
            'type' => 'required' ,  
            'npk' => 'required' ,          
            'fullname' => 'required' ,
            'department' => 'required' ,
            'phone' => 'required' ,
            'due_date' => 'required' ,
            'purpose' => 'required' ,
        ]);

        $year = date('y');
        $month = date('m');
        $lastForm = DB::table('form_hardware')
                      ->select('no_reg')
                      ->orderBy('no_reg', 'desc')
                      ->first();
        $lastNumber = ($lastForm) ? substr($lastForm->no_reg, -3) : '000';
        
        $lastMonth = ($lastForm) ? substr($lastForm->no_reg, 6, 2) : '00';            
        if ($lastMonth !== $month){
            $lastNumber = '000';
        }            
        $newNumber = str_pad((intval($lastNumber) + 1), strlen($lastNumber), '0', STR_PAD_LEFT);            
        $no_reg = 'HWR/' . $year . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

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
            $form_hardware = Hardware::create([
                'no_reg' => $no_reg,
                'category' => $request->category ,
                'type' => $request->type ,
                'npk' => $request->npk ,
                'fullname' => $request->fullname ,
                'department' => $request->department ,
                'phone' => $request->phone ,
                'due_date' => $request->due_date ,
                'device_before' => $request->device_before ,
                'purpose' => $request->purpose ,                
                'created_by' => Auth::user()->id,
                'created_dept' => Auth::user()->departments->pluck('id')->first(),
                'final_status' => $finalStatus,
                'is_manager_approve' => $isManagerApprove,
                'manager_approval_date' => $managerApprovalDate,            
            ]);
            
            $form_hardware->save();

            $depts = Department::all();
            return redirect()->route('website.hardware.show_data_form')->with('success', 'Success Create Form');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function show_data_form()
    {
        $depts = Department::all();
        return view('website.pages.hardware.show_data_form', compact(['depts']));
    }

    public function show_data_form_ajax(Request $request)
    {
        
        $data = Hardware::orderBy('id', 'DESC')
                        ->where('created_by', Auth::user()->id)
                        ->join('users', 'form_hardware.created_by', '=', 'users.id')
                        ->select('form_hardware.*', 'users.name as user_name');

        return DataTables::eloquent($data)->make(true);
    }

    public function approve_form(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $hardware = Hardware::findOrFail($id);
        if($type=='ok'){
            $hardware->is_confirm=1;
        }else{
            $hardware->is_confirm=0;
        }
        $hardware->save();
        return "Confirm is Saved!";
    }

    // MGR //

    public function show_manager_approval()
    {
        return view('website.pages.hardware.approval_manager');
    }

    public function show_manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();
        
        $data = Hardware::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
        ->where('final_status', 'created')
        ->join('users', 'form_hardware.created_by', '=', 'users.id')
        ->select('form_hardware.*', 'users.name as user_name');
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    public function show_data_manager_approval()
    {
        $depts = Department::all();
        return view('website.pages.hardware.show_data_manager_approval', compact(['depts']));
    }

    public function show_data_manager_approval_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();
        
        $data = Hardware::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
        ->where('is_manager_approve','1')
        ->join('users', 'form_hardware.created_by', '=', 'users.id')
        ->select('form_hardware.*', 'users.name as user_name');
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_manager(Request $request)
    {
        $id=$request->id;
        
        $type=$request->type;
        
        $hardware = Hardware::findOrFail($id);
        
        if($type=='ok'){
            $hardware->is_manager_approve=1;
            $hardware->final_status='Manager Approve';
            $hardware->manager_note=$request->manager_note;
        }else{
            $hardware->is_manager_approve=0;
            $hardware->final_status='Manager Reject';
            $hardware->manager_note=$request->manager_note;
            $hardware->is_finish=0;
        }
        $hardware->manager_approval_date= Carbon::now();
        $hardware->save();
        return "Request is Saved!";        
    }

    /// ITD APPROVE ///

    public function show_it_approval()
    {
        return view('website.pages.hardware.approval_it');
    }

    public function show_it_approval_ajax(Request $request)
    {
        $data = Hardware::where('final_status','Manager Approve')
                        ->join('users', 'form_hardware.created_by', '=', 'users.id')
                        ->select('form_hardware.*', 'users.name as user_name');

        return DataTables::eloquent($data)->make(true);
    }

    public function show_data_it_approval()
    {
        $depts = Department::all();
        return view('website.pages.hardware.show_data_it_approval', compact(['depts']));
    }

    public function show_data_it_approval_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = Hardware::where('is_it_approve','1')
                        ->join('users', 'form_hardware.created_by', '=', 'users.id')
                        ->select('form_hardware.*', 'users.name as user_name');
        
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_it(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $hardware = Hardware::findOrFail($id);
        if($type=='ok'){
            $hardware->is_it_approve=1;
            $hardware->final_status='IT Approve';
            $hardware->it_note=$request->it_note;
        }else{
            $hardware->is_it_approve=0;
            $hardware->final_status='IT Reject';
            $hardware->it_note=$request->it_note;
            $hardware->is_finish=0;
        }
        $hardware->it_approval_date= Carbon::now();
        $hardware->save();
        return "Request is Saved!";
    }

    /// IT MGR ///
    public function show_it_mgr_approval()
    {
        return view('website.pages.hardware.approval_it_mgr');
    }

    public function show_it_mgr_approval_ajax(Request $request)
    {
        $data = Hardware::where('final_status','IT Approve')
                        ->join('users', 'form_hardware.created_by', '=', 'users.id')
                        ->select('form_hardware.*', 'users.name as user_name');
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_it_mgr(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $hardware = Hardware::findOrFail($id);
        if($type=='ok'){
            $hardware->is_it_mgr_approve=1;
            $hardware->final_status='IT MGR Approve';
            $hardware->it_mgr_note=$request->it_mgr_note;
        }else{
            $hardware->is_it_mgr_approve=0;
            $hardware->final_status='IT MGR Reject';
            $hardware->it_mgr_note=$request->it_mgr_note;
            $hardware->is_finish=0;
        }
        $hardware->it_mgr_approval_date= Carbon::now();
        $hardware->save();
        return "Request is Saved!";
    }

    public function show_data_it_mgr_approval()
    {
        $depts = Department::all();
        return view('website.pages.hardware.show_data_it_mgr_approval', compact(['depts']));
    }

    public function show_data_it_mgr_approval_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = Hardware::where('is_it_mgr_approve','1')
                        ->join('users', 'form_hardware.created_by', '=', 'users.id')
                        ->select('form_hardware.*', 'users.name as user_name');;
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    /// EXECUTION ///
    public function show_execution()
    {
        return view('website.pages.hardware.approval_execution');
    }

    public function show_execution_ajax(Request $request)
    {
        $data = Hardware::where('final_status','IT MGR Approve')
                        ->join('users', 'form_hardware.created_by', '=', 'users.id')
                        ->select('form_hardware.*', 'users.name as user_name');
                        
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_execution(Request $request)
    {
        $request->validate([
            'device_after' => 'required' ,
        ]);

        $id=$request->id;
        $type=$request->type;
        $hardware = Hardware::findOrFail($id);
        
        $user = $hardware->createdBy;

        if($type=='ok'){
            $isi = "FORM HARDWARE\n\n";
                
            $isi .= "Category : " . $hardware->category;
            $isi .= "\nType : " . $hardware->type;
            
            $isi .= "\n\nNPK : *" . $hardware->npk ."*";
            $isi .= "\nName : *" . $hardware->fullname ."*";
            $isi .= "\nDepartment : " . $hardware->department;
            $isi .= "\nPhone : " . $hardware->phone;
            $isi .= "\nDue date : " . $hardware->due_date;
            $isi .= "\nDevice Before : " . ($hardware->device_before ? $hardware->device_before : '-');
            $isi .= "\nDevice After : " . $request->device_after;
            $isi .= "\nPurpose : " . $hardware->purpose;
    
            $isi .= "\n\nStatus : Finished";
    
            $isi .= "\n\nManager Note : " . $hardware->manager_note;
            $isi .= "\nITD Note : " . $hardware->it_note;
            $isi .= "\nITD Manager Note : " . $hardware->it_mgr_note;
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

            $hardware->device_after=$request->device_after;
            $hardware->is_finish=1;
            $hardware->is_confirm=0;
            $hardware->final_status='Finished';
            $hardware->finish_note=$request->finish_note;
        }else{
            $hardware->is_finish=0;
            $hardware->final_status='Rejected';
            $hardware->finish_note=$request->finish_note;
        }
        $hardware->finish_date= Carbon::now();
        $hardware->save();

        return "Request is Saved!";
    }

    public function show_data_execution()
    {
        $depts = Department::all();
        return view('website.pages.hardware.show_data_execution', compact(['depts']));
    }

    public function show_data_execution_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = Hardware::where('is_finish','1')->orWhere('is_finish','0')
                        ->join('users', 'form_hardware.created_by', '=', 'users.id')
                        ->select('form_hardware.*', 'users.name as user_name');
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }
}
