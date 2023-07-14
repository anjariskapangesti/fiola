<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Department;
use App\Models\Account;
use App\Models\FolderAccess;
use App\Models\NewFolder;
use Auth;

class HomeController extends Controller
{
    public function index()
    {       
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $account_mgr_count = Account::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
                                    
        $account_it_count = Account::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $account_it_mgr_count = Account::where('final_status', 'LIKE', 'IT Approve%')->count();
        $account_execution_count = Account::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $folderaccess_mgr_count = FolderAccess::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                    $query->where('created_dept', $firstDepartmentId)
                                        ->orWhere('created_dept', $lastDepartmentId);
                                })
                                        ->where('final_status', 'LIKE', '%created%')->count();

        $folderaccess_it_count = FolderAccess::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $folderaccess_it_mgr_count = FolderAccess::where('final_status', 'LIKE', 'IT Approve%')->count();
        $folderaccess_execution_count = FolderAccess::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $newfolder_mgr_count = NewFolder::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
                                    
        $newfolder_it_count = NewFolder::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $newfolder_it_mgr_count = NewFolder::where('final_status', 'LIKE', 'IT Approve%')->count();
        $newfolder_execution_count = NewFolder::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        return view('website.pages.home', 
        compact('account_mgr_count', 'account_it_count', 'account_it_mgr_count', 'account_execution_count',
                'folderaccess_mgr_count', 'folderaccess_it_count', 'folderaccess_it_mgr_count', 'folderaccess_execution_count',
                'newfolder_mgr_count', 'newfolder_it_count', 'newfolder_it_mgr_count', 'newfolder_execution_count'));
    }    
}
