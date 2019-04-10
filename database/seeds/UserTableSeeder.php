<?php

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

        $superadmin = new User();
        $superadmin->name = 'Super Admin';
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

        $admin = new User();
        $admin->name = 'Supervisor';
        $admin->email = 'supervisor@eazyhoppers.com';
        $admin->password = bcrypt('password');
        $admin->save();
        $admin->roles()->attach($role_supervisor);
    }
}
