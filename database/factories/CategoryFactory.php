<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake('tr')->title();
        return [
            // factory de fake data olusturururz, seeder da factory cagirilir veya manuel veri doldururuz. Seeder lari ise databaseSeeder da $this->call([CategorySeeder::class,]); ile cagiririz.

            'name' => $name,
            'slug' => Str::slug($name),
            'status' => fake()->boolean(),
            'short_description' => fake()->paragraph(5),
            'description' => fake()->paragraph(13),
        ];
    }
}