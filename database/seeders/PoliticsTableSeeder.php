<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoliticsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('politics')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('politics')->insert([
            ['name' => 'Liberal', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Moderate', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Conservative', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Not political', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Others', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}