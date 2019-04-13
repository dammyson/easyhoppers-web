<?php

use App\User;
use App\Role;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $role_superadmin = Role::where('name', 'superadmin')->first();
        $role_admin  = Role::where('name', 'admin')->first();
        $role_supervisor  = Role::where('name', 'supervisor')->first();
        $role_agent  = Role::where('name', 'agent')->first();
        $role_customer  = Role::where('name', 'customer')->first();

        $superadmin = new User();
        $superadmin->name = 'SuperAdmin';
        $superadmin->email = 'superadmin@eazyhoppers.com';
        $superadmin->password = bcrypt('password');
        $superadmin->save();
        $superadmin->roles()->attach($role_superadmin);

        $admin = new User();
        $admin->name = 'Admin';
        $admin->email = 'admin@eazyhoppers.com';
        $admin->password = bcrypt('password');
        $admin->save();
        $admin->roles()->attach($role_admin);

        $supervisor = new User();
        $supervisor->name = 'Supervisor';
        $supervisor->email = 'supervisor@eazyhoppers.com';
        $supervisor->password = bcrypt('password');
        $supervisor->save();
        $supervisor->roles()->attach($role_supervisor);

        $agent = new User();
        $agent->name = 'Agent';
        $agent->email = 'agent@eazyhoppers.com';
        $agent->password = bcrypt('password');
        $agent->save();
        $agent->roles()->attach($role_agent);

        $customer = new User();
        $customer->name = 'Customer';
        $customer->email = 'customer@eazyhoppers.com';
        $customer->password = bcrypt('password');
        $customer->save();
        $customer->roles()->attach($role_customer);
    }
}
