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
        DB::table('permissions')->insert([
            'name' => 'can_approve_mgr',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        DB::table('permissions')->insert([
            'name' => 'can_approve_it',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('permissions')->insert([
            'name' => 'can_approve_it_mgr',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('permissions')->insert([
            'name' => 'can_execution',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('permissions')->insert([
            'name' => 'can_master',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
