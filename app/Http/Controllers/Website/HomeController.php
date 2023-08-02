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

        $account_create_finished = Account::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'Finished')
                                        ->count();

        $folderaccess_create_finished = FolderAccess::where('created_by', Auth::user()->id)
                                                ->where('final_status', 'Finished')
                                                ->count();

        $newfolder_create_finished = NewFolder::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'Finished')
                                        ->count();

        $total_form_finished = $account_create_finished + $folderaccess_create_finished + $newfolder_create_finished;

        $account_create_rejected = Account::where('created_by', Auth::user()->id)
                                          ->where(function ($query) {
                                              $query->where('final_status', 'LIKE', '%Rejected%')
                                                  ->orWhere('final_status', 'LIKE', '%Manager Reject%')
                                                  ->orWhere('final_status', 'LIKE', '%IT Reject%')
                                                  ->orWhere('final_status', 'LIKE', '%IT MGR Reject%');
                                          })
                                          ->count();

        $folderaccess_create_rejected = FolderAccess::where('created_by', Auth::user()->id)
                                                    ->where(function ($query) {
                                                        $query->where('final_status', 'LIKE', '%Rejected%')
                                                            ->orWhere('final_status', 'LIKE', '%Manager Reject%')
                                                            ->orWhere('final_status', 'LIKE', '%IT Reject%')
                                                            ->orWhere('final_status', 'LIKE', '%IT MGR Reject%');
                                                    })
                                                    ->count();

        $newfolder_create_rejected = NewFolder::where('created_by', Auth::user()->id)
                                              ->where(function ($query) {
                                                  $query->where('final_status', 'LIKE', '%Rejected%')
                                                      ->orWhere('final_status', 'LIKE', '%Manager Reject%')
                                                      ->orWhere('final_status', 'LIKE', '%IT Reject%')
                                                      ->orWhere('final_status', 'LIKE', '%IT MGR Reject%');
                                              })
                                              ->count();

        $total_form_rejected = $account_create_rejected + $folderaccess_create_rejected + $newfolder_create_rejected;
        
        $account_create_mgr = Account::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'created')
                                        ->count();

        $folderaccess_create_mgr = FolderAccess::where('created_by', Auth::user()->id)
                                                ->where('final_status', 'created')
                                                ->count();

        $newfolder_create_mgr = NewFolder::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'created')
                                        ->count();

        $total_form_mgr = $account_create_mgr + $folderaccess_create_mgr + $newfolder_create_mgr;

        $account_create_it = Account::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'Manager Approve')
                                        ->count();

        $folderaccess_create_it = FolderAccess::where('created_by', Auth::user()->id)
                                                ->where('final_status', 'Manager Approve')
                                                ->count();

        $newfolder_create_it = NewFolder::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'Manager Approve')
                                        ->count();

        $total_form_it = $account_create_it + $folderaccess_create_it + $newfolder_create_it;

        $account_create_it_mgr = Account::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'IT Approve')
                                        ->count();

        $folderaccess_create_it_mgr = FolderAccess::where('created_by', Auth::user()->id)
                                                ->where('final_status', 'IT Approve')
                                                ->count();

        $newfolder_create_it_mgr = NewFolder::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'IT Approve')
                                        ->count();

        $total_form_it_mgr = $account_create_it_mgr + $folderaccess_create_it_mgr + $newfolder_create_it_mgr;

        $account_create_execution = Account::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'IT MGR Approve')
                                        ->count();

        $folderaccess_create_execution = FolderAccess::where('created_by', Auth::user()->id)
                                                ->where('final_status', 'IT MGR Approve')
                                                ->count();

        $newfolder_create_execution = NewFolder::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'IT MGR Approve')
                                        ->count();

        $total_form_execution = $account_create_execution + $folderaccess_create_execution + $newfolder_create_execution;


        /// untuk master ///
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

        $account_total = Account::count();
        $account_finished = Account::where('final_status', 'LIKE', '%Finished%')->count();
        $account_rejected = Account::where('final_status', 'LIKE', '%Rejected%')
                                ->orWhere('final_status', 'LIKE', '%Manager Reject%')
                                ->orWhere('final_status', 'LIKE', '%IT Reject%')
                                ->orWhere('final_status', 'LIKE', '%IT MGR Reject%')
                                ->count();

        $folderaccess_total = FolderAccess::count();
        $folderaccess_finished = FolderAccess::where('final_status', 'LIKE', '%Finished%')->count();
        $folderaccess_rejected = FolderAccess::where('final_status', 'LIKE', '%Rejected%')
                                ->orWhere('final_status', 'LIKE', '%Manager Reject%')
                                ->orWhere('final_status', 'LIKE', '%IT Reject%')
                                ->orWhere('final_status', 'LIKE', '%IT MGR Reject%')
                                ->count();

        $newfolder_total = NewFolder::count();
        $newfolder_finished = NewFolder::where('final_status', 'LIKE', '%Finished%')->count();
        $newfolder_rejected = NewFolder::where('final_status', 'LIKE', '%Rejected%')
                                ->orWhere('final_status', 'LIKE', '%Manager Reject%')
                                ->orWhere('final_status', 'LIKE', '%IT Reject%')
                                ->orWhere('final_status', 'LIKE', '%IT MGR Reject%')
                                ->count();                                

        // dd($account_total);
        
        return view('website.pages.home', 
        compact('total_form_finished', 'total_form_rejected', 'total_form_mgr', 'total_form_it', 'total_form_it_mgr', 'total_form_execution',
                'account_mgr_count', 'account_it_count', 'account_it_mgr_count', 'account_execution_count',
                'account_total', 'account_finished', 'account_rejected',
                'folderaccess_mgr_count', 'folderaccess_it_count', 'folderaccess_it_mgr_count', 'folderaccess_execution_count',
                'folderaccess_total', 'folderaccess_finished', 'folderaccess_rejected',
                'newfolder_mgr_count', 'newfolder_it_count', 'newfolder_it_mgr_count', 'newfolder_execution_count',
                'newfolder_total', 'newfolder_finished', 'newfolder_rejected',));
    }    
}
