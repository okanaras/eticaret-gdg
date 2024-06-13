<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->text(14);
        return [
            // factory de fake data olusturururz, seeder da factory cagirilir veya manuel veri doldururuz. Seeder lari ise databaseSeeder da $this->call([BrandSeeder::class,]); ile cagiririz.

            'name' => $name,
            'slug' => Str::slug($name),
            'status' => fake()->boolean(),
            'is_featured' => fake()->boolean(),
            'order' => fake()->randomNumber()
        ];
    }
}