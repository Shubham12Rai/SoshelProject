<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReligiousTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('religious')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('religious')->insert([
            ['name' => 'Agnostic', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Atheist', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Buddhist', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Catholic', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Christian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hindu', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jewish', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Muslim', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sikh', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Spiritual', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Prefer not to say', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Other', 'created_at' => now(), 'updated_at' => now()],
        ]);

    }
}