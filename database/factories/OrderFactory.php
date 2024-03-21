<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'grand_total' => $this->faker->randomFloat(2, 0, 1000),
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal', 'bank_transfer']),
            'payment_status' => $this->faker->randomElement(['paid', 'pending', 'failed']),
            'status' => $this->faker->randomElement(['new', 'processing', 'shipped', 'delivered', 'canceled']),
            'currency' => $this->faker->randomElement(['BRL', 'USD', 'EUR', 'GBP']),
            'shipping_amount' => $this->faker->randomFloat(2, 0, 100),
            'shipping_method' => $this->faker->randomElement(['standard', 'express']),
            'notes' => $this->faker->sentence,
        ];
    }
}
