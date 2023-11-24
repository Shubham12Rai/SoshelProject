<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Role;

class RoleAdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Cria usuários admins (dados controlados)
        $this->createAdmins();

        // Vincula usuários aos papéis
        $this->sync();    
    }

    private function createAdmins()
    {
        Admin::create([
            'email' => 'dev@dev.com', 
            'name'  => 'Developer',
            'password' => bcrypt('root'),
            'avatar'  => 'img/config/nopic.png',
            'active'  => true
        ]);
        
        $this->command->info('Admin dev created');

        Admin::create([
            'email' => 'admin@admin.com', 
            'name'  => 'Administrator',
            'password' => bcrypt('admin'),
            'avatar'  => 'img/config/nopic.png',
            'active'  => true
        ]);

        $this->command->info('Admins dev and admin created');
    }

    private function sync()
    {       
        $role = Admin::find(1);
        $role->roles()->sync([1]);

        $role = Admin::find(2);
        $role->roles()->sync([2]);        

        $this->command->info('Admins linked to roles!');
    }
}
