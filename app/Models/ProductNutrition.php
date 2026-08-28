<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ProductNutritionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $product_id
 * @property int|null $kcal_per_100
 * @property int|null $kj_per_100
 * @property string|null $fruit_content_percent
 * @property string|null $sugars_per_100
 * @property string|null $carbohydrates_per_100
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Product $product
 */
#[Fillable([
    'product_id', 'kcal_per_100', 'kj_per_100', 'fruit_content_percent',
    'sugars_per_100', 'carbohydrates_per_100',
])]
class ProductNutrition extends Model
{
    /** @use HasFactory<ProductNutritionFactory> */
    use HasFactory;

    protected $table = 'product_nutrition';

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
