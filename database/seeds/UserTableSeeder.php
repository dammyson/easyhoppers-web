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
        $superadmin->name = 'UserSA';
        $superadmin->email = 'user_sa@eazyhoppers.com';
        $superadmin->password = bcrypt('password');
        $superadmin->save();
        $superadmin->roles()->attach($role_superadmin);

        $admin = new User();
        $admin->name = 'UserA';
        $admin->email = 'user_a@eazyhoppers.com';
        $admin->password = bcrypt('password');
        $admin->save();
        $admin->roles()->attach($role_admin);

        $supervisor = new User();
        $supervisor->name = 'UserS';
        $supervisor->email = 'user_s@eazyhoppers.com';
        $supervisor->password = bcrypt('password');
        $supervisor->save();
        $supervisor->roles()->attach($role_supervisor);

        $agent = new User();
        $agent->name = 'UserAG';
        $agent->email = 'user_ag@eazyhoppers.com';
        $agent->password = bcrypt('password');
        $agent->save();
        $agent->roles()->attach($role_agent);

        $customer = new User();
        $customer->name = 'UserC';
        $customer->email = 'user_c@eazyhoppers.com';
        $customer->password = bcrypt('password');
        $customer->save();
        $customer->roles()->attach($role_customer);
    }
}
