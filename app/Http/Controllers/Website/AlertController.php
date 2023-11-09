<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

use App\Mail\AlertMail;

use Illuminate\Support\Facades\Mail;

class AlertController extends Controller
{
    public function alert()
    {
        $account_hrdga_wait_mgr_count = Account::where('final_status', 'created')->where('created_dept', 1)->count();
        $account_irl_wait_mgr_count = Account::where('final_status', 'created')->where('created_dept', 2)->count();
        $account_enb_wait_mgr_count = Account::where('final_status', 'created')->where('created_dept', 3)->count();
        $account_enu_wait_mgr_count = Account::where('final_status', 'created')->where('created_dept', 4)->count();
        $account_mte_wait_mgr_count = Account::where('final_status', 'created')->where('created_dept', 5)->count();
        $account_qab_wait_mgr_count = Account::where('final_status', 'created')->where('created_dept', 6)->count();
        $account_msy_wait_mgr_count = Account::where('final_status', 'created')->where('created_dept', 7)->count();
        $account_qau_wait_mgr_count = Account::where('final_status', 'created')->where('created_dept', 8)->count();
        $account_itd_wait_mgr_count = Account::where('final_status', 'created')->where('created_dept', 9)->count();
        $account_prounitdc_wait_mgr_count = Account::where('final_status', 'created')->where('created_dept', 10)->count();
        $account_prounitma_wait_mgr_count = Account::where('final_status', 'created')->where('created_dept', 11)->count();
        $account_probody_wait_mgr_count = Account::where('final_status', 'created')->where('created_dept', 12)->count();
        $account_psd_wait_mgr_count = Account::where('final_status', 'created')->where('created_dept', 13)->count();
        $account_ppic_wait_mgr_count = Account::where('final_status', 'created')->where('created_dept', 14)->count();
        $account_eqec_wait_mgr_count = Account::where('final_status', 'created')->where('created_dept', 15)->count();
        $account_mma_wait_mgr_count = Account::where('final_status', 'created')->where('created_dept', 16)->count();
        $account_proec_wait_mgr_count = Account::where('final_status', 'created')->where('created_dept', 17)->count();
        $account_ppicec_wait_mgr_count = Account::where('final_status', 'created')->where('created_dept', 18)->count();

        $qab_count = $account_qab_wait_mgr_count;
        
        if ($qab_count > 0) {
            $to = 'diki@aiia.co.id';
            $subject = 'FIOLA (Form ITD Online Application)';
            $data = ['message' => 'Ada tunggu approve'];
            $url = 'https://fiola.aiia.co.id';
            
            Mail::to($to)->send(new AlertMail($data, $subject, $url));

            return "Email terkirim!";
        } else {
            return "Tidak ada tunggu approve";
        }

        if ($hardware_it_count > 0) {
            $to = 'diki@aiia.co.id';
            $subject = 'FIOLA (Form ITD Online Application)';
            $data = ['message' => 'Ada tunggu approve'];
            $url = 'https://fiola.aiia.co.id';
            
            Mail::to($to)->send(new AlertMail($data, $subject, $url));

            return "Email terkirim!";
        } else {
            return "Tidak ada tunggu approve";
        }
    }

    public function alert_view()
    {
        return view('website.pages.emails.alert_view');
    }
}
