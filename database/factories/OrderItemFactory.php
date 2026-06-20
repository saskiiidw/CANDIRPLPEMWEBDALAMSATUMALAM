<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $menu  = Menu::factory()->create();
        $qty   = fake()->numberBetween(1, 3);
        return [
            'order_id'           => Order::factory(),
            'menu_id'            => $menu->id,
            'menu_name_snapshot' => $menu->name,
            'price_snapshot'     => $menu->price,
            'quantity'           => $qty,
            'subtotal'           => $menu->price * $qty,
        ];
    }
}
