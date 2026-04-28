<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = DB::table('users')->where('npk', '000000')->first();
        $user = DB::table('users')->where('npk', '002530')->first(); // Irfan Anshori
        $manager = DB::table('users')->where('email', 'muhammad.hidayat@aiia.co.id')->first();
        
        if (!$admin || !$user) {
            return;
        }

        $now = Carbon::now();

        // Row 1: Active Finished Project
        $projectId = DB::table('form_project')->insertGetId([
            'no_reg' => 'PRJ-' . $now->format('Ymd') . '-001',
            'npk' => $admin->npk,
            'fullname' => $admin->name,
            'department' => 'ITD',
            'nama_project' => 'Main Dashboard Project A',
            'start_date' => $now->copy()->startOfMonth()->format('Y-m-d'),
            'end_date' => $now->copy()->startOfMonth()->addDays(14)->format('Y-m-d'),
            'final_status' => 'Finished',
            'is_timeline_active' => true,
            'timeline_order' => 1,
            'created_by' => $admin->id,
            'created_dept' => 9, // ITD
            'created_at' => Carbon::now(),
            'benefit' => 'Improving system stability',
            'kondisi_sebelum' => 'System often crashes',
            'kondisi_target' => 'System 99.9% uptime',
        ]);

        // Row 2: Active On Progress Project
        DB::table('form_project')->insert([
            'no_reg' => 'PRJ-' . $now->copy()->format('Ymd') . '-002',
            'npk' => $user->npk,
            'fullname' => $user->name,
            'department' => 'HRD',
            'nama_project' => 'Employee Portal Upgrade',
            'start_date' => $now->copy()->startOfMonth()->addDays(15)->format('Y-m-d'),
            'end_date' => $now->copy()->endOfMonth()->format('Y-m-d'),
            'final_status' => 'On Progress',
            'is_timeline_active' => true,
            'timeline_order' => 2,
            'created_by' => $user->id,
            'created_dept' => 1, // HRD
            'created_at' => Carbon::now(),
            'benefit' => 'Better employee engagement',
            'kondisi_sebelum' => 'Old portal is slow',
            'kondisi_target' => 'New responsive portal',
        ]);

        // Row 3: Pending Reschedule Request
        DB::table('form_project')->insert([
            'no_reg' => 'PRJ-' . $now->copy()->format('Ymd') . '-003',
            'npk' => $user->npk,
            'fullname' => $user->name,
            'department' => 'HRD',
            'nama_project' => 'Urgent HR System Patch',
            'start_date' => $now->copy()->startOfMonth()->addDays(10)->format('Y-m-d'),
            'end_date' => $now->copy()->startOfMonth()->addDays(20)->format('Y-m-d'),
            'final_status' => 'Manager Reject (Reschedule)',
            'is_reschedule' => 1,
            'reschedule_target_id' => $projectId, 
            'is_timeline_active' => false,
            'is_manager_approve' => 0,
            'manager_note' => 'I prefer the other one but Director should decide.',
            'manager_approve_by' => $manager->id,
            'manager_approval_date' => Carbon::now(),
            'created_by' => $user->id,
            'created_dept' => 1,
            'created_at' => Carbon::now(),
            'benefit' => 'Fixing security vulnerabilities',
            'kondisi_sebelum' => 'Vulnerable to SQLi',
            'kondisi_target' => 'Patched and secured',
        ]);

        // Row 4: Project for next month
        $nextMonthProject = DB::table('form_project')->insertGetId([
            'no_reg' => 'PRJ-' . $now->copy()->addMonth()->format('Ymd') . '-001',
            'npk' => $admin->npk,
            'fullname' => $admin->name,
            'department' => 'ITD',
            'nama_project' => 'Network Infrastructure Overhaul',
            'start_date' => $now->copy()->addMonth()->startOfMonth()->format('Y-m-d'),
            'end_date' => $now->copy()->addMonth()->endOfMonth()->format('Y-m-d'),
            'final_status' => 'IT MGR Approve',
            'is_timeline_active' => true,
            'timeline_order' => 3,
            'created_by' => $admin->id,
            'created_dept' => 9,
            'created_at' => Carbon::now(),
            'benefit' => 'Faster network speeds',
            'kondisi_sebelum' => '1Gbps backbone',
            'kondisi_target' => '10Gbps backbone',
        ]);

        // Row 5: PENDING RESCHEDULE REQUEST (Waiting for Irfan Anshori to respond)
        // Irfan owns PRJ-...-002 (Employee Portal Upgrade)
        $targetProject = DB::table('form_project')->where('no_reg', 'PRJ-' . $now->copy()->format('Ymd') . '-002')->first();
        if ($targetProject) {
            DB::table('form_project')->insert([
                'no_reg' => 'PRJ-' . $now->copy()->format('Ymd') . '-004',
                'npk' => $admin->npk,
                'fullname' => $admin->name,
                'department' => 'ITD',
                'nama_project' => 'AI Integration for Sales',
                'start_date' => $now->copy()->startOfMonth()->addDays(5)->format('Y-m-d'),
                'end_date' => $now->copy()->startOfMonth()->addDays(25)->format('Y-m-d'),
                'final_status' => 'Waiting Target Response',
                'is_reschedule' => 1,
                'reschedule_target_id' => $targetProject->id,
                'target_response' => 'pending',
                'is_timeline_active' => false,
                'created_by' => $admin->id,
                'created_dept' => 9,
                'created_at' => Carbon::now(),
                'benefit' => 'Automate sales predictions',
                'kondisi_sebelum' => 'Manual Excel',
                'kondisi_target' => 'AI Automated',
            ]);
        }

        // Row 6: RESCHEDULE ACCEPTED BY TARGET (Waiting for Manager Approval)
        $anotherAdminProject = DB::table('form_project')->where('no_reg', 'PRJ-' . $now->copy()->format('Ymd') . '-001')->first();
        if ($anotherAdminProject) {
            DB::table('form_project')->insert([
                'no_reg' => 'PRJ-' . $now->copy()->format('Ymd') . '-005',
                'npk' => $user->npk,
                'fullname' => $user->name,
                'department' => 'HRD',
                'nama_project' => 'Payroll System Migration',
                'start_date' => $now->copy()->startOfMonth()->format('Y-m-d'),
                'end_date' => $now->copy()->startOfMonth()->addDays(20)->format('Y-m-d'),
                'final_status' => 'created', // This means it's waiting for Manager
                'is_reschedule' => 1,
                'reschedule_target_id' => $anotherAdminProject->id,
                'target_response' => 'yes',
                'target_reschedule_start_date' => $now->copy()->addYear()->startOfMonth()->format('Y-m-d'),
                'target_reschedule_end_date' => $now->copy()->addYear()->startOfMonth()->addDays(14)->format('Y-m-d'),
                'is_timeline_active' => false,
                'created_by' => $user->id,
                'created_dept' => 1,
                'created_at' => Carbon::now(),
                'benefit' => 'More secure payroll',
                'kondisi_sebelum' => 'Legacy server',
                'kondisi_target' => 'Cloud native',
            ]);
        }

        // Row 7: RESCHEDULE DECLINED BY TARGET (But still proceeds to Manager/Director)
        if ($nextMonthProject) {
            DB::table('form_project')->insert([
                'no_reg' => 'PRJ-' . $now->copy()->addMonth()->format('Ymd') . '-002',
                'npk' => $user->npk,
                'fullname' => $user->name,
                'department' => 'HRD',
                'nama_project' => 'LMS Implementation',
                'start_date' => $now->copy()->addMonth()->startOfMonth()->addDays(5)->format('Y-m-d'),
                'end_date' => $now->copy()->addMonth()->startOfMonth()->addDays(15)->format('Y-m-d'),
                'final_status' => 'created',
                'is_reschedule' => 1,
                'reschedule_target_id' => $nextMonthProject,
                'target_response' => 'no',
                'is_timeline_active' => false,
                'created_by' => $user->id,
                'created_dept' => 1,
                'created_at' => Carbon::now(),
                'benefit' => 'Centralized learning',
                'kondisi_sebelum' => 'Offline training',
                'kondisi_target' => 'Digital LMS',
            ]);
        }
        // Row 8: READY FOR DIRECTOR APPROVAL (Reschedule)
        if ($targetProject) {
            DB::table('form_project')->insert([
                'no_reg' => 'PRJ-' . $now->copy()->format('Ymd') . '-006',
                'npk' => $user->npk,
                'fullname' => $user->name,
                'department' => 'HRD',
                'nama_project' => 'E-Learning Content Pack',
                'start_date' => $now->copy()->startOfMonth()->addDays(2)->format('Y-m-d'),
                'end_date' => $now->copy()->startOfMonth()->addDays(22)->format('Y-m-d'),
                'final_status' => 'IT MGR Approve', // Status that Director looks for
                'is_reschedule' => 1,
                'reschedule_target_id' => $targetProject->id,
                'target_response' => 'yes',
                'target_reschedule_start_date' => $now->copy()->addYear()->startOfMonth()->addDays(15)->format('Y-m-d'),
                'target_reschedule_end_date' => $now->copy()->addYear()->startOfMonth()->addDays(25)->format('Y-m-d'),
                'is_timeline_active' => false,
                'is_manager_approve' => 1,
                'manager_approval_date' => $now->copy()->subDays(2),
                'is_it_mgr_approve' => 1,
                'it_mgr_approval_date' => $now->copy()->subDays(1),
                'created_by' => $user->id,
                'created_dept' => 1,
                'created_at' => Carbon::now(),
                'benefit' => 'More content for training',
                'kondisi_sebelum' => 'Limited content',
                'kondisi_target' => 'Rich multimedia content',
            ]);
        }
    }
}
