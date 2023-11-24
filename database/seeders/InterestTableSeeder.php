<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class InterestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('interests')->insert([
            ['interested_in' => 'Man', 'created_at' => now(), 'updated_at' => now()],
            ['interested_in' => 'Women', 'created_at' => now(), 'updated_at' => now()],
            ['interested_in' => 'Both', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('interests')->insert([
            ['interested_for' => 'Dating', 'created_at' => now(), 'updated_at' => now()],
            ['interested_for' => 'Making Friends', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
