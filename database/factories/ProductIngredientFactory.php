<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductIngredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductIngredient>
 */
class ProductIngredientFactory extends Factory
{
    protected $model = ProductIngredient::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'name' => fake()->word(),
            'percentage' => null,
            'is_added' => false,
            'position' => 0,
        ];
    }

    public function added(): static
    {
        return $this->state(fn (): array => ['is_added' => true]);
    }
}
