<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SportTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sports')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('sports')->insert([
            ['name' => 'Basketball', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Baseball', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Soccer', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Football', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Boxing', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Wrestling', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Running', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tennis', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Golf', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lacrosse', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rowing', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Horseback riding', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Body-building', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
