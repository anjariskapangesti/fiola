<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VpnSeeder extends Seeder
{
    public function run()
    {
        $user = DB::table('users')->first();
        if (!$user) return;

        DB::table('form_vpn')->insert([
            [
                'no_reg' => 'VPN-' . date('Ymd') . '-001',
                'npk' => $user->npk,
                'fullname' => $user->name,
                'final_status' => 'Finished',
                'created_by' => $user->id,
                'created_at' => Carbon::now(),
            ]
        ]);
    }
}
