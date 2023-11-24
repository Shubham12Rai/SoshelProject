<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class Optionseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('option')->insert([
            ['option_title' => 'Privacy Policy', 'option_detail' => 'Privacy Policy Content', 'created_at' => now(), 'updated_at' => now()],
            ['option_title' => 'Term & Conditions', 'option_detail' => 'Term & Conditions Content', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}