<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pets')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('pets')->insert([
            ['name' => 'Dogs', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cats', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Snakes', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Horses', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rabbits', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fish', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Turtles', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Guinea pigs', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ferrets', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
