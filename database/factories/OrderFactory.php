<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DeliveryMethod;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(500, 9000);
        $shipping = 499;

        return [
            'number' => 'VIT-'.fake()->unique()->numberBetween(1000, 9999),
            'status' => OrderStatus::Pending,
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_phone' => '+35988'.fake()->numerify('#######'),
            'delivery_method' => DeliveryMethod::EcontOffice,
            'delivery_city' => 'Пловдив',
            'delivery_address' => 'Офис на куриера',
            'payment_method' => PaymentMethod::CashOnDelivery,
            'subtotal_minor' => $subtotal,
            'shipping_minor' => $shipping,
            'total_minor' => $subtotal + $shipping,
            'currency' => 'EUR',
        ];
    }
}
