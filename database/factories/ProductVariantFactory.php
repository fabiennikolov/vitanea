<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'sku' => strtoupper(fake()->unique()->bothify('??-####')),
            'label' => fake()->word().' '.fake()->word(),
            'size_value' => 250,
            'size_unit' => 'мл',
            'price_minor' => fake()->numberBetween(140, 950),
            'currency' => 'EUR',
            'stock' => 10,
            'is_active' => true,
            'position' => 0,
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(fn (): array => ['stock' => 0]);
    }
}
