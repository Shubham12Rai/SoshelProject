<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	Model::unguard();

    	DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    	$this->command->info('Initializing...');
    	$this->command->info('Deleting tables...');

    	DB::table('configs')->truncate();
    	DB::table('role_admin')->truncate();
        DB::table('permission_role')->truncate();
        DB::table('permissions')->truncate();
        DB::table('permission_groups')->truncate();
        DB::table('roles')->truncate();
        DB::table('admins')->truncate();

        $this->command->info('Deleted tables!');
        $this->command->info('Creating Tables...');
        
        $this->call([
            StatusTableSeeder::class,
            
            ConfigTableSeeder::class,
            
            ReligiousTableSeeder::class,
            COVIDVaccineStatusTableSeeder::class,
            DatingIntentionTableSeeder::class,
            EducationStatusTableSeeder::class,
            EthnicityTableSeeder::class,
            FamilyPlanTableSeeder::class,
            GenderTableSeeder::class,
            GoingOutTableSeeder::class,
            HeightTableSeeder::class,
            InterestTableSeeder::class,
            MusicTableSeeder::class,
            PetTableSeeder::class,
            PlanTableSeeder::class,
            PoliticsTableSeeder::class,
            RelationshipTypeTableSeeder::class,
            ReligiousTableSeeder::class,
            SexualityTableSeeder::class,
            SportTableSeeder::class,
            ZodiacTableSeeder::class,
            LanguageTableSeeder::class,
            // RoleUserTablesSeeder::class,
            PermissionRoleTablesSeeder::class,
            RoleAdminTablesSeeder::class,
            JobRoleSeeder::class,
            IncomeTableSeeder::class,
            ReportTableSeeder::class,
            FeedbackSeeder::class
        ]);

        $this->command->info('Finished!');

    	DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
