<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Department;
use App\Models\Account;
use Auth;

class HomeController extends Controller
{
    public function index()
    {
        $account_mgr_count = Account::where('final_status', 'LIKE', '%created%')->count();
        $account_it_count = Account::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $account_it_mgr_count = Account::where('final_status', 'LIKE', '%IT Approve%')->count();
        $account_execution_count = Account::where('final_status', 'LIKE', '%MGR IT Approve%')->count();

        return view('website.pages.home', compact('account_mgr_count', 'account_it_count', 'account_it_mgr_count', 'account_execution_count'));
    }    
}
