<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'name' => $this->faker->name,
            'slug' => $this->faker->unique()->slug,
            'images' => $this->faker->imageUrl(),
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'is_active' => $this->faker->boolean,
            'is_featured' => $this->faker->boolean,
            'in_stock' => $this->faker->boolean,
            'on_sale' => $this->faker->boolean,
        ];
    }
}
