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
use App\Models\AksesSistem;
use App\Models\IncidentReport;
use App\Models\Izin;

use App\Models\Alert;
use App\Models\Ticket;
use App\Models\Support;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;

        $current_year = $request->filter_year ?? $year;
        $current_month = $request->filter_month ?? $month;

        $userDepartments = Auth::user()->departments->pluck('id');
        $firstDepartmentId = $userDepartments->first();
        $lastDepartmentId = $userDepartments->last();
        /// FINISHED ///
        $models = [
            'Account',
            'FolderAccess',
            'NewFolder',
            'Software',
            'Hardware',
            'Vpn',
            'Project',
            'Fitur',
            'Relayout',
            'Network',
            'AksesSistem',
            'IncidentReport',
            'Izin',
        ];
    
        $finalStatusConditions = [
            'Finished' => ['Finished'],
            'Rejected' => ['%Rejected%', '%Manager Reject%', '%IT Reject%', '%IT MGR Reject%'],
            'created' => ['created'],
            'Manager Approve' => ['Manager Approve'],
            'IT Approve' => ['IT Approve'],
            'Execution' => ['IT MGR Approve', 'On Progress'],
        ];
    
        $results = [];
    
        foreach ($finalStatusConditions as $statusKey => $conditions) {
            $results[$statusKey] = 0;
    
            foreach ($models as $model) {
                $modelClass = 'App\\Models\\' . $model;
                $query = $modelClass::where('created_by', Auth::user()->id);
    
                if ($statusKey === 'Rejected') {
                    $query->where(function ($query) use ($conditions) {
                        foreach ($conditions as $condition) {
                            $query->orWhere('final_status', 'LIKE', $condition);
                        }
                    });
                } elseif ($statusKey === 'Execution') {
                    $query->whereIn('final_status', $conditions);
                } else {
                    // Check if conditions is not empty
                    if (!empty($conditions)) {
                        $query->where('final_status', $conditions[0]);
                    }
                }
    
                $results[$statusKey] += $query->count();
            }
        }
    
        $total_form_finished = $results['Finished'];
        $total_form_rejected = $results['Rejected'];
        $total_form_mgr = $results['created'];
        $total_form_it = $results['Manager Approve'];
        $total_form_it_mgr = $results['IT Approve'];
        $total_form_execution = $results['Execution'];

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

        $akses_sistem_mgr_count = AksesSistem::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
                ->where('final_status', 'LIKE', '%created%')->count();
                
        $akses_sistem_it_count = AksesSistem::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $akses_sistem_it_mgr_count = AksesSistem::where('final_status', 'LIKE', 'IT Approve%')->count();
        $akses_sistem_execution_count = AksesSistem::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $incident_report_mgr_count = IncidentReport::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
                ->where('final_status', 'LIKE', '%created%')->count();
                
        $incident_report_it_count = IncidentReport::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $incident_report_it_mgr_count = IncidentReport::where('final_status', 'LIKE', 'IT Approve%')->count();
        $incident_report_execution_count = IncidentReport::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $izin_mgr_count = Izin::where(function($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })
                ->where('final_status', 'LIKE', '%created%')->count();
                
        $izin_it_count = Izin::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $izin_it_mgr_count = Izin::where('final_status', 'LIKE', 'IT Approve%')->count();
        $izin_execution_count = Izin::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        /// DIAGRAM BATANG ///
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

        $filterFirst = $request->input('filterFirst', $startOfMonth) ?? $startOfMonth;
        $filterEnd = $request->input('filterEnd', $endOfMonth) ?? $endOfMonth;

        $account_total = Account::count();
        $account_finished = Account::where('final_status', 'LIKE', '%Finished%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();
        $account_rejected = Account::where('final_status', 'LIKE', '%Reject%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();

        $folderaccess_total = FolderAccess::count();
        $folderaccess_finished = FolderAccess::where('final_status', 'LIKE', '%Finished%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();
        $folderaccess_rejected = FolderAccess::where('final_status', 'LIKE', '%Reject%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();

        $newfolder_total = NewFolder::count();
        $newfolder_finished = NewFolder::where('final_status', 'LIKE', '%Finished%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();
        $newfolder_rejected = NewFolder::where('final_status', 'LIKE', '%Reject%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();   

        $software_total = Software::count();
        $software_finished = Software::where('final_status', 'LIKE', '%Finished%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();
        $software_rejected = Software::where('final_status', 'LIKE', '%Reject%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count(); 

        $hardware_total = Hardware::count();
        $hardware_finished = Hardware::where('final_status', 'LIKE', '%Finished%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();
        $hardware_rejected = Hardware::where('final_status', 'LIKE', '%Reject%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count(); 

        $vpn_total = Vpn::count();
        $vpn_finished = Vpn::where('final_status', 'LIKE', '%Finished%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();
        $vpn_rejected = Vpn::where('final_status', 'LIKE', '%Reject%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count(); 

        $project_total = Project::count();
        $project_finished = Project::where('final_status', 'LIKE', '%Finished%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();
        $project_rejected = Project::where('final_status', 'LIKE', '%Reject%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();

        $fitur_total = Fitur::count();
        $fitur_finished = Fitur::where('final_status', 'LIKE', '%Finished%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();
        $fitur_rejected = Fitur::where('final_status', 'LIKE', '%Reject%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();

        $relayout_total = Relayout::count();
        $relayout_finished = Relayout::where('final_status', 'LIKE', '%Finished%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();
        $relayout_rejected = Relayout::where('final_status', 'LIKE', '%Reject%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();

        $network_total = Network::count();
        $network_finished = Network::where('final_status', 'LIKE', '%Finished%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();
        $network_rejected = Network::where('final_status', 'LIKE', '%Reject%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();
        
        $akses_sistem_total = AksesSistem::count();
        $akses_sistem_finished = AksesSistem::where('final_status', 'LIKE', '%Finished%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();
        $akses_sistem_rejected = AksesSistem::where('final_status', 'LIKE', '%Reject%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();
        
        $incident_report_total = IncidentReport::count();
        $incident_report_finished = IncidentReport::where('final_status', 'LIKE', '%Finished%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();
        $incident_report_rejected = IncidentReport::where('final_status', 'LIKE', '%Reject%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();

        $izin_total = Izin::count();
        $izin_finished = Izin::where('final_status', 'LIKE', '%Finished%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();
        $izin_rejected = Izin::where('final_status', 'LIKE', '%Reject%')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();

        $auth = User::where('id', Auth::user()->id)
                                    ->whereNull('nohp')
                                    ->count();   

        $ticket_open = Ticket::where('final_status', 'created')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();
        $ticket_finished = Ticket::where('final_status', 'Finished')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();
        $ticket_on_progress = Ticket::where('final_status', 'IT Approve')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();
        $ticket_pending = Ticket::where('final_status', 'Pending')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();
        $ticket_rejected = Ticket::where('final_status', 'Rejected')->whereYear('created_at', $current_year)->whereMonth('created_at', $current_month)->count();

        $ticket_total = [];
        $ticket_solved = [];

        for ($month = 1; $month <= 12; $month++) {
            $ticket_total[$month] = Ticket::whereYear('created_at', $current_year)
                ->whereMonth('created_at', str_pad($month, 2, '0', STR_PAD_LEFT))
                ->count();

            $ticket_solved[$month] = Ticket::whereYear('created_at', $current_year)
                ->whereMonth('created_at', str_pad($month, 2, '0', STR_PAD_LEFT))
                ->where('final_status', 'Finished')
                ->count();
        } 

        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        } else{
            return view('website.pages.home', [
                'ticket_total' => $ticket_total,
                'ticket_solved' => $ticket_solved,
            ], 
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
                    'network_total', 'network_finished', 'network_rejected',
                    'akses_sistem_mgr_count', 'akses_sistem_it_count', 'akses_sistem_it_mgr_count', 'akses_sistem_execution_count',
                    'akses_sistem_total', 'akses_sistem_finished', 'akses_sistem_rejected',
                    'incident_report_mgr_count', 'incident_report_it_count', 'incident_report_it_mgr_count', 'incident_report_execution_count',
                    'incident_report_total', 'incident_report_finished', 'incident_report_rejected',
                    'izin_mgr_count', 'izin_it_count', 'izin_it_mgr_count', 'izin_execution_count',
                    'izin_total', 'izin_finished', 'izin_rejected',
                    'ticket_open', 'ticket_finished', 'ticket_on_progress', 'ticket_pending', 'ticket_rejected',));
        }
    }    

    public function home_ajax()
    {
        $tables = [
            'form_account' => 'form_account',
            'form_folder_access' => 'form_folder_access',
            'form_new_folder' => 'form_new_folder',
            'form_software' => 'form_software',
            'form_hardware' => 'form_hardware',
            'form_vpn' => 'form_vpn',
            'form_project' => 'form_project',
            'form_fitur' => 'form_fitur',
            'form_relayout' => 'form_relayout',
            'form_network' => 'form_network',
            'form_akses_sistem' => 'form_akses_sistem',
            'form_incident_report' => 'form_incident_report',
            'form_izin' => 'form_izin',
        ];

        $mergedData = collect();

        foreach ($tables as $table) {
            $data = DB::table($table)
                ->select("$table.no_reg", "$table.final_status", "$table.created_at", 'users.name as created_by', 'departments.code as created_dept')
                ->join('public.users', "$table.created_by", '=', 'public.users.id')
                ->join('public.departments', "$table.created_dept", '=', 'public.departments.id')
                ->whereNull("$table.is_finish")
                ->get();

            $mergedData = $mergedData->concat($data);
        }

        return response()->json(['data' => $mergedData]);
    }

    public function type()
    {
        $supports = Support::orderBy('shift', 'ASC')->get();

        return view('website.pages.type', compact('supports'));
    }
}
