<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FolderAccessSeeder extends Seeder
{
    public function run()
    {
        $user = DB::table('users')->first();
        if (!$user) return;

        DB::table('form_folder_access')->insert([
            [
                'no_reg' => 'FAC-' . date('Ymd') . '-001',
                'final_status' => 'Finished',
                'created_by' => $user->id,
                'created_at' => Carbon::now(),
            ]
        ]);
    }
}
