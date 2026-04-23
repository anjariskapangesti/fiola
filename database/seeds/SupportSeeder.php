<?php

use Illuminate\Database\Seeder;
use App\Models\Support;
use Illuminate\Support\Str;

class SupportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supports = [
            [
                'name' => 'Alliq Nur Imanin Aji',
                'email' => 'alliq@aiia.co.id',
                'nohp' => '081234567892',
                'shift' => 'Non Shift',
                'status' => 'active',
            ],
            [
                'name' => 'Rohmat Maulana Ishaq',
                'email' => 'rohmat@aiia.co.id',
                'nohp' => '081234567893',
                'shift' => 'Shift 2',
                'status' => 'not active',
            ],
            [
                'name' => 'Muhammad Diki Dwi Nugraha',
                'email' => 'diki@aiia.co.id',
                'nohp' => '081234567894',
                'shift' => 'Shift 3',
                'status' => 'not active',
            ],
        ];

        foreach ($supports as $support) {
            Support::updateOrCreate(
                ['email' => $support['email']],
                $support
            );
        }
    }
}
