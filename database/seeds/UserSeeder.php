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
        $permissionsUser = [1];
        $permissionsMGR = [1, 2];
        $permissionsEXC = [1, 7];
        $permissionsITD = [1, 3, 5, 6];
        $permissionsITDMGR = [1, 2, 3, 4, 5, 6]; 

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
                'email' => 'administrator@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
            [
                'name' => 'Ferry Avianto',
                'email' => 'ferry@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],            
        ];

        foreach ($admins as $admin) {
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
                'email' => 'irfan.anshori@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
            [
                'name' => 'Khusni Setyawan',
                'email' => 'khusni@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],            
            [
                'name' => 'Indra Pasurya',
                'email' => 'indra@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
            [
                'name' => 'Ziyan Awaliyah Ritonga',
                'email' => 'ziyan@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
        ];

        foreach ($usersHRD as $userHRD) {
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
                'email' => 'n.maulita@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
            [
                'name' => 'Ahmad Rizky Rifai',
                'email' => 'ahmad.rifai@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
        ];

        foreach ($usersIRLGA as $userIRLGA) {
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
                'email' => 'corel@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
            [
                'name' => 'Valentsyach Rizqi Alfani',
                'email' => 'valent.alfani@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],            
            [
                'name' => 'Thoif Zara',
                'email' => 'thoif.zara@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
        ];

        foreach ($usersENGB as $userENGB) {
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
                'email' => 'lutfi@aiia.co.id',
                'password' => Hash::make('aiia'),
            ],
        ];

        foreach ($mgrsENGB as $mgrENGB) {
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
