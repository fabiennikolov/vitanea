<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unitPrice = fake()->numberBetween(140, 950);
        $quantity = fake()->numberBetween(1, 4);

        return [
            'order_id' => Order::factory(),
            'product_name' => fake()->word().' '.fake()->word(),
            'variant_label' => '250 мл',
            'sku' => strtoupper(fake()->unique()->bothify('??-####')),
            'unit_price_minor' => $unitPrice,
            'quantity' => $quantity,
            'line_total_minor' => $unitPrice * $quantity,
        ];
    }
}
