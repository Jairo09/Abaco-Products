<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'base_price' => $this->faker->numberBetween(100, 1000),
            'price' => $this->faker->numberBetween(100, 1000),
            'stock_quantity' => $this->faker->numberBetween(1, 100),
        ];
    }
}
