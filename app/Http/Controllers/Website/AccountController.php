<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Account;
use App\Models\Department;
use App\Models\User;

use Carbon\Carbon;
use DataTables;
use Auth;

class AccountController extends Controller
{
    public function create()
    {
        $depts = Department::all();
        return view('website.pages.account.create', compact(['depts']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'budget_type' => 'required' ,
            'form_type' => 'required' ,
            'npk' => 'required' ,
            'fullname' => 'required' ,
            'department' => 'required' ,
            'phone' => 'required' ,
            'purpose' => 'required' ,
            'ad_name' => 'required' ,
            'is_email' => 'required' ,
        ]);
        if($request->is_email==false){
            $request->is_email = 0;
        }else{
            $request->is_email = 1;
        }
        try
        {
            Account::create([
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
                'created_dept' => Auth::user()->dept_id,
                'final_status' => 'created'
            ]);
            $depts = Department::all();
            return redirect()->back()->with('success', 'Success Create Form');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function show_manager_approval()
    {
        return view('website.pages.account.approval_manager');
    }

    public function show_manager_approval_ajax(Request $request)
    {
        // return Auth::user()->dept_id;
        $data = Account::where('created_dept', Auth::user()->dept_id)->where('final_status','created');
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
        $data = Account::where('created_dept', Auth::user()->dept_id)->where('is_manager_approve','1');
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
        }
        $account->manager_approval_date= Carbon::now();
        $account->save();
        return view('website.pages.account.approval_manager')->with('success', 'Request is Successfully Updated!');
    }

    /// ITD APPROVE ///

    public function show_it_approval()
    {
        return view('website.pages.account.approval_it');
    }

    public function show_it_approval_ajax(Request $request)
    {
        $data = Account::where('final_status','Manager Approve');
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
        $data = Account::where('created_dept', Auth::user()->dept_id)->where('is_it_approve','1');
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
        }
        $account->it_approval_date= Carbon::now();
        $account->save();
        return view('website.pages.account.approval_it')->with('success', 'Request is Successfully Updated!');
    }

    /// MGR IT ///
    public function show_mgr_it_approval()
    {
        return view('website.pages.account.approval_mgr_it');
    }

    public function show_mgr_it_approval_ajax(Request $request)
    {
        $data = Account::where('final_status','IT Approve');
        return DataTables::eloquent($data)->make(true);
    }

    public function approve_mgr_it(Request $request)
    {
        $id=$request->id;
        $type=$request->type;
        $account = Account::findOrFail($id);
        if($type=='ok'){
            $account->is_it_mgr_approve=1;
            $account->final_status='MGR IT Approve';
        }else{
            $account->is_it_mgr_approve=0;
            $account->final_status='MGR IT Reject';
        }
        $account->it_mgr_approval_date= Carbon::now();
        $account->save();
        return view('website.pages.account.approval_mgr_it')->with('success', 'Request is Successfully Updated!');
    }

}
