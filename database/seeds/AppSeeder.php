<?php

use Illuminate\Database\Seeder;
use App\Models\App;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $apps = [
            [
                'name' => 'HRIS',
                'url' => 'https://hris.fiola.com',
                'description' => 'Human Resource Information System',
            ],
            [
                'name' => 'Inventory System',
                'url' => 'https://inventory.fiola.com',
                'description' => 'System for managing office inventory',
            ],
            [
                'name' => 'E-Learning',
                'url' => 'https://learn.fiola.com',
                'description' => 'Internal employee training platform',
            ],
            [
                'name' => 'Finance Portal',
                'url' => 'https://finance.fiola.com',
                'description' => 'Portal for finance and payroll management',
            ],
            [
                'name' => 'Support Center',
                'url' => 'https://support.fiola.com',
                'description' => 'Internal ticketing and support system',
            ],
        ];

        foreach ($apps as $app) {
            App::updateOrCreate(
                ['name' => $app['name']],
                $app
            );
        }
    }
}
