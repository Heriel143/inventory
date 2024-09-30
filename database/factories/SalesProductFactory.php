<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SalesProduct>
 */
class SalesProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => $this->faker->randomDigitNotNull,
            'sale_id' => $this->faker->randomDigitNotNull,
            'quantity' => $this->faker->randomDigitNotNull,
            'price' => $this->faker->randomFloat(2, 0, 999999),
        ];
    }
}
