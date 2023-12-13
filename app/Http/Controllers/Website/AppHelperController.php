<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AppHelper;

class AppHelperController extends Controller
{
    public function getApprovalCount()
    {
        $account_mgr_count = AppHelper::account_mgr_count();
        $account_it_count = AppHelper::account_it_count();
        $account_it_mgr_count = AppHelper::account_it_mgr_count();
        $account_execution_count = AppHelper::account_execution_count();

        $manager_approvals_count = AppHelper::manager_approvals_count();
        $confirms_count = AppHelper::confirms_count();
        $it_approvals_count = AppHelper::it_approvals_count();
        $it_mgr_approvals_count = AppHelper::it_mgr_approvals_count();
        $execution_count = AppHelper::execution_count();
        

        return response()->json([
        'account_mgr_count' => $account_mgr_count,
        'account_it_count' => $account_it_count,
        'account_it_mgr_count' => $account_it_mgr_count,
        'account_execution_count' => $account_execution_count,

        'manager_approvals_count' => $manager_approvals_count,
        'confirms_count' => $confirms_count,
        'it_approvals_count' => $it_approvals_count,
        'it_mgr_approvals_count' => $it_mgr_approvals_count,
        'execution_count' => $execution_count,
    ]);
    }
}
