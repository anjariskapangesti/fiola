<?php

use Illuminate\Database\Seeder;
use App\Models\Alert;
use App\Models\Department;

class AlertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get some departments
        $itd = Department::where('code', 'ITD')->first();
        $hrd = Department::where('code', 'HRD&GA')->first();
        $pro = Department::where('code', 'PRD')->first();

        $alerts = [
            [
                'name' => 'IT Admin Alert',
                'email' => 'admin.it@fiola.com',
                'nohp' => '081234567890',
                'role' => 'Administrator',
                'department' => $itd ? $itd->id : 1,
            ],
            [
                'name' => 'HR Manager Alert',
                'email' => 'manager.hr@fiola.com',
                'nohp' => '081234567891',
                'role' => 'Manager',
                'department' => $hrd ? $hrd->id : 1,
            ],
            [
                'name' => 'Production Supervisor',
                'email' => 'supervisor.prod@fiola.com',
                'nohp' => '081234567892',
                'role' => 'Supervisor',
                'department' => $pro ? $pro->id : 1,
            ],
        ];

        foreach ($alerts as $alert) {
            Alert::updateOrCreate(
                ['email' => $alert['email']],
                $alert
            );
        }
    }
}
