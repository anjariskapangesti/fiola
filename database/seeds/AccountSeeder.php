<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Department;

class AccountSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $departments = Department::all();

        if ($users->isEmpty() || $departments->isEmpty()) {
            return;
        }

        $userHRD = User::where('email', 'irfan.anshori@aiia.co.id')->first() ?: $users->random();
        $deptHRD = Department::where('id', 1)->first() ?: $departments->random();
        
        $mgrHRD = User::where('email', 'muhammad.hidayat@aiia.co.id')->first() ?: $users->random();
        $itStaff = User::where('email', 'alliq@aiia.co.id')->first() ?: $users->random();
        $itMgr = User::where('email', 'administrator@aiia.co.id')->first() ?: $users->random();

        $accounts = [
            [
                'no_reg' => 'ACC-' . date('Ymd') . '-001',
                'budget_type' => 'CAPEX',
                'form_type' => 'New Account',
                'npk' => $userHRD->npk,
                'fullname' => $userHRD->name,
                'department' => $deptHRD->name,
                'phone' => $userHRD->nohp,
                'company' => 'AIIA',
                'expired_date' => Carbon::now()->addYear(),
                'ad_name' => 'irfan.anshori',
                'is_email' => true,
                'email_address' => 'irfan.anshori@aiia.co.id',
                'purpose' => 'New employee account setup',
                'final_status' => 'created',
                'is_manager_approve' => null,
                'is_it_approve' => null,
                'is_it_mgr_approve' => null,
                'is_on_progress' => null,
                'is_finish' => null,
                'is_confirm' => false,
                'created_by' => $userHRD->id,
                'created_dept' => $deptHRD->id,
            ],
            [
                'no_reg' => 'ACC-' . date('Ymd') . '-002',
                'budget_type' => 'OPEX',
                'form_type' => 'New Account',
                'npk' => '001084',
                'fullname' => 'Khusni Setyawan',
                'department' => $deptHRD->name,
                'phone' => '081234567900',
                'company' => 'AIIA',
                'expired_date' => Carbon::now()->addYear(),
                'ad_name' => 'khusni.setyawan',
                'is_email' => true,
                'email_address' => 'khusni@aiia.co.id',
                'purpose' => 'System access for HR operations',
                'final_status' => 'Manager Approve',
                'is_manager_approve' => true,
                'manager_approval_date' => Carbon::now()->subDays(2),
                'manager_approve_by' => $mgrHRD->id,
                'manager_note' => 'Approved for system access.',
                'is_it_approve' => null,
                'is_it_mgr_approve' => null,
                'is_on_progress' => null,
                'is_finish' => null,
                'is_confirm' => false,
                'created_by' => $userHRD->id,
                'created_dept' => $deptHRD->id,
            ],
            [
                'no_reg' => 'ACC-' . date('Ymd') . '-003',
                'budget_type' => 'CAPEX',
                'form_type' => 'New Account',
                'npk' => '000106',
                'fullname' => 'Indra Pasurya',
                'department' => $deptHRD->name,
                'phone' => '081234567901',
                'company' => 'AIIA',
                'expired_date' => Carbon::now()->addYear(),
                'ad_name' => 'indra.pasurya',
                'is_email' => false,
                'purpose' => 'AD Account only',
                'final_status' => 'IT Approve',
                'is_manager_approve' => true,
                'manager_approval_date' => Carbon::now()->subDays(3),
                'manager_approve_by' => $mgrHRD->id,
                'is_it_approve' => true,
                'it_approval_date' => Carbon::now()->subDays(1),
                'it_approve_by' => $itStaff->id,
                'it_note' => 'Technical validation complete.',
                'is_it_mgr_approve' => null,
                'is_on_progress' => null,
                'is_finish' => null,
                'is_confirm' => false,
                'created_by' => $userHRD->id,
                'created_dept' => $deptHRD->id,
            ],
            [
                'no_reg' => 'ACC-' . date('Ymd') . '-004',
                'budget_type' => 'CAPEX',
                'form_type' => 'New Account',
                'npk' => '002168',
                'fullname' => 'Ziyan Awaliyah Ritonga',
                'department' => $deptHRD->name,
                'phone' => '081234567902',
                'company' => 'AIIA',
                'expired_date' => Carbon::now()->addYear(),
                'ad_name' => 'ziyan.ritonga',
                'is_email' => true,
                'email_address' => 'ziyan@aiia.co.id',
                'purpose' => 'Full access for GA',
                'final_status' => 'IT MGR Approve',
                'is_manager_approve' => true,
                'manager_approval_date' => Carbon::now()->subDays(4),
                'manager_approve_by' => $mgrHRD->id,
                'is_it_approve' => true,
                'it_approval_date' => Carbon::now()->subDays(3),
                'it_approve_by' => $itStaff->id,
                'is_it_mgr_approve' => true,
                'it_mgr_approval_date' => Carbon::now()->subDays(2),
                'it_mgr_approve_by' => $itMgr->id,
                'it_mgr_note' => 'Ready for execution.',
                'is_on_progress' => null,
                'is_finish' => null,
                'is_confirm' => false,
                'created_by' => $userHRD->id,
                'created_dept' => $deptHRD->id,
            ],
            [
                'no_reg' => 'ACC-' . date('Ymd') . '-005',
                'budget_type' => 'CAPEX',
                'form_type' => 'New Account',
                'npk' => '000567',
                'fullname' => 'Ahmad Rizky Rifai',
                'department' => $deptHRD->name,
                'phone' => '081234567903',
                'company' => 'AIIA',
                'expired_date' => Carbon::now()->addYear(),
                'ad_name' => 'ahmad.rifai',
                'is_email' => true,
                'email_address' => 'ahmad.rifai@aiia.co.id',
                'purpose' => 'Email and AD',
                'final_status' => 'Finished',
                'is_manager_approve' => true,
                'manager_approval_date' => Carbon::now()->subDays(10),
                'manager_approve_by' => $mgrHRD->id,
                'is_it_approve' => true,
                'it_approval_date' => Carbon::now()->subDays(9),
                'it_approve_by' => $itStaff->id,
                'is_it_mgr_approve' => true,
                'it_mgr_approval_date' => Carbon::now()->subDays(8),
                'it_mgr_approve_by' => $itMgr->id,
                'is_on_progress' => true,
                'on_progress_date' => Carbon::now()->subDays(7),
                'on_progress_by' => $itStaff->id,
                'is_finish' => true,
                'finish_date' => Carbon::now()->subDays(5),
                'finish_by' => $itStaff->id,
                'finish_note' => 'Account created and credentials sent.',
                'is_confirm' => true,
                'created_by' => $userHRD->id,
                'created_dept' => $deptHRD->id,
            ],
            [
                'no_reg' => 'ACC-' . date('Ymd') . '-006',
                'budget_type' => 'CAPEX',
                'form_type' => 'New Account',
                'npk' => '002304',
                'fullname' => 'Nikmatul Maulita',
                'department' => 'IRLGA',
                'phone' => '081234567904',
                'company' => 'AIIA',
                'purpose' => 'Rejected test',
                'final_status' => 'Manager Reject',
                'is_manager_approve' => false,
                'manager_approval_date' => Carbon::now()->subDays(1),
                'manager_approve_by' => $mgrHRD->id,
                'manager_note' => 'Incomplete documents.',
                'created_by' => $userHRD->id,
                'created_dept' => $deptHRD->id,
            ],
        ];

        foreach ($accounts as $account) {
            DB::table('form_account')->updateOrInsert(
                ['no_reg' => $account['no_reg']],
                array_merge($account, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
