<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeleteAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('account_delete_feedback')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('account_delete_feedback')->insert([
            ['name' => 'My account was hacked.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'I don’t feel safe on Soshel.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'I don’t find Soshel useful.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'I spend too much time using Soshel.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'I have a privacy concern.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'I found someone.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Other', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
