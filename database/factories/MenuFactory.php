<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition(): array
    {
        return [
            'seller_id' => User::factory()->penjual(),
            'name' => fake()->randomElement([
                'Nasi Goreng', 'Mie Ayam', 'Soto Ayam', 'Nasi Padang', 'Es Teh Manis', 'Es Jeruk',
            ]),
            'description' => fake()->sentence(6),
            'category' => fake()->randomElement(['makanan_berat', 'makanan_ringan', 'minuman']),
            'price' => fake()->numberBetween(5000, 25000),
            'stock' => fake()->numberBetween(0, 20),
            'cooking_time_minutes' => fake()->numberBetween(5, 20),
            'is_active' => true,
        ];
    }

    public function habis(): static
    {
        return $this->state(fn (array $attributes) => ['stock' => 0]);
    }
}