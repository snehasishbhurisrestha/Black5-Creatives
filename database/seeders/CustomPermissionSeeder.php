<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;

class CustomPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

     // to run use this command  php artisan db:seed --class=CustomPermissionSeeder
     
    public function run(): void
    {
        $permissions = [
            'Dashboard',
            'Brand Show',
            'Brand Create',
            'Brand Edit',
            'Brand Delete',
            'Category Show',
            'Category Create',
            'Category Edit',
            'Category Delete',
            'ContactUs Show',
            'ContactUs Delete',
            'Coupon Show',
            'Coupon Create',
            'Coupon Edit',
            'Coupon Delete',
            'Order Show',
            'Order Edit',
            'Order Delete',
            'Product Show',
            'Product Create',
            'Product Edit',
            'Product Delete',
            'Slider Show',
            'Slider Create',
            'Slider Edit',
            'Slider Delete',
            'System User Show',
            'System User Create',
            'System User Edit',
            'System User Delete',
            'Testimonial Show',
            'Testimonial Create',
            'Testimonial Edit',
            'Testimonial Delete',
            'Web User Show',
            'Web User Delete',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }
    }
}
