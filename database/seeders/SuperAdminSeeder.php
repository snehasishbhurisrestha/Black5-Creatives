<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Check if no user exists
        if (User::count() === 0) {
            // Create a new user
            $user = User::create([
                'name' => 'Super Admin',
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'admin@admin.com',
                'phone' => 9999999999,
                'password' => bcrypt('12345678'),
            ]);

            // Create the 'Super Admin' role if it doesn't exist
            $role = Role::firstOrCreate(['name' => 'Super Admin']);

            // Assign the role to the user
            $user->assignRole($role);

            $this->command->info('Super Admin user created and role assigned.');
        } else {
            $this->command->info('Users already exist in the database. No action taken.');
        }
    }
}
