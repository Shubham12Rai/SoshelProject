<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EthnicityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('ethnicity')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('ethnicity')->insert([
            ['name' => 'American Indian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Black/African Descent', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Middle Eastern', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pacific Islander', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'South Asian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Southeast Asian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'White/Caucasian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hispanic/Latino', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Other', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}