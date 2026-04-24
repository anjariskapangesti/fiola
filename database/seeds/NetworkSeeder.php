<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NetworkSeeder extends Seeder
{
    public function run()
    {
        $user = DB::table('users')->first();
        if (!$user) return;

        DB::table('form_network')->insert([
            [
                'no_reg' => 'NWK-' . date('Ymd') . '-001',
                'final_status' => 'Finished',
                'created_by' => $user->id,
                'created_at' => Carbon::now(),
            ]
        ]);
    }
}
