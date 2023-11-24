<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZodiacTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('zodiac')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('zodiac')->insert([
            ['name' => 'Aries', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tauras', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gemini', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cancer', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Leo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Virgo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Libra', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Scorpio', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sagittarius', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Capricorn', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Aquarius', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pisces', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Prefer not to say', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
