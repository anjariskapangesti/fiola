<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Department;

class NewFolderSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $departments = Department::all();

        if ($users->isEmpty() || $departments->isEmpty()) {
            return;
        }

        // Truncate existing data
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('form_new_folder_user')->truncate();
            DB::table('form_new_folder_path')->truncate();
            DB::table('form_new_folder')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } elseif (DB::getDriverName() === 'pgsql') {
            DB::statement('TRUNCATE TABLE form_new_folder_user, form_new_folder_path, form_new_folder RESTART IDENTITY CASCADE');
        } else {
            DB::table('form_new_folder_user')->truncate();
            DB::table('form_new_folder_path')->truncate();
            DB::table('form_new_folder')->truncate();
        }

        $userHRD = User::where('email', 'irfan.anshori@aiia.co.id')->first() ?: $users->random();
        $deptHRD = Department::where('id', 1)->first() ?: $departments->random();
        
        $mgrHRD = User::where('email', 'muhammad.hidayat@aiia.co.id')->first() ?: $users->random();
        $itStaff = User::where('email', 'alliq@aiia.co.id')->first() ?: $users->random();
        $itMgr = User::where('email', 'administrator@aiia.co.id')->first() ?: $users->random();

        $folders = [
            [
                'no_reg' => 'FLD-' . date('Ymd') . '-001',
                'purpose' => 'Confidential HR recruitment files',
                'final_status' => 'created',
                'created_by' => $userHRD->id,
                'created_dept' => $deptHRD->id,
                'paths' => [
                    ['foldername' => 'Recruitment_2024', 'mainpath' => '\\\\SERVER01\\HRD$\\Confidential']
                ],
                'users' => [
                    ['username' => 'Irfan Anshori', 'department' => 'HRD', 'permission' => 'Read/Write'],
                    ['username' => 'Khusni Setyawan', 'department' => 'HRD', 'permission' => 'Read']
                ]
            ],
            [
                'no_reg' => 'FLD-' . date('Ymd') . '-002',
                'purpose' => 'Project Documentation for ITD',
                'final_status' => 'IT Approve',
                'is_manager_approve' => true,
                'manager_approval_date' => Carbon::now()->subDays(2),
                'is_it_approve' => true,
                'it_approval_date' => Carbon::now()->subDays(1),
                'created_by' => $itStaff->id,
                'created_dept' => 9, // ITD
                'paths' => [
                    ['foldername' => 'Fiola_v2_Docs', 'mainpath' => '\\\\SERVER02\\ITD$\\Projects']
                ],
                'users' => [
                    ['username' => 'Alliq Nur Imanin Aji', 'department' => 'ITD', 'permission' => 'Read/Write'],
                    ['username' => 'Administrator', 'department' => 'ITD', 'permission' => 'Read/Write']
                ]
            ],
            [
                'no_reg' => 'FLD-' . date('Ymd') . '-003',
                'purpose' => 'Monthly Report Archive',
                'final_status' => 'Finished',
                'is_manager_approve' => true,
                'manager_approval_date' => Carbon::now()->subDays(10),
                'is_it_approve' => true,
                'it_approval_date' => Carbon::now()->subDays(9),
                'is_it_mgr_approve' => true,
                'it_mgr_approval_date' => Carbon::now()->subDays(8),
                'is_finish' => true,
                'finish_date' => Carbon::now()->subDays(7),
                'is_confirm' => true,
                'created_by' => $userHRD->id,
                'created_dept' => $deptHRD->id,
                'paths' => [
                    ['foldername' => 'Archive_2023', 'mainpath' => '\\\\SERVER01\\HRD$\\Public']
                ],
                'users' => [
                    ['username' => 'Public', 'department' => 'ALL', 'permission' => 'Read']
                ]
            ]
        ];

        foreach ($folders as $data) {
            $paths = $data['paths'];
            $users = $data['users'];
            unset($data['paths'], $data['users']);

            $folderId = DB::table('form_new_folder')->insertGetId(array_merge($data, [
                'created_at' => now(),
                'updated_at' => now()
            ]));

            foreach ($paths as $p) {
                DB::table('form_new_folder_path')->insert(array_merge($p, [
                    'new_folder_id' => $folderId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            }

            foreach ($users as $u) {
                DB::table('form_new_folder_user')->insert(array_merge($u, [
                    'new_folder_id' => $folderId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            }
        }
    }
}
