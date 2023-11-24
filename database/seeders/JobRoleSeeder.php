<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('job_roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('job_roles')->insert([
            ['name' => 'Healthcare', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Finance', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Engineering', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Information technology', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Software', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Art', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Law', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hospitality', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Aviation', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pharmaceutics', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Retail', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Communications', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Accounting', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Construction', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Education', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Research', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Automation', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Marketing/advertising', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Small business', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Consulting', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Real estate', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sales', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Social Media', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Civil Services', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Food Industry', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Others', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
