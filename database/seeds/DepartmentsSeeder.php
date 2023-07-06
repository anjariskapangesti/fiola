<?php

use Illuminate\Database\Seeder;

class DepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('department')->insert([
            'code' => 'HRD',
            'name' => 'Human Resources Development',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('department')->insert([
            'code' => 'IRLGA',
            'name' => 'IRL & GA',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('department')->insert([
            'code' => 'ENGB',
            'name' => 'Engineering Body',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('department')->insert([
            'code' => 'ENGU',
            'name' => 'Engineering Unit',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('department')->insert([
            'code' => 'MTE',
            'name' => 'Maintenance',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('department')->insert([
            'code' => 'QAB',
            'name' => 'QA Body',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('department')->insert([
            'code' => 'MS',
            'name' => 'Management System',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('department')->insert([
            'code' => 'QAU',
            'name' => 'QA Engine Component',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('department')->insert([
            'code' => 'ITD',
            'name' => 'IT Development',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('department')->insert([
            'code' => 'PRDUDC',
            'name' => 'Production Engine Component (DC)',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('department')->insert([
            'code' => 'PRDUMA',
            'name' => 'Production Engine Component (MA)',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('department')->insert([
            'code' => 'PRDB',
            'name' => 'Production Body',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('department')->insert([
            'code' => 'PSD',
            'name' => 'Production System & Development',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('department')->insert([
            'code' => 'PPIC',
            'name' => 'PPIC',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('department')->insert([
            'code' => 'ENGQAE',
            'name' => 'ENG & QA Electic',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('department')->insert([
            'code' => 'MTEE',
            'name' => 'Maintenance Electric',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('department')->insert([
            'code' => 'PRDE',
            'name' => 'Production Electric',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('department')->insert([
            'code' => 'PPICE',
            'name' => 'PPIC Electric',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('department')->insert([
            'code' => 'OTHER',
            'name' => 'Other',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
