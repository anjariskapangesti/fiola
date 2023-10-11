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
            'code' => 'HRD&GA',
            'name' => 'Human Resources Development & General Affairs',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'IRL',
            'name' => 'Industrial Relation & Legal',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'ENB',
            'name' => 'Engineering Body',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'ENU',
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
            'name' => 'Quality Body',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'MSY',
            'name' => 'Management System',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'QAU',
            'name' => 'Quality Unit',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'ITD',
            'name' => 'Information Technology Development',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'PRO UNIT DC',
            'name' => 'Production Unit DC',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'PRO UNIT MA',
            'name' => 'Production Unit (MA)',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'PRO BODY',
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
            'name' => 'Production Planning and Inventory Control',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'EQEC',
            'name' => 'ENG & QA Electic Components',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'MMA',
            'name' => 'Machine Maintenance',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'PRO EC',
            'name' => 'Production Electric Components',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('departments')->insert([
            'code' => 'PPIC EC',
            'name' => 'Production Planning and Inventory Control Electric Components',
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
