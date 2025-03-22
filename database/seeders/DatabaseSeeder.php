<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\RolePermissionSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {

        User::where('email', 'test@example.com')->delete();
        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'm.hickling@hotmail.co.uk',
            'password' => 'testing123',
        ]);

    }
}
