<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Account;
use App\Models\Department;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

class AccountController extends Controller
{
    public function create()
    {
        $departments = Department::orderBy('name')->get();

        $userDepartment = Auth::user()->createdDepartments;
        
        return view('website.pages.account.create', compact(['departments', 'userDepartment']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_reg' => 'unique',
            'budget_type' => 'required' ,
            'form_type' => 'required' ,
            'npk' => 'required' ,
            'fullname' => 'required' ,
            'department' => 'required' ,
            'phone' => 'required' ,
            'purpose' => 'required' ,
            'ad_name' => 'required' ,
        ]);

        $year = date('y');
        $month = date('m');
        $lastForm = DB::table('form_account')
                      ->select('no_reg')
                      ->orderBy('no_reg', 'desc')
                      ->first();
        $lastNumber = ($lastForm) ? substr($lastForm->no_reg, -3) : '000';
        
        $lastMonth = ($lastForm) ? substr($lastForm->no_reg, 6, 2) : '00';            
        if ($lastMonth !== $month){
            $lastNumber = '000';
        }            
        $newNumber = str_pad((intval($lastNumber) + 1), strlen($lastNumber), '0', STR_PAD_LEFT);            
        $no_reg = 'ACC/' . $year . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        if($request->is_email==false){
            $request->is_email = 0;
        }else{
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

        try
        {
            $form_account = Account::create([
                'no_reg' => $no_reg,
                'budget_type' => $request->budget_type ,
                'form_type' => $request->form_type ,
                'npk' => $request->npk ,
                'fullname' => $request->fullname ,
                'department' => $request->department ,
                'phone' => $request->phone ,
                'company' => $request->company ,
                'expired_date' => $request->expired_date ,
                'purpose' => $request->purpose ,
                'ad_name' => $request->ad_name ,
                'is_email' => $request->is_email ,
                'created_by' => Auth::user()->id,
                // 'created_dept' => Auth::user()->dept_id,
                'created_dept' => Auth::user()->departments->pluck('id')->first(),
                'final_status' => $finalStatus,
                'is_manager_approve' => $isManagerApprove,
                'manager_approval_date' => $managerApprovalDate,            
            ]);

            
            $form_account->save();

            $depts = Department::all();
            return redirect()->back()->with('success', 'Success Create Form');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function show_data_form()
    {
        $depts = Department::all();
        return view('website.pages.account.show_data_form', compact(['depts']));
    }

    public function show_data_form_ajax(Request $request)
    {
        
        $data = Account::where('created_by', Auth::user()->id)
                        ->join('users', 'form_account.created_by', '=', 'users.id')
                        ->select('form_account.*', 'users.name as user_name');

        return DataTables::eloquent($data)->make(true);
    }

    // MGR //

    public function show_manager_approval()
    {
        return view('website.pages.account.approval_manager');
    }

    public function show_manager_approval_ajax(Request $request)
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();
        
        $data = Account::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
        ->where('final_status', 'created')
        ->join('users', 'form_account.created_by', '=', 'users.id')
        ->select('form_account.*', 'users.name as user_name');
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    public function show_data_manager_approval()
    {
        $depts = Department::all();
        return view('website.pages.account.show_data_manager_approval', compact(['depts']));
    }

    public function show_data_manager_approval_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();
        
        $data = Account::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
        ->where('is_manager_approve','1')
        ->join('users', 'form_account.created_by', '=', 'users.id')
        ->select('form_account.*', 'users.name as user_name');
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_manager(Request $request)
    {
        $id=$request->id;
        
        $type=$request->type;
        
        $account = Account::findOrFail($id);
        
        if($type=='ok'){
            $account->is_manager_approve=1;
            $account->final_status='Manager Approve';
        }else{
            $account->is_manager_approve=0;
            $account->final_status='Manager Reject';
            $account->manager_note=$request->manager_note;
        }
        $account->manager_approval_date= Carbon::now();
        $account->save();
        return "Request is Saved!";        
    }

    /// ITD APPROVE ///

    public function show_it_approval()
    {
        return view('website.pages.account.approval_it');
    }

    public function show_it_approval_ajax(Request $request)
    {
        $data = Account::where('final_status','Manager Approve')
                        ->join('users', 'form_account.created_by', '=', 'users.id')
                        ->select('form_account.*', 'users.name as user_name');

        return DataTables::eloquent($data)->make(true);
    }

    public function show_data_it_approval()
    {
        $depts = Department::all();
        return view('website.pages.account.show_data_it_approval', compact(['depts']));
    }

    public function show_data_it_approval_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = Account::where('is_it_approve','1')
                        ->join('users', 'form_account.created_by', '=', 'users.id')
                        ->select('form_account.*', 'users.name as user_name');
        
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_it(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $account = Account::findOrFail($id);
        if($type=='ok'){
            $account->is_it_approve=1;
            $account->final_status='IT Approve';
        }else{
            $account->is_it_approve=0;
            $account->final_status='IT Reject';
            $account->it_note=$request->it_note;
        }
        $account->it_approval_date= Carbon::now();
        $account->save();
        return "Request is Saved!";
    }

    /// IT MGR ///
    public function show_it_mgr_approval()
    {
        return view('website.pages.account.approval_it_mgr');
    }

    public function show_it_mgr_approval_ajax(Request $request)
    {
        $data = Account::where('final_status','IT Approve')
                        ->join('users', 'form_account.created_by', '=', 'users.id')
                        ->select('form_account.*', 'users.name as user_name');
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_it_mgr(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $account = Account::findOrFail($id);
        if($type=='ok'){
            $account->is_it_mgr_approve=1;
            $account->final_status='IT MGR Approve';
        }else{
            $account->is_it_mgr_approve=0;
            $account->final_status='IT MGR Reject';
            $account->it_mgr_note=$request->it_mgr_note;
        }
        $account->it_mgr_approval_date= Carbon::now();
        $account->save();
        return "Request is Saved!";
    }

    public function show_data_it_mgr_approval()
    {
        $depts = Department::all();
        return view('website.pages.account.show_data_it_mgr_approval', compact(['depts']));
    }

    public function show_data_it_mgr_approval_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = Account::where('is_it_mgr_approve','1')
                        ->join('users', 'form_account.created_by', '=', 'users.id')
                        ->select('form_account.*', 'users.name as user_name');;
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

    /// EXECUTION ///
    public function show_execution()
    {
        return view('website.pages.account.approval_execution');
    }

    public function show_execution_ajax(Request $request)
    {
        $data = Account::where('final_status','IT MGR Approve')
                        ->join('users', 'form_account.created_by', '=', 'users.id')
                        ->select('form_account.*', 'users.name as user_name');
                        
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_execution(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $account = Account::findOrFail($id);
        if($type=='ok'){
            $account->is_finish=1;
            $account->final_status='Finished';
        }else{
            $account->is_finish=0;
            $account->final_status='Rejected';
            $account->finish_note=$request->finish_note;
        }
        $account->finish_date= Carbon::now();
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
        // return Auth::user()->dept_id;
        $data = Account::where('is_finish','1')
                        ->join('users', 'form_account.created_by', '=', 'users.id')
                        ->select('form_account.*', 'users.name as user_name');
        // return $data;
        return DataTables::eloquent($data)->make(true);
    }

}
