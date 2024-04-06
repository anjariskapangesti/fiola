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
    
        $models = [
            Account::class,
            FolderAccess::class,
            NewFolder::class,
            Software::class,
            Hardware::class,
            Vpn::class,
            Project::class,
            Fitur::class,
        ];
    
        $totalCount = 0;
    
        foreach ($models as $model) {
            $count = $model::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                            $query->where('created_dept', $firstDepartmentId)
                                ->orWhere('created_dept', $lastDepartmentId);
                        })
                        ->where('final_status', 'LIKE', '%created%')->count();
    
            $totalCount += $count;
        }
    
        return $totalCount;
    }

    public static function confirms_count()
    {
        $models = [
            Account::class,
            FolderAccess::class,
            NewFolder::class,
            Software::class,
            Hardware::class,
            Vpn::class,
            Project::class,
            Fitur::class,
        ];
    
        $totalCount = 0;
    
        foreach ($models as $model) {
            $count = $model::where('created_by', Auth::user()->id)
                            ->where('is_confirm', 'LIKE', '%0%')->count();
    
            $totalCount += $count;
        }
    
        return $totalCount;
    }

    public static function it_approvals_count()
    {
        $account_it_count = Account::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $folderaccess_it_count = FolderAccess::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $newfolder_it_count = NewFolder::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $software_it_count = Software::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $hardware_it_count = Hardware::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $vpn_it_count = Vpn::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $project_it_count = Project::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $fitur_it_count = Fitur::where('final_status', 'LIKE', '%Manager Approve%')->count();

        return $account_it_count + $folderaccess_it_count + $newfolder_it_count + $software_it_count + $hardware_it_count + $vpn_it_count + $project_it_count + $fitur_it_count;
    }

    public static function it_mgr_approvals_count()
    {
        $account_it_mgr_count = Account::where('final_status', 'LIKE', 'IT Approve%')->count();
        $folderaccess_it_mgr_count = FolderAccess::where('final_status', 'LIKE', 'IT Approve%')->count();
        $newfolder_it_mgr_count = NewFolder::where('final_status', 'LIKE', 'IT Approve%')->count();
        $software_it_mgr_count = Software::where('final_status', 'LIKE', 'IT Approve%')->count();
        $hardware_it_mgr_count = Hardware::where('final_status', 'LIKE', 'IT Approve%')->count();
        $vpn_it_mgr_count = Vpn::where('final_status', 'LIKE', 'IT Approve%')->count();
        $project_it_mgr_count = Project::where('final_status', 'LIKE', 'IT Approve%')->count();
        $fitur_it_mgr_count = Fitur::where('final_status', 'LIKE', 'IT Approve%')->count();

        return $account_it_mgr_count + $folderaccess_it_mgr_count + $newfolder_it_mgr_count + $software_it_mgr_count + $hardware_it_mgr_count + $vpn_it_mgr_count + $project_it_mgr_count + $fitur_it_mgr_count;
    }

    public static function execution_count()
    {
        $account_execution_count = Account::where(function($query) {
            $query->where('final_status', 'LIKE', '%IT MGR Approve%')
                  ->orWhere('final_status', 'LIKE', '%On Progress%');
        })->count();
        $folderaccess_execution_count = FolderAccess::where('final_status', 'LIKE', '%IT MGR Approve%')->count();
        $newfolder_execution_count = NewFolder::where('final_status', 'LIKE', '%IT MGR Approve%')->count();
        $software_execution_count = Software::where('final_status', 'LIKE', '%IT MGR Approve%')->count();
        $hardware_execution_count = Hardware::where('final_status', 'LIKE', '%IT MGR Approve%')->count();
        $vpn_execution_count = Vpn::where('final_status', 'LIKE', '%IT MGR Approve%')->count();
        $project_execution_count = Project::where(function($query) {
            $query->where('final_status', 'LIKE', '%IT MGR Approve%')
                  ->orWhere('final_status', 'LIKE', '%On Progress%')
                  ->orWhere('final_status', 'LIKE', '%On Progress%');
        })->count();
        $fitur_execution_count = Fitur::where(function($query) {
            $query->where('final_status', 'LIKE', '%IT MGR Approve%')
                  ->orWhere('final_status', 'LIKE', '%On Progress%')
                  ->orWhere('final_status', 'LIKE', '%On Progress%');
        })->count();

        return $account_execution_count + $folderaccess_execution_count + $newfolder_execution_count + $software_execution_count + $hardware_execution_count + $vpn_execution_count + $project_execution_count + $fitur_execution_count;
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
        return Account::whereIn('final_status', ['IT MGR Approve', 'On Progress'])->count();
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
        return FolderAccess::whereIn('final_status', ['IT MGR Approve', 'On Progress'])->count();
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
        return NewFolder::whereIn('final_status', ['IT MGR Approve', 'On Progress'])->count();
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
        return Software::whereIn('final_status', ['IT MGR Approve', 'On Progress'])->count();
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
        return Hardware::whereIn('final_status', ['IT MGR Approve', 'On Progress'])->count();
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
        return Vpn::whereIn('final_status', ['IT MGR Approve', 'On Progress'])->count();
    }

    /// FORM PROJECT ///
    public static function project_mgr_count()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        return Project::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
    }

    public static function project_confirm_count()
    {
        return Project::where('created_by', Auth::user()->id)
                        ->where('is_confirm', 'LIKE', '%0%')->count();
    }

    public static function project_it_count()
    {
        return Project::where('final_status', 'LIKE', '%Manager Approve%')->count();
    }

    public static function project_it_mgr_count()
    {
        return Project::where('final_status', 'LIKE', 'IT Approve%')->count();
    }

    public static function project_execution_count()
    {
        return Project::whereIn('final_status', ['IT MGR Approve', 'On Progress'])->count();
    }

    /// FORM FITUR ///
    public static function fitur_mgr_count()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        return Fitur::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
    }

    public static function fitur_confirm_count()
    {
        return Fitur::where('created_by', Auth::user()->id)
                        ->where('is_confirm', 'LIKE', '%0%')->count();
    }

    public static function fitur_it_count()
    {
        return Fitur::where('final_status', 'LIKE', '%Manager Approve%')->count();
    }

    public static function fitur_it_mgr_count()
    {
        return Fitur::where('final_status', 'LIKE', 'IT Approve%')->count();
    }

    public static function fitur_execution_count()
    {
        return Fitur::whereIn('final_status', ['IT MGR Approve', 'On Progress'])->count();
    }
}
