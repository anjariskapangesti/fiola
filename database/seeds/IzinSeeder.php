<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IzinSeeder extends Seeder
{
    public function run()
    {
        $user = DB::table('users')->first();
        if (!$user) return;

        DB::table('form_izin')->insert([
            [
                'no_reg' => 'IZN-' . date('Ymd') . '-001',
                'lokasi' => 'Server Room',
                'final_status' => 'Finished',
                'created_by' => $user->id,
                'created_at' => Carbon::now(),
            ]
        ]);
    }
}
