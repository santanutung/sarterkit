<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ModuleAppDashboard=Module::updateOrCreate(['name'=>'Admin Dashboard']);
        Permission::updateOrCreate([
            'module_id'=>$ModuleAppDashboard->id,
            'name'=>'Access Dashboard',
            'slug'=>'app.dashboard'
        ]);
        $ModuleAppRole=Module::updateOrCreate(['name'=>'Role Management']);
        Permission::updateOrCreate([
            'module_id'=>$ModuleAppRole->id,
            'name'=>'Access Roll',
            'slug'=>'app.roles.index'
        ]);
        Permission::updateOrCreate([
            'module_id'=>$ModuleAppRole->id,
            'name'=>'Create Roll',
            'slug'=>'app.roles.create'
        ]);
        Permission::updateOrCreate([
            'module_id'=>$ModuleAppRole->id,
            'name'=>'Edit Roll',
            'slug'=>'app.roles.edit'
        ]);
        Permission::updateOrCreate([
            'module_id'=>$ModuleAppRole->id,
            'name'=>'Delete Roll',
            'slug'=>'app.roles.destroy'
        ]);

        $ModuleAppUser=Module::updateOrCreate(['name'=>'User Management']);
        Permission::updateOrCreate([
            'module_id'=>$ModuleAppUser->id,
            'name'=>'Access User',
            'slug'=>'app.users.index'
        ]);
        Permission::updateOrCreate([
            'module_id'=>$ModuleAppUser->id,
            'name'=>'Create User',
            'slug'=>'app.users.create'
        ]);
        Permission::updateOrCreate([
            'module_id'=>$ModuleAppUser->id,
            'name'=>'Edit User',
            'slug'=>'app.users.edit'
        ]);
        Permission::updateOrCreate([
            'module_id'=>$ModuleAppUser->id,
            'name'=>'Delete User',
            'slug'=>'app.users.destroy'
        ]);

    }
}
