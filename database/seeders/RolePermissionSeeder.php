<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'view students progress',  
            'manage students',         
            'manage own profile',      
            'review test results',
            'take test',              
            'create tests',           
            'view tests',             
            'attempt tests',          
            'edit users',             
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);    
        $teacherRole = Role::firstOrCreate(['name' => 'Teacher']); 
        $studentRole = Role::firstOrCreate(['name' => 'Student']); 

        $adminRole->givePermissionTo(Permission::all()); 

        $teacherRole->givePermissionTo([
            'view students progress',
            'manage students',
            'create tests',
            'review test results',
        ]);

        $studentRole->givePermissionTo([
            'manage own profile',    
            'view tests',             
            'take test',              
            'attempt tests',       
        ]);
    }
}
