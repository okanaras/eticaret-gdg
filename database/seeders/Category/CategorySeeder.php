<?php

namespace Database\Seeders\Category;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // factory de fake data olusturururz, seeder da factory cagirilir veya manuel veri doldururuz.

        // factory yi cagiriyoruz ve 20 tane fake data olusturulmasini sagliyoruz
        Category::factory(20)->create();
    }
}