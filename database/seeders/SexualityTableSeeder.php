<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SexualityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sexuality')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('sexuality')->insert([
            ['name' => 'Straight', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gay', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lesbian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bisexual', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Transexual', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Queer', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Other', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
