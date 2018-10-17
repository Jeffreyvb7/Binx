<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // create permissions
        // studieroute
        Permission::create(['name' => 'create studieroute']);
        Permission::create(['name' => 'edit studieroute']);
        Permission::create(['name' => 'delete studieroute']);

        // task
        Permission::create(['name' => 'create task']);
        Permission::create(['name' => 'edit task']);
        Permission::create(['name' => 'delete task']);

        // Submission
        Permission::create(['name' => 'can submit']);

        // profile
        Permission::create(['name' => 'edit profile']);

        // admin
        Permission::create(['name' => 'create student']);
        Permission::create(['name' => 'edit student']);
        Permission::create(['name' => 'delete student']);
        Permission::create(['name' => 'create teacher']);
        Permission::create(['name' => 'edit teacher']);
        Permission::create(['name' => 'delete teacher']);
        Permission::create(['name' => 'create group']);
        Permission::create(['name' => 'associate group']);
        Permission::create(['name' => 'dissociate group']);
        Permission::create(['name' => 'delete group']);

        // portfolio
        Permission::create(['name' => 'create portfolio']);

        // permission
        Permission::create(['name' => 'create permission']);
        Permission::create(['name' => 'edit permission']);
        Permission::create(['name' => 'delete permission']);




        // create roles and assign created permissions

        $role = Role::create(['name' => 'student']);
        $role->givePermissionTo(['edit profile', 'can submit', 'create portfolio']);

        $role = Role::create(['name' => 'teacher']);
        $role->givePermissionTo(['create studieroute', 'edit studieroute', 'delete studieroute', 'create task', 'edit task', 'delete task']);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        \App\User::find(1)->assignRole('student');
        \App\User::find(2)->assignRole('teacher');
        \App\User::find(3)->assignRole('admin');
    }
}