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
use App\Models\Project;
use App\Models\Fitur;
use App\Models\Relayout;
use App\Models\Network;
use App\Models\Alert;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
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

        $project_create_finished = Project::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'Finished')
                                        ->count();
                                    
        $fitur_create_finished = Fitur::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'Finished')
                                        ->count();

        $total_form_finished = $account_create_finished + $folderaccess_create_finished + $newfolder_create_finished + $software_create_finished + $hardware_create_finished + $vpn_create_finished + $project_create_finished + $fitur_create_finished;
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

        $project_create_rejected = Project::where('created_by', Auth::user()->id)
                                              ->where(function ($query) {
                                                  $query->where('final_status', 'LIKE', '%Rejected%')
                                                      ->orWhere('final_status', 'LIKE', '%Manager Reject%')
                                                      ->orWhere('final_status', 'LIKE', '%IT Reject%')
                                                      ->orWhere('final_status', 'LIKE', '%IT MGR Reject%');
                                              })
                                              ->count();

        $fitur_create_rejected = Fitur::where('created_by', Auth::user()->id)
                                            ->where(function ($query) {
                                                $query->where('final_status', 'LIKE', '%Rejected%')
                                                    ->orWhere('final_status', 'LIKE', '%Manager Reject%')
                                                    ->orWhere('final_status', 'LIKE', '%IT Reject%')
                                                    ->orWhere('final_status', 'LIKE', '%IT MGR Reject%');
                                            })
                                            ->count();

        $total_form_rejected = $account_create_rejected + $folderaccess_create_rejected + $newfolder_create_rejected + $software_create_rejected + $hardware_create_rejected + $vpn_create_rejected + $project_create_rejected + $fitur_create_rejected;
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

        $project_create_mgr = Project::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'created')
                                        ->count();
                                        
        $fitur_create_mgr = Fitur::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'created')
                                        ->count();

        $total_form_mgr = $account_create_mgr + $folderaccess_create_mgr + $newfolder_create_mgr + $software_create_mgr + $hardware_create_mgr + $vpn_create_mgr + $project_create_mgr + $fitur_create_mgr;
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

        $project_create_it = Project::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'Manager Approve')
                                        ->count();

        $fitur_create_it = Fitur::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'Manager Approve')
                                        ->count();

        $total_form_it = $account_create_it + $folderaccess_create_it + $newfolder_create_it + $software_create_it + $hardware_create_it + $vpn_create_it + $project_create_it + $fitur_create_it;
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

        $project_create_it_mgr = Project::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'IT Approve')
                                        ->count();

        $fitur_create_it_mgr = Fitur::where('created_by', Auth::user()->id)
                                        ->where('final_status', 'IT Approve')
                                        ->count();

        $total_form_it_mgr = $account_create_it_mgr + $folderaccess_create_it_mgr + $newfolder_create_it_mgr + $software_create_it_mgr + $hardware_create_it_mgr + $vpn_create_it_mgr + $project_create_it_mgr + $fitur_create_it_mgr;
        /// EXECUTION ///
        $account_create_execution = Account::where('created_by', Auth::user()->id)
                                        ->whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                                        ->count();

        $folderaccess_create_execution = FolderAccess::where('created_by', Auth::user()->id)
                                                ->whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                                                ->count();

        $newfolder_create_execution = NewFolder::where('created_by', Auth::user()->id)
                                        ->whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                                        ->count();

        $software_create_execution = Software::where('created_by', Auth::user()->id)
                                        ->whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                                        ->count();

        $hardware_create_execution = Hardware::where('created_by', Auth::user()->id)
                                        ->whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                                        ->count();

        $vpn_create_execution = Vpn::where('created_by', Auth::user()->id)
                                        ->whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                                        ->count();

        $project_create_execution = Project::where('created_by', Auth::user()->id)
                                        ->whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                                        ->count();

        $fitur_create_execution = Fitur::where('created_by', Auth::user()->id)
                                        ->whereIn('final_status', ['IT MGR Approve', 'On Progress'])
                                        ->count();

        $total_form_execution = $account_create_execution + $folderaccess_create_execution + $newfolder_create_execution + $software_create_execution + $hardware_create_execution + $vpn_create_execution + $project_create_execution + $fitur_create_execution;


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

        $project_mgr_count = Project::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
                                    
        $project_it_count = Project::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $project_it_mgr_count = Project::where('final_status', 'LIKE', 'IT Approve%')->count();
        $project_execution_count = Project::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $fitur_mgr_count = Fitur::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
                                    
        $fitur_it_count = Fitur::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $fitur_it_mgr_count = Fitur::where('final_status', 'LIKE', 'IT Approve%')->count();
        $fitur_execution_count = Fitur::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $relayout_mgr_count = Relayout::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
                                    
        $relayout_it_count = Relayout::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $relayout_it_mgr_count = Relayout::where('final_status', 'LIKE', 'IT Approve%')->count();
        $relayout_execution_count = Relayout::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $network_mgr_count = Network::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
                                $query->where('created_dept', $firstDepartmentId)
                                    ->orWhere('created_dept', $lastDepartmentId);
                            })
                                    ->where('final_status', 'LIKE', '%created%')->count();
                                    
        $network_it_count = Network::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $network_it_mgr_count = Network::where('final_status', 'LIKE', 'IT Approve%')->count();
        $network_execution_count = Network::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        /// DIAGRAM BATANG ///
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

        $filterFirst = $request->input('filterFirst', $startOfMonth) ?? $startOfMonth;
        $filterEnd = $request->input('filterEnd', $endOfMonth) ?? $endOfMonth;

        $account_total = Account::count();
        $account_finished = Account::where('final_status', 'LIKE', '%Finished%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count();
        $account_rejected = Account::where('final_status', 'LIKE', '%Reject%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count();

        $folderaccess_total = FolderAccess::count();
        $folderaccess_finished = FolderAccess::where('final_status', 'LIKE', '%Finished%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count();
        $folderaccess_rejected = FolderAccess::where('final_status', 'LIKE', '%Reject%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count();

        $newfolder_total = NewFolder::count();
        $newfolder_finished = NewFolder::where('final_status', 'LIKE', '%Finished%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count();
        $newfolder_rejected = NewFolder::where('final_status', 'LIKE', '%Reject%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count();   

        $software_total = Software::count();
        $software_finished = Software::where('final_status', 'LIKE', '%Finished%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count();
        $software_rejected = Software::where('final_status', 'LIKE', '%Reject%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count(); 

        $hardware_total = Hardware::count();
        $hardware_finished = Hardware::where('final_status', 'LIKE', '%Finished%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count();
        $hardware_rejected = Hardware::where('final_status', 'LIKE', '%Reject%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count(); 

        $vpn_total = Vpn::count();
        $vpn_finished = Vpn::where('final_status', 'LIKE', '%Finished%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count();
        $vpn_rejected = Vpn::where('final_status', 'LIKE', '%Reject%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count(); 

        $project_total = Project::count();
        $project_finished = Project::where('final_status', 'LIKE', '%Finished%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count();
        $project_rejected = Project::where('final_status', 'LIKE', '%Reject%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count();

        $fitur_total = Fitur::count();
        $fitur_finished = Fitur::where('final_status', 'LIKE', '%Finished%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count();
        $fitur_rejected = Fitur::where('final_status', 'LIKE', '%Reject%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count();

        $relayout_total = Relayout::count();
        $relayout_finished = Relayout::where('final_status', 'LIKE', '%Finished%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count();
        $relayout_rejected = Relayout::where('final_status', 'LIKE', '%Reject%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count();

        $network_total = Network::count();
        $network_finished = Network::where('final_status', 'LIKE', '%Finished%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count();
        $network_rejected = Network::where('final_status', 'LIKE', '%Reject%')->whereBetween('created_at', [$filterFirst, $filterEnd])->count();

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
                    'vpn_total', 'vpn_finished', 'vpn_rejected',
                    'project_mgr_count', 'project_it_count', 'project_it_mgr_count', 'project_execution_count',
                    'project_total', 'project_finished', 'project_rejected',
                    'fitur_mgr_count', 'fitur_it_count', 'fitur_it_mgr_count', 'fitur_execution_count',
                    'fitur_total', 'fitur_finished', 'fitur_rejected',
                    'relayout_mgr_count', 'relayout_it_count', 'relayout_it_mgr_count', 'relayout_execution_count',
                    'relayout_total', 'relayout_finished', 'relayout_rejected',
                    'network_mgr_count', 'network_it_count', 'network_it_mgr_count', 'network_execution_count',
                    'network_total', 'network_finished', 'network_rejected',));
        }
    }    

    public function home_ajax()
    {
        $formAccountData = DB::table('form_account')
                            ->select('form_account.no_reg', 'form_account.final_status', 'form_account.created_at', 'users.name as created_by', 'departments.code as created_dept')
                            ->join('public.users', 'form_account.created_by', 'public.users.id')
                            ->join('public.departments', 'form_account.created_dept', 'public.departments.id')
                            ->get();
    
        $formFolderAccessData = DB::table('form_folder_access')
                                    ->select('form_folder_access.no_reg', 'form_folder_access.final_status', 'form_folder_access.created_at', 'users.name as created_by', 'departments.code as created_dept')
                                    ->join('public.users', 'form_folder_access.created_by', 'public.users.id')
                                    ->join('public.departments', 'form_folder_access.created_dept', 'public.departments.id')
                                    ->get();

         $formNewFolderData = DB::table('form_new_folder')
                                    ->select('form_new_folder.no_reg', 'form_new_folder.final_status', 'form_new_folder.created_at', 'users.name as created_by', 'departments.code as created_dept')
                                    ->join('public.users', 'form_new_folder.created_by', 'public.users.id')
                                    ->join('public.departments', 'form_new_folder.created_dept', 'public.departments.id')
                                    ->get();

        $formSoftwareData = DB::table('form_software')
                                    ->select('form_software.no_reg', 'form_software.final_status', 'form_software.created_at', 'users.name as created_by', 'departments.code as created_dept')
                                    ->join('public.users', 'form_software.created_by', 'public.users.id')
                                    ->join('public.departments', 'form_software.created_dept', 'public.departments.id')
                                    ->get();

        $formHardwareData = DB::table('form_hardware')
                                    ->select('form_hardware.no_reg', 'form_hardware.final_status', 'form_hardware.created_at', 'users.name as created_by', 'departments.code as created_dept')
                                    ->join('public.users', 'form_hardware.created_by', 'public.users.id')
                                    ->join('public.departments', 'form_hardware.created_dept', 'public.departments.id')
                                    ->get();

        $formVpnData = DB::table('form_vpn')
                                    ->select('form_vpn.no_reg', 'form_vpn.final_status', 'form_vpn.created_at', 'users.name as created_by', 'departments.code as created_dept')
                                    ->join('public.users', 'form_vpn.created_by', 'public.users.id')
                                    ->join('public.departments', 'form_vpn.created_dept', 'public.departments.id')
                                    ->get();

        $formProjectData = DB::table('form_project')
                                    ->select('form_project.no_reg', 'form_project.final_status', 'form_project.created_at', 'users.name as created_by', 'departments.code as created_dept')
                                    ->join('public.users', 'form_project.created_by', 'public.users.id')
                                    ->join('public.departments', 'form_project.created_dept', 'public.departments.id')
                                    ->get();

        $formFiturData = DB::table('form_fitur')
                                    ->select('form_fitur.no_reg', 'form_fitur.final_status', 'form_fitur.created_at', 'users.name as created_by', 'departments.code as created_dept')
                                    ->join('public.users', 'form_fitur.created_by', 'public.users.id')
                                    ->join('public.departments', 'form_fitur.created_dept', 'public.departments.id')
                                    ->get();
    
        $mergedData = $formAccountData->concat($formFolderAccessData)->concat($formNewFolderData)->concat($formSoftwareData)->concat($formHardwareData)->concat($formVpnData)->concat($formFiturData)->concat($formProjectData);
    
        return response()->json(['data' => $mergedData]);
    }
}
