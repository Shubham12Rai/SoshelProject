<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelationshipTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('relationship_type')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('relationship_type')->insert([
            ['name' => 'Monogamy', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Non Monogamy', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Figuring it out', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Prefer not to say', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
