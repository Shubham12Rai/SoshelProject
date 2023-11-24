<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('plan')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('plan')->insert([
            ['name' => 'Standard user', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Premium user', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'In-app purchase', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'All', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}