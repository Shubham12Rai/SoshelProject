<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HeightTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('height')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        
        DB::table('height')->insert([
            ['feet' => '2', 'inches' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['feet' => '3', 'inches' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['feet' => '4', 'inches' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['feet' => '5', 'inches' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['feet' => '6', 'inches' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['feet' => '7', 'inches' => '0', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $inchesData = [];
        for ($i = 0; $i <= 11; $i++) {
            $inchesData[] = ['feet' => 0, 'inches' => $i, 'created_at' => now(), 'updated_at' => now()];
        }

        DB::table('height')->insert($inchesData);
    }
}
