<?php

namespace App\Models;

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
            Relayout::class,
            Network::class,
            AksesSistem::class,
            IncidentReport::class,
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

    public static function gm_approvals_count()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();
    
        $models = [
            AksesSistem::class,
        ];
    
        $totalCount = 0;
    
        foreach ($models as $model) {
            $count = $model::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                            $query->where('created_dept', $firstDepartmentId)
                                ->orWhere('created_dept', $lastDepartmentId);
                        })
                        ->where('final_status', 'LIKE', '%Manager Approve%')->count();
    
            $totalCount += $count;
        }
    
        return $totalCount;
    }

    public static function ticket_count()
    {
        return Ticket::whereNull('is_finish')->count();
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
            Relayout::class,
            Network::class,
            AksesSistem::class,
        ];
    
        $totalCount = 0;
    
        foreach ($models as $model) {
            $count = $model::where('created_by', Auth::user()->id)
                            ->where('is_confirm', 'false')->count();
    
            $totalCount += $count;
        }
    
        return $totalCount;
    }

    public static function it_approvals_count()
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
            Relayout::class,
            Network::class,
            AksesSistem::class,
            IncidentReport::class,
        ];
    
        $totalCount = 0;
    
        foreach ($models as $model) {
            $count = $model::where('final_status', 'Manager Approve')->count();
    
            $totalCount += $count;
        }
    
        return $totalCount;
    }

    public static function it_mgr_approvals_count()
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
            Relayout::class,
            Network::class,
            AksesSistem::class,
            IncidentReport::class,
        ];
    
        $totalCount = 0;
    
        foreach ($models as $model) {
            $count = $model::where('final_status', 'IT Approve')->count();
    
            $totalCount += $count;
        }
    
        return $totalCount;
    }

    public static function execution_count()
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
            Relayout::class,
            Network::class,
            AksesSistem::class,
            IncidentReport::class,
        ];
    
        $totalCount = 0;
    
        foreach ($models as $model) {
            $count = $model::whereIn('final_status', ['IT MGR Approve', 'On Progress'])->count();
    
            $totalCount += $count;
        }
    
        return $totalCount;
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
                        ->where('is_confirm', 'false')->count();
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
                        ->where('is_confirm', 'LIKE', 'false')->count();
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
                        ->where('is_confirm', 'LIKE', 'false')->count();
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
                        ->where('is_confirm', 'LIKE', 'false')->count();
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
                        ->where('is_confirm', 'LIKE', 'false')->count();
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
                        ->where('is_confirm', 'LIKE', 'false')->count();
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
                        ->where('is_confirm', 'LIKE', 'false')->count();
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
                        ->where('is_confirm', 'LIKE', 'false')->count();
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

    /// FORM RELAYOUT ///
    public static function relayout_mgr_count()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        return Relayout::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
    }

    public static function relayout_confirm_count()
    {
        return Relayout::where('created_by', Auth::user()->id)
                        ->where('is_confirm', 'LIKE', 'false')->count();
    }

    public static function relayout_it_count()
    {
        return Relayout::where('final_status', 'LIKE', '%Manager Approve%')->count();
    }

    public static function relayout_it_mgr_count()
    {
        return Relayout::where('final_status', 'LIKE', 'IT Approve%')->count();
    }

    public static function relayout_execution_count()
    {
        return Relayout::whereIn('final_status', ['IT MGR Approve', 'On Progress'])->count();
    }

    /// FORM NETWORK ///
    public static function network_mgr_count()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        return Network::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
    }

    public static function network_confirm_count()
    {
        return Network::where('created_by', Auth::user()->id)
                        ->where('is_confirm', 'LIKE', 'false')->count();
    }

    public static function network_it_count()
    {
        return Network::where('final_status', 'LIKE', '%Manager Approve%')->count();
    }

    public static function network_it_mgr_count()
    {
        return Network::where('final_status', 'LIKE', 'IT Approve%')->count();
    }

    public static function network_execution_count()
    {
        return Network::whereIn('final_status', ['IT MGR Approve', 'On Progress'])->count();
    }

    /// FORM AKSES SISTEM ///
    public static function akses_sistem_mgr_count()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        return AksesSistem::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
    }

    public static function akses_sistem_gm_count()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        return AksesSistem::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%Manager Approve%')->count();
    }

    public static function akses_sistem_dir_count()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        return AksesSistem::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%GM Approve%')->count();
    }

    public static function akses_sistem_confirm_count()
    {
        return AksesSistem::where('created_by', Auth::user()->id)
                        ->where('is_confirm', 'false')->count();
    }

    public static function akses_sistem_it_count()
    {
        return AksesSistem::where('final_status', 'LIKE', '%DIR Approve%')->count();
    }

    public static function akses_sistem_it_mgr_count()
    {
        return AksesSistem::where('final_status', 'LIKE', 'IT Approve%')->count();
    }

    public static function akses_sistem_execution_count()
    {
        return AksesSistem::whereIn('final_status', ['IT MGR Approve', 'On Progress'])->count();
    }

    /// FORM INCIDENT REPORT ///
    public static function incident_report_mgr_count()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        return IncidentReport::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
    }

    public static function incident_report_gm_count()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        return IncidentReport::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%Manager Approve%')->count();
    }

    public static function incident_report_dir_count()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        return IncidentReport::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%GM Approve%')->count();
    }

    public static function incident_report_confirm_count()
    {
        return IncidentReport::where('created_by', Auth::user()->id)
                        ->where('is_confirm', 'false')->count();
    }

    public static function incident_report_it_count()
    {
        return IncidentReport::where('final_status', 'LIKE', '%DIR Approve%')->count();
    }

    public static function incident_report_it_mgr_count()
    {
        return IncidentReport::where('final_status', 'LIKE', 'IT Approve%')->count();
    }

    public static function incident_report_execution_count()
    {
        return IncidentReport::whereIn('final_status', ['IT MGR Approve', 'On Progress'])->count();
    }
}
