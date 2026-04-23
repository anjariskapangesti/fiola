<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->insert([
        //     'name' => 'Administrator',
        //     'email' => 'administrator@aiia.co.id',
        //     'password' => Hash::make('aiia'),
        // ]);

        // // Menetapkan departemen untuk pengguna Administrator
        // // $admin->departments()->sync([$department->id, $model_type]);

        // $departmentId = 9;
        // $userId = 1;

        // DB::table('model_has_departments')->insert([
        //     'model_type' => "App\Models\User",
        //     'model_id' => $userId,
        //     'department_id' => $departmentId,
        // ]);

        // $userIds = [1];
        // $permissionIds = [1, 2, 3, 4, 5, 6]; 

        // foreach ($userIds as $userId) {
        //     foreach ($permissionIds as $permissionId) {
        //         DB::table('model_has_permissions')->insert([
        //             'model_type' => "App\Models\User",
        //             'model_id' => $userId,
        //             'permission_id' => $permissionId,
        //         ]);
        //     }
        // }
        /// ITD ///
        $permCreateForm = DB::table('permissions')->where('name', 'can_create_form')->value('id');
        $permApproveMgr = DB::table('permissions')->where('name', 'can_approve_mgr')->value('id');
        $permApproveIt = DB::table('permissions')->where('name', 'can_approve_it')->value('id');
        $permApproveItMgr = DB::table('permissions')->where('name', 'can_approve_it_mgr')->value('id');
        $permExecution = DB::table('permissions')->where('name', 'can_execution')->value('id');
        $permMaster = DB::table('permissions')->where('name', 'can_master')->value('id');
        $permApproveExc = DB::table('permissions')->where('name', 'can_approve_executives')->value('id');
        $permAppsFiola = DB::table('permissions')->where('name', 'apps_fiola')->value('id');
        $permGeneral = DB::table('permissions')->where('name', 'general')->value('id');

        $permissionsUser = [$permCreateForm, $permGeneral, $permAppsFiola];
        $permissionsMGR = [$permCreateForm, $permApproveMgr, $permGeneral, $permAppsFiola];
        $permissionsEXC = [$permCreateForm, $permApproveExc, $permGeneral, $permAppsFiola];
        $permissionsITD = [$permCreateForm, $permApproveIt, $permExecution, $permMaster, $permAppsFiola];
        $permissionsITDMGR = [$permCreateForm, $permApproveMgr, $permApproveIt, $permApproveItMgr, $permExecution, $permMaster, $permAppsFiola];

        $departmentHRD = 1;
        $departmentIRLGA = 2;
        $departmentENGB = 3;
        $departmentENGU = 4;
        $departmentMTE = 5;
        $departmentQAB = 6;
        $departmentMS = 7;
        $departmentQAU = 8;
        $departmentITD = 9;
        $departmentPRDUDC = 10;
        $departmentPRDUMA = 11;
        $departmentPRDB = 12;
        $departmentPSD = 13;
        $departmentPPIC = 14;
        $departmentENGQAE = 15;
        $departmentMTEE = 16;
        $departmentPRDE = 17;
        $departmentPPICE = 18;
        $departmentOTHER = 19;
        $departmentEXC = 20;

        /// Admin ///
        $admins = [
            [
                'name' => 'Administrator',
                'npk' => '000000',
                'email' => 'administrator@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
            [
                'name' => 'Ferry Avianto',
                'npk' => '000017',
                'email' => 'ferry@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],            
        ];

        foreach ($admins as $admin) {
            $admin['company'] = 'AIIA';
            $admindept = DB::table('users')->insertGetId($admin);
    
            // Menetapkan departemen untuk pengguna
            
            DB::table('model_has_departments')->insert([
                'model_type' => "App\Models\User",
                'model_id' => $admindept,
                'department_id' => $departmentITD,
            ]);
    
            foreach ($permissionsITDMGR as $permissionITDMGR) {
                DB::table('model_has_permissions')->insert([
                    'model_type' => "App\Models\User",
                    'model_id' => $admindept,
                    'permission_id' => $permissionITDMGR,
                ]);
            }
        }

        /// ITD INFRA ///

        $usersITD = [
            [
                'name' => 'Alliq Nur Imanin Aji',
                'email' => 'alliq@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
            [
                'name' => 'Rohmat Maulana Ishaq',
                'email' => 'rohmat@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
            [
                'name' => 'Muhammad Diki Dwi Nugraha',
                'email' => 'diki@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
            
        ];
    
        foreach ($usersITD as $userITD) {
            $userITD['company'] = 'AIIA';
            $userITDdept = DB::table('users')->insertGetId($userITD);
    
            // Menetapkan departemen untuk pengguna
            
            DB::table('model_has_departments')->insert([
                'model_type' => "App\Models\User",
                'model_id' => $userITDdept,
                'department_id' => $departmentITD,
            ]);
    
            foreach ($permissionsITD as $perminissionITD) {
                DB::table('model_has_permissions')->insert([
                    'model_type' => "App\Models\User",
                    'model_id' => $userITDdept,
                    'permission_id' => $perminissionITD,
                ]);
            }
        }

        /// USER ITD ///
        $usersITD = [
            [
                'name' => 'Imam Mahfud',
                'email' => 'imam@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
            [
                'name' => 'Rafie Afif Andika',
                'email' => 'rafie@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],            
            [
                'name' => 'Mitsal Fabian Nadhiem',
                'email' => 'fabian@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
            [
                'name' => 'Handika',
                'email' => 'handika@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
        ];

        foreach ($usersITD as $userITD) {
            $userITD['company'] = 'AIIA';
            $userITDdept = DB::table('users')->insertGetId($userITD);
    
            // Menetapkan departemen untuk pengguna
            
            DB::table('model_has_departments')->insert([
                'model_type' => "App\Models\User",
                'model_id' => $userITDdept,
                'department_id' => $departmentITD,
            ]);
    
            foreach ($permissionsUser as $permissionUser) {
                DB::table('model_has_permissions')->insert([
                    'model_type' => "App\Models\User",
                    'model_id' => $userITDdept,
                    'permission_id' => $permissionUser,
                ]);
            }
        }
        
        /// USER HRD ///
        $usersHRD = [
            [
                'name' => 'Irfan Anshori',
                'npk' => '002530',
                'email' => 'irfan.anshori@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
            [
                'name' => 'Khusni Setyawan',
                'npk' => '001084',
                'email' => 'khusni@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],            
            [
                'name' => 'Indra Pasurya',
                'npk' => '000106',
                'email' => 'indra@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
            [
                'name' => 'Ziyan Awaliyah Ritonga',
                'npk' => '002168',
                'email' => 'ziyan@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
            [
                'name' => 'Ahmad Rizky Rifai',
                'npk' => '000567',
                'email' => 'ahmad.rifai@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
        ];

        foreach ($usersHRD as $userHRD) {
            $userHRD['company'] = 'AIIA';
            $userHRDdept = DB::table('users')->insertGetId($userHRD);
    
            // Menetapkan departemen untuk pengguna
            
            DB::table('model_has_departments')->insert([
                'model_type' => "App\Models\User",
                'model_id' => $userHRDdept,
                'department_id' => $departmentHRD,
            ]);
    
            foreach ($permissionsUser as $permissionUser) {
                DB::table('model_has_permissions')->insert([
                    'model_type' => "App\Models\User",
                    'model_id' => $userHRDdept,
                    'permission_id' => $permissionUser,
                ]);
            }
        }

        /// USER IRLGA ///
        $usersIRLGA = [            
            [
                'name' => 'Nikmatul Maulita',
                'npk' => '002304',
                'email' => 'n.maulita@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
        ];

        foreach ($usersIRLGA as $userIRLGA) {
            $userIRLGA['company'] = 'AIIA';
            $userIRLGAdept = DB::table('users')->insertGetId($userIRLGA);
    
            // Menetapkan departemen untuk pengguna
            
            DB::table('model_has_departments')->insert([
                'model_type' => "App\Models\User",
                'model_id' => $userIRLGAdept,
                'department_id' => $departmentIRLGA,
            ]);
    
            foreach ($permissionsUser as $permissionUser) {
                DB::table('model_has_permissions')->insert([
                    'model_type' => "App\Models\User",
                    'model_id' => $userIRLGAdept,
                    'permission_id' => $permissionUser,
                ]);
            }
        }

        /// MGR HRD IRLGA ///
        $mgrsHRDIRLGA = [
            [
                'name' => 'Muhammad Hidayat Martin',
                'email' => 'muhammad.hidayat@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
        ];

        foreach ($mgrsHRDIRLGA as $mgrHRDIRLGA) {
            $mgrHRDIRLGA['company'] = 'AIIA';
            $mgrHRDIRLGAdept = DB::table('users')->insertGetId($mgrHRDIRLGA);
    
            // Menetapkan departemen untuk pengguna
            
            DB::table('model_has_departments')->insert([
                'model_type' => "App\Models\User",
                'model_id' => $mgrHRDIRLGAdept,
                'department_id' => $departmentHRD,
            ]);
        
            DB::table('model_has_departments')->insert([
                'model_type' => "App\Models\User",
                'model_id' => $mgrHRDIRLGAdept,
                'department_id' => $departmentIRLGA,
            ]);
    
            foreach ($permissionsMGR as $permissionMGR) {
                DB::table('model_has_permissions')->insert([
                    'model_type' => "App\Models\User",
                    'model_id' => $mgrHRDIRLGAdept,
                    'permission_id' => $permissionMGR,
                ]);
            }
        }

        /// USER ENGB ///
        $usersENGB = [
            [
                'name' => 'Corel Harnowo',
                'npk' => '002404',
                'email' => 'corel@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
            [
                'name' => 'Valentsyach Rizqi Alfani',
                'npk' => '002417',
                'email' => 'valent.alfani@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],            
            [
                'name' => 'Muhammad Naza Syaifullah',
                'npk' => '002651',
                'password' => Hash::make('aiia'),
            ],
        ];

        foreach ($usersENGB as $userENGB) {
            $userENGB['company'] = 'AIIA';
            $userENGBdept = DB::table('users')->insertGetId($userENGB);
            
            DB::table('model_has_departments')->insert([
                'model_type' => "App\Models\User",
                'model_id' => $userENGBdept,
                'department_id' => $departmentENGB,
            ]);
    
            foreach ($permissionsUser as $permissionUser) {
                DB::table('model_has_permissions')->insert([
                    'model_type' => "App\Models\User",
                    'model_id' => $userENGBdept,
                    'permission_id' => $permissionUser,
                ]);
            }
        }

        /// MGR ENGB ///
        $mgrsENGB = [
            [
                'name' => 'Lutfi Dahlan',
                'npk' => '000023',
                'email' => 'lutfi@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
        ];

        foreach ($mgrsENGB as $mgrENGB) {
            $mgrENGB['company'] = 'AIIA';
            $mgrENGBdept = DB::table('users')->insertGetId($mgrENGB);
    
            // Menetapkan departemen untuk pengguna
            
            DB::table('model_has_departments')->insert([
                'model_type' => "App\Models\User",
                'model_id' => $mgrENGBdept,
                'department_id' => $departmentENGB,
            ]);
        
    
            foreach ($permissionsMGR as $permissionMGR) {
                DB::table('model_has_permissions')->insert([
                    'model_type' => "App\Models\User",
                    'model_id' => $mgrENGBdept,
                    'permission_id' => $permissionMGR,
                ]);
            }
        }        

        
    }
}
