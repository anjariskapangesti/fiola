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

        $software_mgr_count = Software::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                    $query->where('created_dept', $firstDepartmentId)
                                        ->orWhere('created_dept', $lastDepartmentId);
                                })
                                        ->where('final_status', 'LIKE', '%created%')->count();

        $hardware_mgr_count = Hardware::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                    $query->where('created_dept', $firstDepartmentId)
                                        ->orWhere('created_dept', $lastDepartmentId);
                                })
                                        ->where('final_status', 'LIKE', '%created%')->count();

        $vpn_mgr_count = Vpn::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                    $query->where('created_dept', $firstDepartmentId)
                                        ->orWhere('created_dept', $lastDepartmentId);
                                })
                                        ->where('final_status', 'LIKE', '%created%')->count();
        
        return $account_mgr_count + $folderaccess_mgr_count + $newfolder_mgr_count + $software_mgr_count + $hardware_mgr_count + $vpn_mgr_count;
    }

    public static function confirms_count()
    {
        $account_confirm_count = Account::where('created_by', Auth::user()->id)
                                        ->where('is_confirm', 'LIKE', '%0%')->count();

        $folderaccess_confirm_count = FolderAccess::where('created_by', Auth::user()->id)
                                                    ->where('is_confirm', 'LIKE', '%0%')->count();

        $newfolder_confirm_count = NewFolder::where('created_by', Auth::user()->id)
                                            ->where('is_confirm', 'LIKE', '%0%')->count();

        $software_confirm_count = Software::where('created_by', Auth::user()->id)
                                            ->where('is_confirm', 'LIKE', '%0%')->count();
        
        $hardware_confirm_count = Hardware::where('created_by', Auth::user()->id)
                                            ->where('is_confirm', 'LIKE', '%0%')->count();

        $vpn_confirm_count = Vpn::where('created_by', Auth::user()->id)
                                            ->where('is_confirm', 'LIKE', '%0%')->count();
                                            
        return $account_confirm_count + $folderaccess_confirm_count + $newfolder_confirm_count + $software_confirm_count + $hardware_confirm_count + $vpn_confirm_count;
    }

    public static function it_approvals_count()
    {
        $account_it_count = Account::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $folderaccess_it_count = FolderAccess::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $newfolder_it_count = NewFolder::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $software_it_count = Software::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $hardware_it_count = Hardware::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $vpn_it_count = Vpn::where('final_status', 'LIKE', '%Manager Approve%')->count();

        return $account_it_count + $folderaccess_it_count + $newfolder_it_count + $software_it_count + $hardware_it_count + $vpn_it_count;
    }

    public static function it_mgr_approvals_count()
    {
        $account_it_mgr_count = Account::where('final_status', 'LIKE', 'IT Approve%')->count();
        $folderaccess_it_mgr_count = FolderAccess::where('final_status', 'LIKE', 'IT Approve%')->count();
        $newfolder_it_mgr_count = NewFolder::where('final_status', 'LIKE', 'IT Approve%')->count();
        $software_it_mgr_count = Software::where('final_status', 'LIKE', 'IT Approve%')->count();
        $hardware_it_mgr_count = Hardware::where('final_status', 'LIKE', 'IT Approve%')->count();
        $vpn_it_mgr_count = Vpn::where('final_status', 'LIKE', 'IT Approve%')->count();

        return $account_it_mgr_count + $folderaccess_it_mgr_count + $newfolder_it_mgr_count + $software_it_mgr_count + $hardware_it_mgr_count + $vpn_it_mgr_count;
    }

    public static function execution_count()
    {
        $account_execution_count = Account::where('final_status', 'LIKE', '%IT MGR Approve%')->count();
        $folderaccess_execution_count = FolderAccess::where('final_status', 'LIKE', '%IT MGR Approve%')->count();
        $newfolder_execution_count = NewFolder::where('final_status', 'LIKE', '%IT MGR Approve%')->count();
        $software_execution_count = Software::where('final_status', 'LIKE', '%IT MGR Approve%')->count();
        $hardware_execution_count = Hardware::where('final_status', 'LIKE', '%IT MGR Approve%')->count();
        $vpn_execution_count = Vpn::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        return $account_execution_count + $folderaccess_execution_count + $newfolder_execution_count + $software_execution_count + $hardware_execution_count + $vpn_execution_count;
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

    public static function account_confirm_count()
    {
        return Account::where('created_by', Auth::user()->id)
                        ->where('is_confirm', 'LIKE', '%0%')->count();
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

    public static function folderaccess_confirm_count()
    {
        return FolderAccess::where('created_by', Auth::user()->id)
                        ->where('is_confirm', 'LIKE', '%0%')->count();
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

    public static function newfolder_confirm_count()
    {
        return NewFolder::where('created_by', Auth::user()->id)
                        ->where('is_confirm', 'LIKE', '%0%')->count();
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

    /// FORM SOFTWARE ///
    public static function software_mgr_count()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        return Software::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
    }

    public static function software_confirm_count()
    {
        return Software::where('created_by', Auth::user()->id)
                        ->where('is_confirm', 'LIKE', '%0%')->count();
    }

    public static function software_it_count()
    {
        return Software::where('final_status', 'LIKE', '%Manager Approve%')->count();
    }

    public static function software_it_mgr_count()
    {
        return Software::where('final_status', 'LIKE', 'IT Approve%')->count();
    }

    public static function software_execution_count()
    {
        return Software::where('final_status', 'LIKE', '%IT MGR Approve%')->count();
    }

    /// FORM HARDWARE ///
    public static function hardware_mgr_count()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        return Hardware::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
    }

    public static function hardware_confirm_count()
    {
        return Hardware::where('created_by', Auth::user()->id)
                        ->where('is_confirm', 'LIKE', '%0%')->count();
    }

    public static function hardware_it_count()
    {
        return Hardware::where('final_status', 'LIKE', '%Manager Approve%')->count();
    }

    public static function hardware_it_mgr_count()
    {
        return Hardware::where('final_status', 'LIKE', 'IT Approve%')->count();
    }

    public static function hardware_execution_count()
    {
        return Hardware::where('final_status', 'LIKE', '%IT MGR Approve%')->count();
    }

    /// FORM VPN ///
    public static function vpn_mgr_count()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        return Vpn::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
    }

    public static function vpn_confirm_count()
    {
        return Vpn::where('created_by', Auth::user()->id)
                        ->where('is_confirm', 'LIKE', '%0%')->count();
    }

    public static function vpn_it_count()
    {
        return Vpn::where('final_status', 'LIKE', '%Manager Approve%')->count();
    }

    public static function vpn_it_mgr_count()
    {
        return Vpn::where('final_status', 'LIKE', 'IT Approve%')->count();
    }

    public static function vpn_execution_count()
    {
        return Vpn::where('final_status', 'LIKE', '%IT MGR Approve%')->count();
    }
}
