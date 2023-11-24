<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FamilyPlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('family_plans')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('family_plans')->insert([
            ['name' => 'Open to children', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Does not want children', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Unsure', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Prefer not to say', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
