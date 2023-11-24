<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('education_status')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('education_status')->insert([
            ['name' => 'Highschool', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Undergraduate', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Postgraduate', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Prefer not to say', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
