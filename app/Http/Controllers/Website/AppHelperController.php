<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\AppHelper;

class AppHelperController extends Controller
{
    public function getApprovalCount()
    {
        return response()->json([
            'project_mgr_count' => AppHelper::project_mgr_count(),
            'project_dir_count' => AppHelper::project_dir_count(),
            'project_reschedule_notifications_count' => AppHelper::project_reschedule_notifications_count(),
            'manager_approvals_count' => AppHelper::manager_approvals_count(),
        ]);
    }
}
