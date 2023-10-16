<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Department;
use App\Models\Account;
use App\Models\FolderAccess;
use App\Models\NewFolder;
use App\Models\Software;
use App\Models\Hardware;
use App\Models\Vpn;
use Illuminate\Support\Facades\DB;
use Auth;

class HomeController extends Controller
{
    public function index()
    {       
        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();
        /// FINISHED ///
        $account_create_finished = Account::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'Finished')
                                        ->count();

        $folderaccess_create_finished = FolderAccess::where('created_by', Auth::user()->id)
                                                ->where('final_status', 'Finished')
                                                ->count();

        $newfolder_create_finished = NewFolder::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'Finished')
                                        ->count();

        $software_create_finished = Software::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'Finished')
                                        ->count();

        $hardware_create_finished = Hardware::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'Finished')
                                        ->count();

        $vpn_create_finished = Vpn::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'Finished')
                                        ->count();

        $total_form_finished = $account_create_finished + $folderaccess_create_finished + $newfolder_create_finished + $software_create_finished + $hardware_create_finished + $vpn_create_finished;
        /// REJECTED ///
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

        $software_create_rejected = Software::where('created_by', Auth::user()->id)
                                              ->where(function ($query) {
                                                  $query->where('final_status', 'LIKE', '%Rejected%')
                                                      ->orWhere('final_status', 'LIKE', '%Manager Reject%')
                                                      ->orWhere('final_status', 'LIKE', '%IT Reject%')
                                                      ->orWhere('final_status', 'LIKE', '%IT MGR Reject%');
                                              })
                                              ->count();
                                              
        $hardware_create_rejected = Hardware::where('created_by', Auth::user()->id)
                                              ->where(function ($query) {
                                                  $query->where('final_status', 'LIKE', '%Rejected%')
                                                      ->orWhere('final_status', 'LIKE', '%Manager Reject%')
                                                      ->orWhere('final_status', 'LIKE', '%IT Reject%')
                                                      ->orWhere('final_status', 'LIKE', '%IT MGR Reject%');
                                              })
                                              ->count();

        $vpn_create_rejected = Vpn::where('created_by', Auth::user()->id)
                                              ->where(function ($query) {
                                                  $query->where('final_status', 'LIKE', '%Rejected%')
                                                      ->orWhere('final_status', 'LIKE', '%Manager Reject%')
                                                      ->orWhere('final_status', 'LIKE', '%IT Reject%')
                                                      ->orWhere('final_status', 'LIKE', '%IT MGR Reject%');
                                              })
                                              ->count();

        $total_form_rejected = $account_create_rejected + $folderaccess_create_rejected + $newfolder_create_rejected + $software_create_rejected + $hardware_create_rejected + $vpn_create_rejected;
        /// MGR ///
        $account_create_mgr = Account::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'created')
                                        ->count();

        $folderaccess_create_mgr = FolderAccess::where('created_by', Auth::user()->id)
                                                ->where('final_status', 'created')
                                                ->count();

        $newfolder_create_mgr = NewFolder::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'created')
                                        ->count();

        $software_create_mgr = Software::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'created')
                                        ->count();
                                        
        $hardware_create_mgr = Hardware::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'created')
                                        ->count();
                                        
        $vpn_create_mgr = Vpn::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'created')
                                        ->count();

        $total_form_mgr = $account_create_mgr + $folderaccess_create_mgr + $newfolder_create_mgr + $software_create_mgr + $hardware_create_mgr + $vpn_create_mgr;
        /// IT ///
        $account_create_it = Account::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'Manager Approve')
                                        ->count();

        $folderaccess_create_it = FolderAccess::where('created_by', Auth::user()->id)
                                                ->where('final_status', 'Manager Approve')
                                                ->count();

        $newfolder_create_it = NewFolder::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'Manager Approve')
                                        ->count();

        $software_create_it = Software::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'Manager Approve')
                                        ->count();

        $hardware_create_it = Hardware::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'Manager Approve')
                                        ->count();

        $vpn_create_it = Vpn::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'Manager Approve')
                                        ->count();

        $total_form_it = $account_create_it + $folderaccess_create_it + $newfolder_create_it + $software_create_it + $hardware_create_it + $vpn_create_it;
        /// IT MGR ///
        $account_create_it_mgr = Account::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'IT Approve')
                                        ->count();

        $folderaccess_create_it_mgr = FolderAccess::where('created_by', Auth::user()->id)
                                                ->where('final_status', 'IT Approve')
                                                ->count();

        $newfolder_create_it_mgr = NewFolder::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'IT Approve')
                                        ->count();

        $software_create_it_mgr = Software::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'IT Approve')
                                        ->count();

        $hardware_create_it_mgr = Hardware::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'IT Approve')
                                        ->count();

        $vpn_create_it_mgr = Vpn::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'IT Approve')
                                        ->count();

        $total_form_it_mgr = $account_create_it_mgr + $folderaccess_create_it_mgr + $newfolder_create_it_mgr + $software_create_it_mgr + $hardware_create_it_mgr + $vpn_create_it_mgr;
        /// EXECUTION ///
        $account_create_execution = Account::where('created_by', Auth::user()->id)
                                        ->whereIn('final_status', ['IT MGR Approve', 'Delay'])
                                        ->count();

        $folderaccess_create_execution = FolderAccess::where('created_by', Auth::user()->id)
                                                ->whereIn('final_status', ['IT MGR Approve', 'Delay'])
                                                ->count();

        $newfolder_create_execution = NewFolder::where('created_by', Auth::user()->id)
                                        ->whereIn('final_status', ['IT MGR Approve', 'Delay'])
                                        ->count();

        $software_create_execution = Software::where('created_by', Auth::user()->id)
                                        ->whereIn('final_status', ['IT MGR Approve', 'Delay'])
                                        ->count();

        $hardware_create_execution = Hardware::where('created_by', Auth::user()->id)
                                        ->whereIn('final_status', ['IT MGR Approve', 'Delay'])
                                        ->count();

        $vpn_create_execution = Vpn::where('created_by', Auth::user()->id)
                                        ->whereIn('final_status', ['IT MGR Approve', 'Delay'])
                                        ->count();

        $total_form_execution = $account_create_execution + $folderaccess_create_execution + $newfolder_create_execution + $software_create_execution + $hardware_create_execution + $vpn_create_execution;


        /// MASTER ///
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

        $software_mgr_count = Software::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
                                    
        $software_it_count = Software::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $software_it_mgr_count = Software::where('final_status', 'LIKE', 'IT Approve%')->count();
        $software_execution_count = Software::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $hardware_mgr_count = Hardware::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
                                    
        $hardware_it_count = Hardware::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $hardware_it_mgr_count = Hardware::where('final_status', 'LIKE', 'IT Approve%')->count();
        $hardware_execution_count = Hardware::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $vpn_mgr_count = Vpn::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
                                    
        $vpn_it_count = Vpn::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $vpn_it_mgr_count = Vpn::where('final_status', 'LIKE', 'IT Approve%')->count();
        $vpn_execution_count = Vpn::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        ///
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
                                
        $software_total = Software::count();
        $software_finished = Software::where('final_status', 'LIKE', '%Finished%')->count();
        $software_rejected = Software::where('final_status', 'LIKE', '%Rejected%')
                                ->orWhere('final_status', 'LIKE', '%Manager Reject%')
                                ->orWhere('final_status', 'LIKE', '%IT Reject%')
                                ->orWhere('final_status', 'LIKE', '%IT MGR Reject%')
                                ->count(); 

        $hardware_total = Hardware::count();
        $hardware_finished = Hardware::where('final_status', 'LIKE', '%Finished%')->count();
        $hardware_rejected = Hardware::where('final_status', 'LIKE', '%Rejected%')
                                ->orWhere('final_status', 'LIKE', '%Manager Reject%')
                                ->orWhere('final_status', 'LIKE', '%IT Reject%')
                                ->orWhere('final_status', 'LIKE', '%IT MGR Reject%')
                                ->count(); 

        $vpn_total = Vpn::count();
        $vpn_finished = Vpn::where('final_status', 'LIKE', '%Finished%')->count();
        $vpn_rejected = Vpn::where('final_status', 'LIKE', '%Rejected%')
                                ->orWhere('final_status', 'LIKE', '%Manager Reject%')
                                ->orWhere('final_status', 'LIKE', '%IT Reject%')
                                ->orWhere('final_status', 'LIKE', '%IT MGR Reject%')
                                ->count(); 

        $auth = User::where('id', Auth::user()->id)
                                    ->whereNull('nohp')
                                    ->count();   

        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else{
            return view('website.pages.home', 
            compact('total_form_finished', 'total_form_rejected', 'total_form_mgr', 'total_form_it', 'total_form_it_mgr', 'total_form_execution',
                    'account_mgr_count', 'account_it_count', 'account_it_mgr_count', 'account_execution_count',
                    'account_total', 'account_finished', 'account_rejected',
                    'folderaccess_mgr_count', 'folderaccess_it_count', 'folderaccess_it_mgr_count', 'folderaccess_execution_count',
                    'folderaccess_total', 'folderaccess_finished', 'folderaccess_rejected',
                    'newfolder_mgr_count', 'newfolder_it_count', 'newfolder_it_mgr_count', 'newfolder_execution_count',
                    'newfolder_total', 'newfolder_finished', 'newfolder_rejected',
                    'software_mgr_count', 'software_it_count', 'software_it_mgr_count', 'software_execution_count',
                    'software_total', 'software_finished', 'software_rejected',
                    'hardware_mgr_count', 'hardware_it_count', 'hardware_it_mgr_count', 'hardware_execution_count',
                    'hardware_total', 'hardware_finished', 'hardware_rejected',
                    'vpn_mgr_count', 'vpn_it_count', 'vpn_it_mgr_count', 'vpn_execution_count',
                    'vpn_total', 'vpn_finished', 'vpn_rejected'));
        }
                                    
        
    }    

    public function home_ajax()
    {
        $formAccountData = DB::table('form_account')
                            ->select('form_account.no_reg', 'form_account.final_status', 'form_account.created_at', 'users.name as created_by', 'departments.code as created_dept')
                            ->join('users', 'form_account.created_by', 'users.id')
                            ->join('departments', 'form_account.created_dept', 'departments.id')
                            ->get();
    
        $formFolderAccessData = DB::table('form_folder_access')
                                    ->select('form_folder_access.no_reg', 'form_folder_access.final_status', 'form_folder_access.created_at', 'users.name as created_by', 'departments.code as created_dept')
                                    ->join('users', 'form_folder_access.created_by', 'users.id')
                                    ->join('departments', 'form_folder_access.created_dept', 'departments.id')
                                    ->get();

         $formNewFolderData = DB::table('form_new_folder')
                                    ->select('form_new_folder.no_reg', 'form_new_folder.final_status', 'form_new_folder.created_at', 'users.name as created_by', 'departments.code as created_dept')
                                    ->join('users', 'form_new_folder.created_by', 'users.id')
                                    ->join('departments', 'form_new_folder.created_dept', 'departments.id')
                                    ->get();

        $formSoftwareData = DB::table('form_software')
                                    ->select('form_software.no_reg', 'form_software.final_status', 'form_software.created_at', 'users.name as created_by', 'departments.code as created_dept')
                                    ->join('users', 'form_software.created_by', 'users.id')
                                    ->join('departments', 'form_software.created_dept', 'departments.id')
                                    ->get();

        $formHardwareData = DB::table('form_hardware')
                                    ->select('form_hardware.no_reg', 'form_hardware.final_status', 'form_hardware.created_at', 'users.name as created_by', 'departments.code as created_dept')
                                    ->join('users', 'form_hardware.created_by', 'users.id')
                                    ->join('departments', 'form_hardware.created_dept', 'departments.id')
                                    ->get();

        $formVpnData = DB::table('form_vpn')
                                    ->select('form_vpn.no_reg', 'form_vpn.final_status', 'form_vpn.created_at', 'users.name as created_by', 'departments.code as created_dept')
                                    ->join('users', 'form_vpn.created_by', 'users.id')
                                    ->join('departments', 'form_vpn.created_dept', 'departments.id')
                                    ->get();
    
        $mergedData = $formAccountData->concat($formFolderAccessData)->concat($formNewFolderData)->concat($formSoftwareData)->concat($formHardwareData)->concat($formVpnData);
    
        return response()->json(['data' => $mergedData]);
    }
}
