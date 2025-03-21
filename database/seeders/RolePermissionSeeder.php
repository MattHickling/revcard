<?php

namespace Database\Seeders;

use App\Models\User;
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

        $adminRole = Role::firstOrCreate(['name' => 'admin']);    
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']); 
        $studentRole = Role::firstOrCreate(['name' => 'student']); 

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

        $user = User::first();
        if ($user) {
            $user->assignRole('admin');
        }

        $user = User::skip(1)->first();
        if ($user) {
            $user->assignRole('teacher');
        }

        $user = User::skip(2)->first();
        if ($user) {
            $user->assignRole('student');
        }
    }
}
