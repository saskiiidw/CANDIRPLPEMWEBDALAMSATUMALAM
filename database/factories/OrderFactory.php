<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'buyer_id' => User::factory(),
            'seller_id' => User::factory()->penjual(),
            'total_price' => fake()->numberBetween(10000, 50000),
            'eta_minutes' => fake()->numberBetween(5, 30),
            'status' => 'menunggu_pembayaran',
            'note' => null,
        ];
    }

    public function diterima(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'diterima']);
    }

    public function dibatalkan(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'dibatalkan']);
    }
}