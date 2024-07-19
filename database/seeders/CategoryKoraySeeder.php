<?php

namespace Database\Seeders;

use RoachPHP\Roach;
use Illuminate\Database\Seeder;
use App\Spiders\KoraySporSpider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoryKoraySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Roach::startSpider(KoraySporSpider::class);
    }
}