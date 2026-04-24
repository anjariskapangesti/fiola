<?php

use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'can_create_form',
            'can_approve_mgr',
            'can_approve_it',
            'can_approve_it_mgr',
            'can_execution',
            'can_master',
            'can_approve_executives',
            'apps_fiola',
            'general',
            'approve_mgr',
            'approve_gm',
            'approve_dir',
            'approve_vp',
            'approve_pres',
            'ITDMGR',
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission, 'guard_name' => 'web'],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
