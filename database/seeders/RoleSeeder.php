<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);

        $adminPermissions = Permission::all();
        $adminRole->givePermissionTo($adminPermissions);  
        
        $teacherPermissions = Permission::whereIn('name', ['create-quiz', 'retry-quiz', 'delete-quiz'])->get();
        $teacherRole->givePermissionTo($teacherPermissions);  
        
        $studentPermissions = Permission::whereIn('name', ['view-results', 'view-student'])->get();
        $studentRole->givePermissionTo($studentPermissions); 
    }
}
