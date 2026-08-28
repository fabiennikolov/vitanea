<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Money;
use Database\Factories\ProductVariantFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $product_id
 * @property string $sku
 * @property string $label
 * @property int|null $size_value
 * @property string|null $size_unit
 * @property string|null $dilution
 * @property string|null $packaging
 * @property int $price_minor
 * @property int|null $compare_at_price_minor
 * @property string $currency
 * @property int $stock
 * @property bool $is_active
 * @property int $position
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Product $product
 */
#[Fillable([
    'product_id', 'sku', 'label', 'size_value', 'size_unit', 'dilution',
    'packaging', 'price_minor', 'compare_at_price_minor', 'currency',
    'stock', 'is_active', 'position',
])]
class ProductVariant extends Model
{
    /** @use HasFactory<ProductVariantFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function price(): Money
    {
        return Money::fromMinor($this->price_minor);
    }

    public function isInStock(): bool
    {
        return $this->is_active && $this->stock > 0;
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
