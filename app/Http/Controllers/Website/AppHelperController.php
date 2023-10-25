<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AppHelper;

class AppHelperController extends Controller
{
    public function getAccountManagerCount()
    {
        $accountMgrCount = AppHelper::account_mgr_count();
        // $accountCount = AppHelper::account_it_count();
        // dd($accountCount);
        return response()->json(['account_mgr_count' => $accountMgrCount]);
    }
}
