<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CreateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'Admin',
        'type'=>'admin']);

         $permissions = Permission::all();

         $role->syncPermissions($permissions);

         $role = Role::create(['name' => 'المالك',
         'type'=>'store']);
 
          $permissions = Permission::all();
 
          $role->syncPermissions($permissions);
    }
}
