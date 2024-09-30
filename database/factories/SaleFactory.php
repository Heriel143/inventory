<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_number' => $this->faker->randomDigitNotNull(),
            'customer_id' => $this->faker->randomDigitNotNull,
            'total_amount' => $this->faker->randomFloat(2, 0, 999999),
        ];
    }
}
