<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductNutrition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductNutrition>
 */
class ProductNutritionFactory extends Factory
{
    protected $model = ProductNutrition::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'kcal_per_100' => 50,
            'kj_per_100' => 215,
            'fruit_content_percent' => 100,
        ];
    }
}
