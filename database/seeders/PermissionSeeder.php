<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create-post', 'edit-post', 'delete-post', 'view-post', 
            'create-quiz', 'retry-quiz', 'delete-quiz',
            'view-results', 'view-student',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

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
