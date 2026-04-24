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

        return Project::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
                $query->where('created_dept', $firstDepartmentId)
                    ->orWhere('created_dept', $lastDepartmentId);
            })
            ->where('final_status', 'LIKE', '%created%')->count();
    }

    public static function ticket_count()
    {
        return Ticket::whereNull('is_finish')->count();
    }

    public static function project_mgr_count()
    {
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();

        return Project::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
            ->where('final_status', 'LIKE', '%created%')->count();
    }

    public static function project_it_count()
    {
        return 0;
    }

    public static function project_it_mgr_count()
    {
        return 0;
    }

    public static function project_execution_count()
    {
        return Project::where('final_status', 'Finished')->count();
    }

    public static function project_dir_count()
    {
        return Project::where('final_status', 'Manager Approve')->count();
    }

    public static function project_reschedule_notifications_count()
    {
        $myProjectsIds = Project::where('created_by', Auth::user()->id)->pluck('id');
        return Project::whereIn('reschedule_target_id', $myProjectsIds)
            ->where('target_response', 'pending')
            ->count();
    }
}
