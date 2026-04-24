<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Department;

class SistemSeeder extends Seeder
{
    public function run()
    {
        // Truncate existing data to prevent duplicates
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('form_sistem_user')->truncate();
            DB::table('form_sistem_app')->truncate();
            DB::table('form_sistem')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } elseif (DB::getDriverName() === 'pgsql') {
            DB::statement('TRUNCATE TABLE form_sistem_user, form_sistem_app, form_sistem RESTART IDENTITY CASCADE');
        } else {
            DB::table('form_sistem_user')->truncate();
            DB::table('form_sistem_app')->truncate();
            DB::table('form_sistem')->truncate();
        }

        $users = User::all();
        $departments = Department::all();

        if ($users->isEmpty() || $departments->isEmpty()) {
            return;
        }

        $userIT = User::where('email', 'alliq@aiia.co.id')->first() ?: $users->random();
        $deptIT = Department::where('id', 9)->first() ?: $departments->random();
        
        $mgrIT = User::where('email', 'administrator@aiia.co.id')->first() ?: $users->random();
        $staff1 = User::where('email', 'rohmat@aiia.co.id')->first() ?: $users->random();
        $staff2 = User::where('email', 'diki@aiia.co.id')->first() ?: $users->random();

        $sistems = [
            [
                'no_reg' => 'SIS-' . date('Ymd') . '-001',
                'purpose' => 'Request access for new development environment',
                'final_status' => 'created',
                'created_by' => $userIT->id,
                'created_dept' => $deptIT->id,
                'users' => [
                    ['npk' => $userIT->npk, 'name' => $userIT->name, 'email' => $userIT->email, 'department' => $deptIT->name],
                ],
                'apps' => ['Jira', 'Confluence', 'Bitbucket']
            ],
            [
                'no_reg' => 'SIS-' . date('Ymd') . '-002',
                'purpose' => 'Production server access for maintenance',
                'final_status' => 'Manager Approve',
                'is_manager_approve' => true,
                'manager_approval_date' => Carbon::now()->subDays(1),
                'manager_approve_by' => $mgrIT->id,
                'manager_note' => 'Approved for temporary maintenance.',
                'created_by' => $staff1->id,
                'created_dept' => $deptIT->id,
                'users' => [
                    ['npk' => $staff1->npk, 'name' => $staff1->name, 'email' => $staff1->email, 'department' => $deptIT->name],
                    ['npk' => $staff2->npk, 'name' => $staff2->name, 'email' => $staff2->email, 'department' => $deptIT->name],
                ],
                'apps' => ['AWS Console', 'Production DB', 'Grafana']
            ],
            [
                'no_reg' => 'SIS-' . date('Ymd') . '-003',
                'purpose' => 'ERP System access for Finance audit',
                'final_status' => 'Finished',
                'is_manager_approve' => true,
                'manager_approval_date' => Carbon::now()->subDays(5),
                'manager_approve_by' => $mgrIT->id,
                'is_it_approve' => true,
                'it_approval_date' => Carbon::now()->subDays(4),
                'it_approve_by' => $mgrIT->id,
                'is_it_mgr_approve' => true,
                'it_mgr_approval_date' => Carbon::now()->subDays(3),
                'it_mgr_approve_by' => $mgrIT->id,
                'is_on_progress' => true,
                'on_progress_date' => Carbon::now()->subDays(2),
                'on_progress_by' => $userIT->id,
                'is_finish' => true,
                'finish_date' => Carbon::now()->subDays(1),
                'finish_by' => $userIT->id,
                'finish_note' => 'Access granted for 2 weeks audit period.',
                'is_confirm' => true,
                'created_by' => $staff2->id,
                'created_dept' => $deptIT->id,
                'users' => [
                    ['npk' => $staff2->npk, 'name' => $staff2->name, 'email' => $staff2->email, 'department' => $deptIT->name],
                ],
                'apps' => ['SAP Finance', 'Bank Statement Portal']
            ],
        ];

        foreach ($sistems as $data) {
            $users = $data['users'];
            $apps = $data['apps'];
            unset($data['users'], $data['apps']);

            $data['uuid'] = (string) Str::uuid();
            $data['created_at'] = now();
            $data['updated_at'] = now();

            $sistemId = DB::table('form_sistem')->insertGetId($data);

            foreach ($users as $u) {
                DB::table('form_sistem_user')->insert([
                    'sistem_id' => $sistemId,
                    'npk' => $u['npk'] ?? '000000',
                    'name' => $u['name'],
                    'email' => $u['email'],
                    'department' => $u['department'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            foreach ($apps as $app) {
                DB::table('form_sistem_app')->insert([
                    'sistem_id' => $sistemId,
                    'app_name' => $app,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
