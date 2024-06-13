<?php

namespace Database\Seeders\Brand;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // factory de fake data olusturururz, seeder da factory cagirilir veya manuel veri doldururuz.

        // factory yi cagiriyoruz ve 20 tane fake data olusturulmasini sagliyoruz
        Brand::factory(20)->create();
    }
}