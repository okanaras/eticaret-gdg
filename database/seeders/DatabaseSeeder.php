<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\Brand\BrandSeeder;
use Database\Seeders\Category\CategorySeeder;
use Database\Seeders\Products\ProductTypesSeeder;
use Database\Seeders\User\UserInitializeUserSeeder;
use Database\Seeders\RolePermissions\GeneralRolePermissionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /**
         * * call() methodu ile seedleri çağırabiliriz.
         * * Seeder lar icerisinde factory methodları cagrilir ve kendimiz veri doldurabiliriz.
         * * php artisan db:seed ile butun seederlar, php artisan db:seed --class=CategorySeeder ile belirtilen seederi calistirir.
         * * seederlar eger seeders klasorunun altinda degilse, yani Seeders/Category/CategorySeeder.php seklinde baska bir kalsorun altindaysa eger asagidaki sekilde cagirilmasi gerekir.
         * ? Not : Seeder namespace in defistirilmesi gerekir.
         * * php artisan db:seed --class=Database\\Seeders\\Category\\CategorySeeder
         */

        $this->call([
            ProductTypesSeeder::class,
            BrandSeeder::class,
            // CategorySeeder::class,
            CategoryKoraySeeder::class,
            GeneralRolePermissionSeeder::class,
            UserInitializeUserSeeder::class,
        ]);
    }
}