<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Department;
use App\Models\Account;
use App\Models\FolderAccess;
use Auth;

class HomeController extends Controller
{
    public function index()
    {
        $account_mgr_count = Account::where('final_status', 'LIKE', '%created%')->where('created_dept', Auth::user()->dept_id)->count();
        $account_it_count = Account::where('final_status', 'LIKE', '%Manager Approve%')->where('created_dept', Auth::user()->dept_id)->count();
        $account_it_mgr_count = Account::where('final_status', 'LIKE', 'IT Approve%')->where('created_dept', Auth::user()->dept_id)->count();
        $account_execution_count = Account::where('final_status', 'LIKE', '%MGR IT Approve%')->where('created_dept', Auth::user()->dept_id)->count();

        $folderaccess_mgr_count = FolderAccess::where('final_status', 'LIKE', '%created%')->where('created_dept', Auth::user()->dept_id)->count();
        $folderaccess_it_count = FolderAccess::where('final_status', 'LIKE', '%Manager Approve%')->where('created_dept', Auth::user()->dept_id)->count();
        $folderaccess_it_mgr_count = FolderAccess::where('final_status', 'LIKE', 'IT Approve%')->where('created_dept', Auth::user()->dept_id)->count();
        $folderaccess_execution_count = FolderAccess::where('final_status', 'LIKE', '%MGR IT Approve%')->where('created_dept', Auth::user()->dept_id)->count();

        return view('website.pages.home', compact('account_mgr_count', 'account_it_count', 'account_it_mgr_count', 'account_execution_count',
        'folderaccess_mgr_count', 'folderaccess_it_count', 'folderaccess_it_mgr_count', 'folderaccess_execution_count'));
    }    
}
