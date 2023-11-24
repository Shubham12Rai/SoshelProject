<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('feedback')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('feedback')->insert([
            ['name' => 'Reason 1', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Reason 2', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Reason 3', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Reason 4', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Reason 5', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
