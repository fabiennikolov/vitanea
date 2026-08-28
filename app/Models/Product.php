<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $category_id
 * @property int|null $series_id
 * @property string $name
 * @property string $slug
 * @property string|null $short_description
 * @property string|null $description
 * @property string|null $usage
 * @property string|null $storage
 * @property bool $is_active
 * @property int $position
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Category $category
 * @property-read Series|null $series
 * @property-read ProductNutrition|null $nutrition
 * @property-read Collection<int, ProductVariant> $variants
 * @property-read Collection<int, ProductIngredient> $ingredients
 * @property-read Collection<int, ProductImage> $images
 * @property-read Collection<int, Fruit> $fruits
 */
#[Fillable([
    'category_id', 'series_id', 'name', 'slug', 'short_description',
    'description', 'usage', 'storage', 'is_active', 'position',
    'meta_title', 'meta_description',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo<Series, $this>
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    /**
     * @return BelongsToMany<Fruit, $this>
     */
    public function fruits(): BelongsToMany
    {
        return $this->belongsToMany(Fruit::class);
    }

    /**
     * @return HasMany<ProductVariant, $this>
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('position');
    }

    /**
     * @return HasMany<ProductIngredient, $this>
     */
    public function ingredients(): HasMany
    {
        return $this->hasMany(ProductIngredient::class)->orderBy('position');
    }

    /**
     * @return HasMany<ProductImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    /**
     * @return HasOne<ProductNutrition, $this>
     */
    public function nutrition(): HasOne
    {
        return $this->hasOne(ProductNutrition::class);
    }

    /**
     * The brand's central claim, expressed as data rather than copy: a product
     * qualifies only when none of its ingredients were added to the fruit.
     */
    public function hasNothingAdded(): bool
    {
        return $this->ingredients->every(fn (ProductIngredient $ingredient): bool => ! $ingredient->is_added);
    }

    public function isInStock(): bool
    {
        return $this->variants->contains(fn (ProductVariant $variant): bool => $variant->isInStock());
    }

    /**
     * @param  Builder<Product>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
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
