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
        DB::table('departments')->insert([
            'code' => 'HRD',
            'name' => 'Human Resources Development',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'IRLGA',
            'name' => 'IRL & GA',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'ENGB',
            'name' => 'Engineering Body',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'ENGU',
            'name' => 'Engineering Unit',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'MTE',
            'name' => 'Maintenance',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'QAB',
            'name' => 'QA Body',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'MS',
            'name' => 'Management System',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'QAU',
            'name' => 'QA Engine Component',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'ITD',
            'name' => 'IT Development',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'PRDUDC',
            'name' => 'Production Engine Component (DC)',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'PRDUMA',
            'name' => 'Production Engine Component (MA)',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'PRDB',
            'name' => 'Production Body',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'PSD',
            'name' => 'Production System & Development',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'PPIC',
            'name' => 'PPIC',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'ENGQAE',
            'name' => 'ENG & QA Electic',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'MTEE',
            'name' => 'Maintenance Electric',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'PRDE',
            'name' => 'Production Electric',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'PPICE',
            'name' => 'PPIC Electric',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'ENG',
            'name' => 'DIV Engineering',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'PRD',
            'name' => 'DIV Production',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'ENGPRDE',
            'name' => 'DIV Engineering & Production Electric',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'HRIRLGA',
            'name' => 'DIV HRD IRL & GA',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'FACPUR',
            'name' => 'DIV FAC & Purchasing',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'PDIR',
            'name' => 'Plant Director',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'DDIR',
            'name' => 'Deputy Director',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'VPD',
            'name' => 'Vice President Director',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'PD',
            'name' => 'President Director',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'ADV',
            'name' => 'Advisor',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
