<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'administrator@aiia.co.id',
            'password' => Hash::make('Aisinbisa1'),
        ]);

        // Menetapkan departemen untuk pengguna Administrator
        // $admin->departments()->sync([$department->id, $model_type]);

        $departmentId = 9;
        $userId = 1;

        DB::table('model_has_departments')->insert([
            'model_type' => "App\Models\User",
            'model_id' => $userId,
            'department_id' => $departmentId,
        ]);

        $userIds = [1];
        $permissionIds = [1, 2, 3, 4, 5, 6]; 

        foreach ($userIds as $userId) {
            foreach ($permissionIds as $permissionId) {
                DB::table('model_has_permissions')->insert([
                    'model_type' => "App\Models\User",
                    'model_id' => $userId,
                    'permission_id' => $permissionId,
                ]);
            }
        }
    }
}
