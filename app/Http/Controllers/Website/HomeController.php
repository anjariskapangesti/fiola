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
use App\Models\Sistem;
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
            'Sistem',
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

        $account_mgr_count = Account::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })->where('final_status', 'LIKE', '%created%')->count();

        $account_it_count = Account::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $account_it_mgr_count = Account::where('final_status', 'LIKE', 'IT Approve%')->count();
        $account_execution_count = Account::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $folderaccess_mgr_count = FolderAccess::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })->where('final_status', 'LIKE', '%created%')->count();

        $folderaccess_it_count = FolderAccess::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $folderaccess_it_mgr_count = FolderAccess::where('final_status', 'LIKE', 'IT Approve%')->count();
        $folderaccess_execution_count = FolderAccess::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $newfolder_mgr_count = NewFolder::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })->where('final_status', 'LIKE', '%created%')->count();

        $newfolder_it_count = NewFolder::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $newfolder_it_mgr_count = NewFolder::where('final_status', 'LIKE', 'IT Approve%')->count();
        $newfolder_execution_count = NewFolder::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $software_mgr_count = Software::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })->where('final_status', 'LIKE', '%created%')->count();

        $software_it_count = Software::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $software_it_mgr_count = Software::where('final_status', 'LIKE', 'IT Approve%')->count();
        $software_execution_count = Software::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $hardware_mgr_count = Hardware::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })->where('final_status', 'LIKE', '%created%')->count();

        $hardware_it_count = Hardware::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $hardware_it_mgr_count = Hardware::where('final_status', 'LIKE', 'IT Approve%')->count();
        $hardware_execution_count = Hardware::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $vpn_mgr_count = Vpn::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })->where('final_status', 'LIKE', '%created%')->count();

        $vpn_it_count = Vpn::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $vpn_it_mgr_count = Vpn::where('final_status', 'LIKE', 'IT Approve%')->count();
        $vpn_execution_count = Vpn::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $project_mgr_count = Project::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })->where('final_status', 'LIKE', '%created%')->count();

        $project_it_count = Project::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $project_it_mgr_count = Project::where('final_status', 'LIKE', 'IT Approve%')->count();
        $project_execution_count = Project::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $fitur_mgr_count = Fitur::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })->where('final_status', 'LIKE', '%created%')->count();

        $fitur_it_count = Fitur::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $fitur_it_mgr_count = Fitur::where('final_status', 'LIKE', 'IT Approve%')->count();
        $fitur_execution_count = Fitur::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $relayout_mgr_count = Relayout::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })->where('final_status', 'LIKE', '%created%')->count();

        $relayout_it_count = Relayout::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $relayout_it_mgr_count = Relayout::where('final_status', 'LIKE', 'IT Approve%')->count();
        $relayout_execution_count = Relayout::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $network_mgr_count = Network::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })->where('final_status', 'LIKE', '%created%')->count();

        $network_it_count = Network::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $network_it_mgr_count = Network::where('final_status', 'LIKE', 'IT Approve%')->count();
        $network_execution_count = Network::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $akses_sistem_mgr_count = Sistem::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })->where('final_status', 'LIKE', '%created%')->count();

        $akses_sistem_it_count = Sistem::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $akses_sistem_it_mgr_count = Sistem::where('final_status', 'LIKE', 'IT Approve%')->count();
        $akses_sistem_execution_count = Sistem::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $incident_report_mgr_count = IncidentReport::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })->where('final_status', 'LIKE', '%created%')->count();

        $incident_report_it_count = IncidentReport::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $incident_report_it_mgr_count = IncidentReport::where('final_status', 'LIKE', 'IT Approve%')->count();
        $incident_report_execution_count = IncidentReport::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $izin_mgr_count = Izin::where(function ($query) use ($firstDepartmentId, $lastDepartmentId) {
            $query->where('created_dept', $firstDepartmentId)
                ->orWhere('created_dept', $lastDepartmentId);
        })->where('final_status', 'LIKE', '%created%')->count();

        $izin_it_count = Izin::where('final_status', 'LIKE', '%Manager Approve%')->count();
        $izin_it_mgr_count = Izin::where('final_status', 'LIKE', 'IT Approve%')->count();
        $izin_execution_count = Izin::where('final_status', 'LIKE', '%IT MGR Approve%')->count();

        $semua = 0;
        foreach ($models as $model) {
            $modelClass = 'App\\Models\\' . $model;

            if (class_exists($modelClass)) {
                $total_query = $modelClass::query();

                if ($current_year != '0000') {
                    $total_query->whereYear('created_at', $current_year);
                }

                if ($current_month != '00') {
                    $total_query->whereMonth('created_at', $current_month);
                }

                $semua += $total_query->count();
            } else {
                throw new \Exception("Model class {$modelClass} does not exist.");
            }
        }

        $account_query = Account::query();
        if ($current_year != '0000') {
            $account_query->whereYear('created_at', $current_year);
        }
        if ($current_month != '00') {
            $account_query->whereMonth('created_at', $current_month);
        }
        $account_total = $account_query->count();
        $account_finished = (clone $account_query)->where('final_status', 'ILIKE', '%Finished%')->count();
        $account_rejected = (clone $account_query)->where('final_status', 'ILIKE', '%Reject%')->count();

        $folderaccess_query = FolderAccess::query();
        if ($current_year != '0000') {
            $folderaccess_query->whereYear('created_at', $current_year);
        }
        if ($current_month != '00') {
            $folderaccess_query->whereMonth('created_at', $current_month);
        }
        $folderaccess_total = $folderaccess_query->count();
        $folderaccess_finished = (clone $folderaccess_query)->where('final_status', 'ILIKE', '%Finished%')->count();
        $folderaccess_rejected = (clone $folderaccess_query)->where('final_status', 'ILIKE', '%Reject%')->count();

        $newfolder_query = NewFolder::query();
        if ($current_year != '0000') {
            $newfolder_query->whereYear('created_at', $current_year);
        }
        if ($current_month != '00') {
            $newfolder_query->whereMonth('created_at', $current_month);
        }
        $newfolder_total = $newfolder_query->count();
        $newfolder_finished = (clone $newfolder_query)->where('final_status', 'ILIKE', '%Finished%')->count();
        $newfolder_rejected = (clone $newfolder_query)->where('final_status', 'ILIKE', '%Reject%')->count();

        $software_query = Software::query();
        if ($current_year != '0000') {
            $software_query->whereYear('created_at', $current_year);
        }
        if ($current_month != '00') {
            $software_query->whereMonth('created_at', $current_month);
        }
        $software_total = $software_query->count();
        $software_finished = (clone $software_query)->where('final_status', 'ILIKE', '%Finished%')->count();
        $software_rejected = (clone $software_query)->where('final_status', 'ILIKE', '%Reject%')->count();

        $hardware_query = Hardware::query();
        if ($current_year != '0000') {
            $hardware_query->whereYear('created_at', $current_year);
        }
        if ($current_month != '00') {
            $hardware_query->whereMonth('created_at', $current_month);
        }
        $hardware_total = $hardware_query->count();
        $hardware_finished = (clone $hardware_query)->where('final_status', 'ILIKE', '%Finished%')->count();
        $hardware_rejected = (clone $hardware_query)->where('final_status', 'ILIKE', '%Reject%')->count();

        $vpn_query = Vpn::query();
        if ($current_year != '0000') {
            $vpn_query->whereYear('created_at', $current_year);
        }
        if ($current_month != '00') {
            $vpn_query->whereMonth('created_at', $current_month);
        }
        $vpn_total = $vpn_query->count();
        $vpn_finished = (clone $vpn_query)->where('final_status', 'ILIKE', '%Finished%')->count();
        $vpn_rejected = (clone $vpn_query)->where('final_status', 'ILIKE', '%Reject%')->count();

        $project_query = Project::query();
        if ($current_year != '0000') {
            $project_query->whereYear('created_at', $current_year);
        }
        if ($current_month != '00') {
            $project_query->whereMonth('created_at', $current_month);
        }
        $project_total = $project_query->count();
        $project_finished = (clone $project_query)->where('final_status', 'ILIKE', '%Finished%')->count();
        $project_rejected = (clone $project_query)->where('final_status', 'ILIKE', '%Reject%')->count();

        $fitur_query = Fitur::query();
        if ($current_year != '0000') {
            $fitur_query->whereYear('created_at', $current_year);
        }
        if ($current_month != '00') {
            $fitur_query->whereMonth('created_at', $current_month);
        }
        $fitur_total = $fitur_query->count();
        $fitur_finished = (clone $fitur_query)->where('final_status', 'ILIKE', '%Finished%')->count();
        $fitur_rejected = (clone $fitur_query)->where('final_status', 'ILIKE', '%Reject%')->count();

        $relayout_query = Relayout::query();
        if ($current_year != '0000') {
            $relayout_query->whereYear('created_at', $current_year);
        }
        if ($current_month != '00') {
            $relayout_query->whereMonth('created_at', $current_month);
        }
        $relayout_total = $relayout_query->count();
        $relayout_finished = (clone $relayout_query)->where('final_status', 'ILIKE', '%Finished%')->count();
        $relayout_rejected = (clone $relayout_query)->where('final_status', 'ILIKE', '%Reject%')->count();

        $network_query = Network::query();
        if ($current_year != '0000') {
            $network_query->whereYear('created_at', $current_year);
        }
        if ($current_month != '00') {
            $network_query->whereMonth('created_at', $current_month);
        }
        $network_total = $network_query->count();
        $network_finished = (clone $network_query)->where('final_status', 'ILIKE', '%Finished%')->count();
        $network_rejected = (clone $network_query)->where('final_status', 'ILIKE', '%Reject%')->count();

        $akses_sistem_query = Sistem::query();
        if ($current_year != '0000') {
            $akses_sistem_query->whereYear('created_at', $current_year);
        }
        if ($current_month != '00') {
            $akses_sistem_query->whereMonth('created_at', $current_month);
        }
        $akses_sistem_total = $akses_sistem_query->count();
        $akses_sistem_finished = (clone $akses_sistem_query)->where('final_status', 'ILIKE', '%Finished%')->count();
        $akses_sistem_rejected = (clone $akses_sistem_query)->where('final_status', 'ILIKE', '%Reject%')->count();

        $incident_report_query = IncidentReport::query();
        if ($current_year != '0000') {
            $incident_report_query->whereYear('created_at', $current_year);
        }
        if ($current_month != '00') {
            $incident_report_query->whereMonth('created_at', $current_month);
        }
        $incident_report_total = $incident_report_query->count();
        $incident_report_finished = (clone $incident_report_query)->where('final_status', 'ILIKE', '%Finished%')->count();
        $incident_report_rejected = (clone $incident_report_query)->where('final_status', 'ILIKE', '%Reject%')->count();

        $izin_query = Izin::query();
        if ($current_year != '0000') {
            $izin_query->whereYear('created_at', $current_year);
        }
        if ($current_month != '00') {
            $izin_query->whereMonth('created_at', $current_month);
        }
        $izin_total = $izin_query->count();
        $izin_finished = (clone $izin_query)->where('final_status', 'ILIKE', '%Finished%')->count();
        $izin_rejected = (clone $izin_query)->where('final_status', 'ILIKE', '%Reject%')->count();

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

        for ($m = 1; $m <= 12; $m++) {
            $ticket_total[$m] = Ticket::whereYear('created_at', $current_year)
                ->whereMonth('created_at', str_pad($m, 2, '0', STR_PAD_LEFT))
                ->count();

            $ticket_solved[$m] = Ticket::whereYear('created_at', $current_year)
                ->whereMonth('created_at', str_pad($m, 2, '0', STR_PAD_LEFT))
                ->where('final_status', 'Finished')
                ->count();
        }

        $projectsApproved = Project::where('final_status', 'Finished')
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->orderBy('start_date', 'asc')
            ->get([
                'id',
                'no_reg',
                'nama_project',
                'fullname',
                'department',
                'start_date',
                'end_date',
                'final_status',
            ]);

        $timelineRows = [];
        $timelineStart = null;
        $timelineEnd = null;

        foreach ($projectsApproved as $project) {
            $startMs = Carbon::parse($project->start_date)->startOfDay()->timestamp * 1000;
            $endMs = Carbon::parse($project->end_date)->endOfDay()->timestamp * 1000;
            $durationDays = Carbon::parse($project->start_date)->diffInDays(Carbon::parse($project->end_date)) + 1;

            $timelineRows[] = [
                'nama_project' => $project->nama_project,
                'no_reg' => $project->no_reg,
                'requestor' => $project->fullname,
                'department' => $project->department,
                'status' => $project->final_status,
                'start_ms' => $startMs,
                'end_ms' => $endMs,
                'start_label' => Carbon::parse($project->start_date)->format('d M Y'),
                'end_label' => Carbon::parse($project->end_date)->format('d M Y'),
                'duration_days' => $durationDays,
            ];

            if ($timelineStart === null || $startMs < $timelineStart) {
                $timelineStart = $startMs;
            }

            if ($timelineEnd === null || $endMs > $timelineEnd) {
                $timelineEnd = $endMs;
            }
        }

        if ($timelineStart !== null && $timelineEnd !== null) {
            $timelineStart = Carbon::createFromTimestampMs($timelineStart)->subDays(2)->timestamp * 1000;
            $timelineEnd = Carbon::createFromTimestampMs($timelineEnd)->addDays(2)->timestamp * 1000;
        }

        if ($auth > 0) {
            return redirect()->route('website.user.edit');
        }

        return view('website.pages.home', [
            'ticket_total' => $ticket_total,
            'ticket_solved' => $ticket_solved,
        ], compact(
            'total_form_finished',
            'total_form_rejected',
            'total_form_mgr',
            'total_form_it',
            'total_form_it_mgr',
            'total_form_execution',
            'account_mgr_count',
            'account_it_count',
            'account_it_mgr_count',
            'account_execution_count',
            'account_total',
            'account_finished',
            'account_rejected',
            'folderaccess_mgr_count',
            'folderaccess_it_count',
            'folderaccess_it_mgr_count',
            'folderaccess_execution_count',
            'folderaccess_total',
            'folderaccess_finished',
            'folderaccess_rejected',
            'newfolder_mgr_count',
            'newfolder_it_count',
            'newfolder_it_mgr_count',
            'newfolder_execution_count',
            'newfolder_total',
            'newfolder_finished',
            'newfolder_rejected',
            'software_mgr_count',
            'software_it_count',
            'software_it_mgr_count',
            'software_execution_count',
            'software_total',
            'software_finished',
            'software_rejected',
            'hardware_mgr_count',
            'hardware_it_count',
            'hardware_it_mgr_count',
            'hardware_execution_count',
            'hardware_total',
            'hardware_finished',
            'hardware_rejected',
            'vpn_mgr_count',
            'vpn_it_count',
            'vpn_it_mgr_count',
            'vpn_execution_count',
            'vpn_total',
            'vpn_finished',
            'vpn_rejected',
            'project_mgr_count',
            'project_it_count',
            'project_it_mgr_count',
            'project_execution_count',
            'project_total',
            'project_finished',
            'project_rejected',
            'fitur_mgr_count',
            'fitur_it_count',
            'fitur_it_mgr_count',
            'fitur_execution_count',
            'fitur_total',
            'fitur_finished',
            'fitur_rejected',
            'relayout_mgr_count',
            'relayout_it_count',
            'relayout_it_mgr_count',
            'relayout_execution_count',
            'relayout_total',
            'relayout_finished',
            'relayout_rejected',
            'network_mgr_count',
            'network_it_count',
            'network_it_mgr_count',
            'network_execution_count',
            'network_total',
            'network_finished',
            'network_rejected',
            'akses_sistem_mgr_count',
            'akses_sistem_it_count',
            'akses_sistem_it_mgr_count',
            'akses_sistem_execution_count',
            'akses_sistem_total',
            'akses_sistem_finished',
            'akses_sistem_rejected',
            'incident_report_mgr_count',
            'incident_report_it_count',
            'incident_report_it_mgr_count',
            'incident_report_execution_count',
            'incident_report_total',
            'incident_report_finished',
            'incident_report_rejected',
            'izin_mgr_count',
            'izin_it_count',
            'izin_it_mgr_count',
            'izin_execution_count',
            'izin_total',
            'izin_finished',
            'izin_rejected',
            'ticket_open',
            'ticket_finished',
            'ticket_on_progress',
            'ticket_pending',
            'ticket_rejected',
            'semua',
            'projectsApproved',
            'timelineRows',
            'timelineStart',
            'timelineEnd'
        ));
    }

    public function home_ajax()
    {
        $tables = [
            'form_account' => ['display' => 'Form Account', 'url' => 'account'],
            'form_folder_access' => ['display' => 'Form Folder Access', 'url' => 'folder-access'],
            'form_new_folder' => ['display' => 'Form New Folder', 'url' => 'new-folder'],
            'form_software' => ['display' => 'Form Software Installation', 'url' => 'software'],
            'form_hardware' => ['display' => 'Form Request Device', 'url' => 'hardware'],
            'form_vpn' => ['display' => 'Form VPN', 'url' => 'vpn'],
            'form_project' => ['display' => 'Form Request Project', 'url' => 'project'],
            'form_fitur' => ['display' => 'Form Request Fitur', 'url' => 'fitur'],
            'form_relayout' => ['display' => 'Form Relayout', 'url' => 'relayout'],
            'form_network' => ['display' => 'Form Network Change', 'url' => 'network'],
            'form_sistem' => ['display' => 'Form Akses Sistem', 'url' => 'akses_sistem'],
            'form_incident_report' => ['display' => 'Form Incident Report', 'url' => 'incident_report'],
            'form_izin' => ['display' => 'Form Izin Memasuki Area Level 3', 'url' => 'izin'],
        ];

        $mergedData = collect();

        if (Auth::user()->hasDepartment('ITD')) {
            foreach ($tables as $table => $config) {
                $data = DB::table($table)
                    ->select(
                        "$table.no_reg",
                        "$table.final_status",
                        "$table.created_at",
                        'users.name as created_name',
                        'departments.code as created_dept',
                        DB::raw("'{$config['display']}' as form_name"),
                        DB::raw("'{$config['url']}' as form_url")
                    )
                    ->join('public.users', "$table.created_by", '=', 'public.users.id')
                    ->join('public.departments', "$table.created_dept", '=', 'public.departments.id')
                    ->whereNull("$table.is_finish")
                    ->get();

                $mergedData = $mergedData->concat($data);
            }
        } elseif (Auth::user()->can('approve_mgr')) {
            $userDepartments = Auth::user()->departments->pluck('id');
            $firstDepartmentId = $userDepartments->first();
            $lastDepartmentId = $userDepartments->last();

            foreach ($tables as $table => $config) {
                $data = DB::table($table)
                    ->select(
                        "$table.no_reg",
                        "$table.final_status",
                        "$table.created_at",
                        "$table.created_by",
                        'users.name as created_name',
                        'departments.code as created_dept',
                        DB::raw("'$table' as table_name"),
                        DB::raw("'{$config['display']}' as form_name"),
                        DB::raw("'{$config['url']}' as form_url")
                    )
                    ->join('public.users', "$table.created_by", '=', 'public.users.id')
                    ->join('public.departments', "$table.created_dept", '=', 'public.departments.id')
                    ->where(function ($query) use ($firstDepartmentId, $lastDepartmentId, $table) {
                        $query->where("$table.created_dept", $firstDepartmentId)
                            ->orWhere("$table.created_dept", $lastDepartmentId);
                    })
                    ->whereNull("$table.is_finish")
                    ->where("$table.final_status", 'created')
                    ->orWhere("$table.created_by", Auth::user()->id)
                    ->get();

                $mergedData = $mergedData->concat($data);
            }
        } else {
            foreach ($tables as $table => $config) {
                $data = DB::table($table)
                    ->select(
                        "$table.no_reg",
                        "$table.final_status",
                        "$table.created_at",
                        "$table.created_by",
                        'users.name as created_name',
                        'departments.code as created_dept',
                        DB::raw("'{$config['display']}' as form_name"),
                        DB::raw("'{$config['url']}' as form_url")
                    )
                    ->join('public.users', "$table.created_by", '=', 'public.users.id')
                    ->join('public.departments', "$table.created_dept", '=', 'public.departments.id')
                    ->where("$table.created_by", Auth::user()->id)
                    ->whereNull("$table.is_finish")
                    ->get();

                $mergedData = $mergedData->concat($data);
            }
        }

        return response()->json(['data' => $mergedData]);
    }

    public function type()
    {
        $supports = Support::orderBy('shift', 'ASC')->get();

        return view('website.pages.type', compact('supports'));
    }

    public function get_rating()
    {
        $avgOverall = Ticket::selectRaw('ROUND(AVG(review::numeric), 2) as avg_review')->value('avg_review');

        $avgByPerson = Ticket::join('public.users', 'tickets.finish_by', '=', 'users.id')
            ->select('users.name')
            ->selectRaw('ROUND(AVG(tickets.review::numeric), 2) as avg_review')
            ->selectRaw('COUNT(*) as total_ticket')
            ->groupBy('users.id', 'users.name')
            ->get();

        return response()->json([
            'overall_average' => $avgOverall,
            'average_per_person' => $avgByPerson
        ]);
    }

    public function metrics(Request $request)
    {
        $days = (int) $request->get('days', 30);

        $numericFilter = "review ~ '^[0-9]+(\\.[0-9]+)?$'";

        $overall = Ticket::whereRaw($numericFilter)
            ->selectRaw('ROUND(AVG(review::numeric), 2) AS avg_review, COUNT(*) AS total_reviews')
            ->first();

        $byPerson = Ticket::join('public.users', 'tickets.finish_by', '=', 'users.id')
            ->whereRaw($numericFilter)
            ->selectRaw('users.id, users.name, ROUND(AVG(tickets.review::numeric), 2) AS avg_review, COUNT(*) AS total_ticket')
            ->groupBy('users.id', 'users.name')
            ->orderByRaw('COUNT(*) DESC, AVG(tickets.review::numeric) DESC')
            ->get();

        $trend = Ticket::whereRaw($numericFilter)
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw("DATE(created_at) AS d, ROUND(AVG(review::numeric), 2) AS avg_review, COUNT(*) AS n")
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        $distribution = Ticket::whereRaw($numericFilter)
            ->selectRaw("
                SUM(CASE WHEN review::numeric < 60 THEN 1 ELSE 0 END) AS b_0_59,
                SUM(CASE WHEN review::numeric >= 60 AND review::numeric < 70 THEN 1 ELSE 0 END) AS b_60_69,
                SUM(CASE WHEN review::numeric >= 70 AND review::numeric < 80 THEN 1 ELSE 0 END) AS b_70_79,
                SUM(CASE WHEN review::numeric >= 80 AND review::numeric < 90 THEN 1 ELSE 0 END) AS b_80_89,
                SUM(CASE WHEN review::numeric >= 90 THEN 1 ELSE 0 END) AS b_90_100
            ")->first();

        $minTickets = (int) $request->get('min_tickets', 5);
        $topPerformers = Ticket::join('public.users', 'tickets.finish_by', '=', 'users.id')
            ->whereRaw($numericFilter)
            ->selectRaw('users.id, users.name, ROUND(AVG(tickets.review::numeric), 2) AS avg_review, COUNT(*) AS total_ticket')
            ->groupBy('users.id', 'users.name')
            ->havingRaw('COUNT(*) >= ?', [$minTickets])
            ->orderByRaw('COUNT(*) DESC, AVG(tickets.review::numeric) DESC')
            ->limit(10)
            ->get();

        return response()->json([
            'filters' => [
                'days' => $days,
                'min_tickets' => $minTickets,
            ],
            'overall' => [
                'average' => (float) ($overall->avg_review ?? 0),
                'total_reviews' => (int) ($overall->total_reviews ?? 0),
            ],
            'by_person' => $byPerson,
            'trend' => $trend,
            'distribution' => [
                '0-59'   => (int) ($distribution->b_0_59 ?? 0),
                '60-69'  => (int) ($distribution->b_60_69 ?? 0),
                '70-79'  => (int) ($distribution->b_70_79 ?? 0),
                '80-89'  => (int) ($distribution->b_80_89 ?? 0),
                '90-100' => (int) ($distribution->b_90_100 ?? 0),
            ],
            'top_performers' => $topPerformers,
        ]);
    }
}