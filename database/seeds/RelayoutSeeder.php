<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class RelayoutSeeder extends Seeder
{
    public function run()
    {
        $user = DB::table('users')->first();
        if (!$user) return;

        DB::table('form_relayout')->insert([
            [
                'id' => (string) Str::uuid(),
                'no_reg' => 'RLY-' . date('Ymd') . '-001',
                'final_status' => 'Finished',
                'created_by' => $user->id,
                'created_at' => Carbon::now(),
            ]
        ]);
    }
}
