<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('reports')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('reports')->insert([
            ['name' => 'Sharing inappropriate content', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pretending to be someone else', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Inappropriate behavior', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'User is underage', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Soliciting inappropriate services', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Other', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
