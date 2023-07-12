<?php

use Illuminate\Database\Seeder;

class SubFolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subfolders')->insert([
            'folder_id' => 1,
            'name' => '01_Official_Report',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('subfolders')->insert([
            'folder_id' => 1,
            'name' => '05_Official_Meeting',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('subfolders')->insert([
            'folder_id' => 2,
            'name' => '01_President_Director',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('subfolders')->insert([
            'folder_id' => 2,
            'name' => '05_Manager',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('subfolders')->insert([
            'folder_id' => 3,
            'name' => '01_HR',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('subfolders')->insert([
            'folder_id' => 4,
            'name' => '05_PPIC',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('subfolders')->insert([
            'folder_id' => 5,
            'name' => '06_ITD',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('subfolders')->insert([
            'folder_id' => 6,
            'name' => '99_Public_Folder',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
