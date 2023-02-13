<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Department;
use DataTables;
use Carbon\Carbon;

class AccountController extends Controller
{
    public  function create()
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
            ]);

            return view('website.pages.account.create')->with('success', 'Form Successfully Submitted!');
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
        $data = Account::where('final_status','created');
        return DataTables::eloquent($data)->make(true);
    }

    public function approve(Request $request)
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

}
