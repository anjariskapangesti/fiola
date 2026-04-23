<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(DepartmentsSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(FolderSeeder::class);
        $this->call(SubFolderSeeder::class);
        $this->call(SupportSeeder::class);
        $this->call(GuideSeeder::class);
        $this->call(DeviceSeeder::class);
        $this->call(AppSeeder::class);
        $this->call(AlertSeeder::class);
        $this->call(TicketSeeder::class);
        $this->call(AccountSeeder::class);
        $this->call(SistemSeeder::class);
        $this->call(NewFolderSeeder::class);
    }
}
