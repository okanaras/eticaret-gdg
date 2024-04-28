<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // factory deki fake datatlari seeder da cagiriyoruz, Seeder i da databaseSeeder da call icinde cagiriyoruz

        // User::factory(10)->create();
        User::factory(10)->unverified()->create();
    }
}