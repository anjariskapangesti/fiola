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
        DB::table('form_project')->insert([
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
    }
}
