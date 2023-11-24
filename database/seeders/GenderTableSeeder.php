<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class GenderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('gender')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('gender')->insert([
            ['name' => 'Male', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Female', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Non-binary', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
