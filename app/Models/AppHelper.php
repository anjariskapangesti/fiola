<?php

namespace App\Models;

use App\Models\Account;
use App\Models\FolderAccess;
use App\Models\NewFolder;
use Auth;


class AppHelper
{                 
    public static function manager_approvals_count()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        $account_mgr_count = Account::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();

        $folderaccess_mgr_count = FolderAccess::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                    $query->where('created_dept', $firstDepartmentId)
                                        ->orWhere('created_dept', $lastDepartmentId);
                                })
                                        ->where('final_status', 'LIKE', '%created%')->count();

        $newfolder_mgr_count = NewFolder::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                    $query->where('created_dept', $firstDepartmentId)
                                        ->orWhere('created_dept', $lastDepartmentId);
                                })
                                        ->where('final_status', 'LIKE', '%created%')->count();
        
        return $account_mgr_count + $folderaccess_mgr_count + $newfolder_mgr_count;
    }

    public static function it_approvals_count()
    {
        $account_it_count = Account::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $folderaccess_it_count = FolderAccess::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $newfolder_it_count = NewFolder::where('final_status', 'LIKE', '%Manager Approve%')->count();

        return $account_it_count + $folderaccess_it_count + $newfolder_it_count;
    }

    public static function it_mgr_approvals_count()
    {
        $account_it_mgr_count = Account::where('final_status', 'LIKE', 'IT Approve%')->count();
        $folderaccess_it_mgr_count = FolderAccess::where('final_status', 'LIKE', 'IT Approve%')->count();
        $newfolder_it_mgr_count = NewFolder::where('final_status', 'LIKE', 'IT Approve%')->count();

        return $account_it_mgr_count + $folderaccess_it_mgr_count + $newfolder_it_mgr_count;
    }

    public static function execution_count()
    {
        $account_execution_count = Account::where('final_status', 'LIKE', '%IT MGR Approve%')->count();
        $folderaccess_execution_count = FolderAccess::where('final_status', 'LIKE', '%IT MGR Approve%')->count();
        $newfolder_execution_count = NewFolder::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        return $account_execution_count + $folderaccess_execution_count + $newfolder_execution_count;
    }

    /// FORM ACCOUNT ///
    public static function account_mgr_count()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        return Account::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
    }

    public static function account_it_count()
    {
        return Account::where('final_status', 'LIKE', '%Manager Approve%')->count();
    }

    public static function account_it_mgr_count()
    {
        return Account::where('final_status', 'LIKE', 'IT Approve%')->count();
    }

    public static function account_execution_count()
    {
        return Account::where('final_status', 'LIKE', '%IT MGR Approve%')->count();
    }

    /// FORM FOLDER ACCESS ///
    public static function folderaccess_mgr_count()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        return FolderAccess::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
    }

    public static function folderaccess_it_count()
    {
        return FolderAccess::where('final_status', 'LIKE', '%Manager Approve%')->count();
    }

    public static function folderaccess_it_mgr_count()
    {
        return FolderAccess::where('final_status', 'LIKE', 'IT Approve%')->count();
    }

    public static function folderaccess_execution_count()
    {
        return FolderAccess::where('final_status', 'LIKE', '%IT MGR Approve%')->count();
    }

    /// FORM NEW FOLDER ///
    public static function newfolder_mgr_count()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        return NewFolder::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
    }

    public static function newfolder_it_count()
    {
        return NewFolder::where('final_status', 'LIKE', '%Manager Approve%')->count();
    }

    public static function newfolder_it_mgr_count()
    {
        return NewFolder::where('final_status', 'LIKE', 'IT Approve%')->count();
    }

    public static function newfolder_execution_count()
    {
        return NewFolder::where('final_status', 'LIKE', '%IT MGR Approve%')->count();
    }
}
