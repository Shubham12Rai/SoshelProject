<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('status')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('status')->insert([
            ['name' => 'Active', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Inactive', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Delete', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Block', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
