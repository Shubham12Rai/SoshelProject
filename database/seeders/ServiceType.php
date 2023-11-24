<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceType extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('service_type')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('service_type')->insert([
            ['name' => 'restaurant', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'bar', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'cafe', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'university', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
