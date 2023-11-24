<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoingOutTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('going_out')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('going_out')->insert([
            ['name' => 'Bars', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Coffee shops', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Clubs', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Concerts', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Festivals', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Museums & Galleries', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Stand up', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Theatre', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
