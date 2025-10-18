<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);

        return [
            'sku' => strtoupper(Str::random(10)),
            'name' => ucfirst($name),
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(1000, 5000) / 100,
            'compare_at_price' => null,
            'inventory' => $this->faker->numberBetween(5, 50),
            'is_active' => true,
            'featured' => $this->faker->boolean(),
        ];
    }
}
