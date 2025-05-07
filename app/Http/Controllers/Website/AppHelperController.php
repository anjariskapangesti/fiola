<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AppHelper;

class AppHelperController extends Controller
{
    public function getApprovalCount()
    {
        $ticket_count = AppHelper::ticket_count();
        // FORM ACCOUNT //
        $account_mgr_count = AppHelper::account_mgr_count();
        $account_it_count = AppHelper::account_it_count();
        $account_it_mgr_count = AppHelper::account_it_mgr_count();
        $account_execution_count = AppHelper::account_execution_count();
        $account_confirm_count = AppHelper::account_confirm_count();
        // FORM FOLDER ACCESS //
        $folderaccess_mgr_count = AppHelper::folderaccess_mgr_count();
        $folderaccess_it_count = AppHelper::folderaccess_it_count();
        $folderaccess_it_mgr_count = AppHelper::folderaccess_it_mgr_count();
        $folderaccess_execution_count = AppHelper::folderaccess_execution_count();
        $folderaccess_confirm_count = AppHelper::folderaccess_confirm_count();
        // FORM NEW FOLDER //
        $newfolder_mgr_count = AppHelper::newfolder_mgr_count();
        $newfolder_it_count = AppHelper::newfolder_it_count();
        $newfolder_it_mgr_count = AppHelper::newfolder_it_mgr_count();
        $newfolder_execution_count = AppHelper::newfolder_execution_count();
        $newfolder_confirm_count = AppHelper::newfolder_confirm_count();
        // FORM SOFTWARE //
        $software_mgr_count = AppHelper::software_mgr_count();
        $software_it_count = AppHelper::software_it_count();
        $software_it_mgr_count = AppHelper::software_it_mgr_count();
        $software_execution_count = AppHelper::software_execution_count();
        $software_confirm_count = AppHelper::software_confirm_count();
        // FORM HARDWARE //
        $hardware_mgr_count = AppHelper::hardware_mgr_count();
        $hardware_it_count = AppHelper::hardware_it_count();
        $hardware_it_mgr_count = AppHelper::hardware_it_mgr_count();
        $hardware_execution_count = AppHelper::hardware_execution_count();
        $hardware_confirm_count = AppHelper::hardware_confirm_count();
        // FORM VPN //
        $vpn_mgr_count = AppHelper::vpn_mgr_count();
        $vpn_it_count = AppHelper::vpn_it_count();
        $vpn_it_mgr_count = AppHelper::vpn_it_mgr_count();
        $vpn_execution_count = AppHelper::vpn_execution_count();
        $vpn_confirm_count = AppHelper::vpn_confirm_count();
        // FORM PROJECT //
        $project_mgr_count = AppHelper::project_mgr_count();
        $project_it_count = AppHelper::project_it_count();
        $project_it_mgr_count = AppHelper::project_it_mgr_count();
        $project_execution_count = AppHelper::project_execution_count();
        $project_confirm_count = AppHelper::project_confirm_count();
        // FORM FITUR //
        $fitur_mgr_count = AppHelper::fitur_mgr_count();
        $fitur_it_count = AppHelper::fitur_it_count();
        $fitur_it_mgr_count = AppHelper::fitur_it_mgr_count();
        $fitur_execution_count = AppHelper::fitur_execution_count();
        $fitur_confirm_count = AppHelper::fitur_confirm_count();
        // FORM RELAYOUT //
        $relayout_mgr_count = AppHelper::relayout_mgr_count();
        $relayout_it_count = AppHelper::relayout_it_count();
        $relayout_it_mgr_count = AppHelper::relayout_it_mgr_count();
        $relayout_execution_count = AppHelper::relayout_execution_count();
        $relayout_confirm_count = AppHelper::relayout_confirm_count();
        // FORM NETWORK //
        $network_mgr_count = AppHelper::network_mgr_count();
        $network_it_count = AppHelper::network_it_count();
        $network_it_mgr_count = AppHelper::network_it_mgr_count();
        $network_execution_count = AppHelper::network_execution_count();
        $network_confirm_count = AppHelper::network_confirm_count();
        // FORM AKSES SISTEM //
        $akses_sistem_mgr_count = AppHelper::akses_sistem_mgr_count();
        $akses_sistem_it_count = AppHelper::akses_sistem_it_count();
        $akses_sistem_it_mgr_count = AppHelper::akses_sistem_it_mgr_count();
        $akses_sistem_execution_count = AppHelper::akses_sistem_execution_count();
        $akses_sistem_confirm_count = AppHelper::akses_sistem_confirm_count();
        // FORM INCIDENT REPORT //
        $incident_report_mgr_count = AppHelper::incident_report_mgr_count();
        $incident_report_it_count = AppHelper::incident_report_it_count();
        $incident_report_it_mgr_count = AppHelper::incident_report_it_mgr_count();
        $incident_report_execution_count = AppHelper::incident_report_execution_count();
        $incident_report_confirm_count = AppHelper::incident_report_confirm_count();
        // FORM IZIN //
        $izin_mgr_count = AppHelper::izin_mgr_count();
        $izin_it_count = AppHelper::izin_it_count();
        $izin_it_mgr_count = AppHelper::izin_it_mgr_count();
        $izin_execution_count = AppHelper::izin_execution_count();
        $izin_confirm_count = AppHelper::izin_confirm_count();
        // FORM IT NEEDS //
        // $it_needs_mgr_count = AppHelper::it_needs_mgr_count();
        // $it_needs_it_count = AppHelper::it_needs_it_count();
        // $it_needs_it_mgr_count = AppHelper::it_needs_it_mgr_count();
        // $it_needs_execution_count = AppHelper::it_needs_execution_count();
        // $it_needs_confirm_count = AppHelper::it_needs_confirm_count();
        // ALL FORM //
        $manager_approvals_count = AppHelper::manager_approvals_count();
        $confirms_count = AppHelper::confirms_count();
        $it_approvals_count = AppHelper::it_approvals_count();
        $it_mgr_approvals_count = AppHelper::it_mgr_approvals_count();
        $execution_count = AppHelper::execution_count();
        
        return response()->json([
            'ticket_count' => $ticket_count,

            'account_mgr_count' => $account_mgr_count,
            'account_it_count' => $account_it_count,
            'account_it_mgr_count' => $account_it_mgr_count,
            'account_execution_count' => $account_execution_count,
            'account_confirm_count' => $account_confirm_count,
            
            'folderaccess_mgr_count' => $folderaccess_mgr_count,
            'folderaccess_it_count' => $folderaccess_it_count,
            'folderaccess_it_mgr_count' => $folderaccess_it_mgr_count,
            'folderaccess_execution_count' => $folderaccess_execution_count,
            'folderaccess_confirm_count' => $folderaccess_confirm_count,

            'newfolder_mgr_count' => $newfolder_mgr_count,
            'newfolder_it_count' => $newfolder_it_count,
            'newfolder_it_mgr_count' => $newfolder_it_mgr_count,
            'newfolder_execution_count' => $newfolder_execution_count,
            'newfolder_confirm_count' => $newfolder_confirm_count,

            'software_mgr_count' => $software_mgr_count,
            'software_it_count' => $software_it_count,
            'software_it_mgr_count' => $software_it_mgr_count,
            'software_execution_count' => $software_execution_count,
            'software_confirm_count' => $software_confirm_count,

            'hardware_mgr_count' => $hardware_mgr_count,
            'hardware_it_count' => $hardware_it_count,
            'hardware_it_mgr_count' => $hardware_it_mgr_count,
            'hardware_execution_count' => $hardware_execution_count,
            'hardware_confirm_count' => $hardware_confirm_count,

            'vpn_mgr_count' => $vpn_mgr_count,
            'vpn_it_count' => $vpn_it_count,
            'vpn_it_mgr_count' => $vpn_it_mgr_count,
            'vpn_execution_count' => $vpn_execution_count,
            'vpn_confirm_count' => $vpn_confirm_count,

            'project_mgr_count' => $project_mgr_count,
            'project_it_count' => $project_it_count,
            'project_it_mgr_count' => $project_it_mgr_count,
            'project_execution_count' => $project_execution_count,
            'project_confirm_count' => $project_confirm_count,

            'fitur_mgr_count' => $fitur_mgr_count,
            'fitur_it_count' => $fitur_it_count,
            'fitur_it_mgr_count' => $fitur_it_mgr_count,
            'fitur_execution_count' => $fitur_execution_count,
            'fitur_confirm_count' => $fitur_confirm_count,

            'relayout_mgr_count' => $relayout_mgr_count,
            'relayout_it_count' => $relayout_it_count,
            'relayout_it_mgr_count' => $relayout_it_mgr_count,
            'relayout_execution_count' => $relayout_execution_count,
            'relayout_confirm_count' => $relayout_confirm_count,

            'network_mgr_count' => $network_mgr_count,
            'network_it_count' => $network_it_count,
            'network_it_mgr_count' => $network_it_mgr_count,
            'network_execution_count' => $network_execution_count,
            'network_confirm_count' => $network_confirm_count,

            'akses_sistem_mgr_count' => $akses_sistem_mgr_count,
            'akses_sistem_it_count' => $akses_sistem_it_count,
            'akses_sistem_it_mgr_count' => $akses_sistem_it_mgr_count,
            'akses_sistem_execution_count' => $akses_sistem_execution_count,
            'akses_sistem_confirm_count' => $akses_sistem_confirm_count,

            'incident_report_mgr_count' => $incident_report_mgr_count,
            'incident_report_it_count' => $incident_report_it_count,
            'incident_report_it_mgr_count' => $incident_report_it_mgr_count,
            'incident_report_execution_count' => $incident_report_execution_count,
            'incident_report_confirm_count' => $incident_report_confirm_count,

            'izin_mgr_count' => $izin_mgr_count,
            'izin_it_count' => $izin_it_count,
            'izin_it_mgr_count' => $izin_it_mgr_count,
            'izin_execution_count' => $izin_execution_count,
            'izin_confirm_count' => $izin_confirm_count,

            // 'it_needs_mgr_count' => $it_needs_mgr_count,
            // 'it_needs_it_count' => $it_needs_it_count,
            // 'it_needs_it_mgr_count' => $it_needs_it_mgr_count,
            // 'it_needs_execution_count' => $it_needs_execution_count,
            // 'it_needs_confirm_count' => $it_needs_confirm_count,
            
            'manager_approvals_count' => $manager_approvals_count,
            'confirms_count' => $confirms_count,
            'it_approvals_count' => $it_approvals_count,
            'it_mgr_approvals_count' => $it_mgr_approvals_count,
            'execution_count' => $execution_count,
        ]);
    }
}
