<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class COVIDVaccineStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('covid_vaccine_status')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('covid_vaccine_status')->insert([
            ['name' => 'Fully vaccinated', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Partially vaccinated', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Not vaccinated', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Prefer not to say', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
