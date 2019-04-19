<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $role_employee = new Role();
        $role_employee->name = 'superadmin';
        $role_employee->description = 'A Super Admin User';
        $role_employee->save();

        $role_manager = new Role();
        $role_manager->name = 'admin';
        $role_manager->description = 'A Admin User';
        $role_manager->save();

        $role_manager = new Role();
        $role_manager->name = 'supervisor';
        $role_manager->description = 'A Supervisor User';
        $role_manager->save();

        $role_agent = new Role();
        $role_agent->name = 'agent';
        $role_agent->description = 'An Agent User';
        $role_agent->save();

        $role_customer = new Role();
        $role_customer->name = 'customer';
        $role_customer->description = 'A customer User';
        $role_customer->save();
    }
}
