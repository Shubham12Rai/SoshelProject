<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatingIntentionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('dating_intention')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        DB::table('dating_intention')->insert([
            ['name' => 'Life partner', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Long term relationship', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Short-term relationship', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sugar arrangements', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Something casual', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Prefer not to say', 'created_at' => now(), 'updated_at' => now()],

        ]);
    }
}
