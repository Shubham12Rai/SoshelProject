<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncomeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('income_level')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        DB::table('income_level')->insert([
            ['min_income' => '0', 'max_income' =>  '20,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '20,000', 'max_income' =>  '40,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '40,000', 'max_income' =>  '60,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '60,000', 'max_income' =>  '80,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '80,000', 'max_income' =>  '100,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '100,000', 'max_income' =>  '150,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '150,000', 'max_income' =>  '200,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '200,000', 'max_income' =>  '250,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '250,000', 'max_income' =>  '300,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '300,000', 'max_income' =>  '350,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '350,000', 'max_income' =>  '400,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '400,000', 'max_income' =>  '450,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '450,000', 'max_income' =>  '500,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '500,000', 'max_income' =>  '550,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '550,000', 'max_income' =>  '600,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '600,000', 'max_income' =>  '650,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '650,000', 'max_income' =>  '700,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '700,000', 'max_income' =>  '750,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '750,000', 'max_income' =>  '800,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '800,000', 'max_income' =>  '850,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '850,000', 'max_income' =>  '900,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '900,000', 'max_income' =>  '950,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '950,000', 'max_income' =>  '1,000,000','created_at' => now(), 'updated_at' => now()],
            ['min_income' => '1,000,000', 'max_income' =>  null,'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
