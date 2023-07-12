<?php

use Illuminate\Database\Seeder;

class FolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('folders')->insert([
            'name' => '01_AIIA_BUSINESS',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('folders')->insert([
            'name' => '02_EXECUTIVES',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('folders')->insert([
            'name' => '03_ADMINISTRATION',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('folders')->insert([
            'name' => '04_PRODUCTION',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('folders')->insert([
            'name' => '05_ENGINEERING',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('folders')->insert([
            'name' => '99_PUBLIC_FOLDER',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
