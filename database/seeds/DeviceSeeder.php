<?php

use Illuminate\Database\Seeder;
use App\Models\Device;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $devices = [
            [
                'name' => 'Laptop High End',
                'cost' => '20000000',
                'spesifikasi' => 'Core i7, 16GB RAM, 512GB SSD',
            ],
            [
                'name' => 'Laptop Standard',
                'cost' => '10000000',
                'spesifikasi' => 'Core i5, 8GB RAM, 256GB SSD',
            ],
            [
                'name' => 'Monitor 24 Inch',
                'cost' => '2000000',
                'spesifikasi' => 'Full HD, IPS Panel',
            ],
            [
                'name' => 'PC Desktop',
                'cost' => '12000000',
                'spesifikasi' => 'Core i5, 16GB RAM, 1TB HDD',
            ],
            [
                'name' => 'Printer L3210',
                'cost' => '2500000',
                'spesifikasi' => 'EcoTank, Print-Scan-Copy',
            ],
        ];

        foreach ($devices as $device) {
            Device::updateOrCreate(
                ['name' => $device['name']],
                $device
            );
        }
    }
}
