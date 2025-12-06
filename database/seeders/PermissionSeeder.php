<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'Permission Create',
            'Permission Show',
            'Permission Edit',
            'Permission Delete',
            'Role Show',
            'Role Create',
            'Role Edit',
            'Role Delete',
            'Asign Permission',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }
    }
}
