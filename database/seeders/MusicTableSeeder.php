<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MusicTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('musics')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('musics')->insert([
            ['name' => 'Country', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rap', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hip hop', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'R&B', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Soul', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Blues', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'EDM', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Classical', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rock', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Alternative', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jazz', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
