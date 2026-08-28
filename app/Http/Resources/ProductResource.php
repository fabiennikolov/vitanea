<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductIngredient;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var ProductVariant|null $cheapest */
        $cheapest = $this->variants->sortBy('price_minor')->first();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'shortDescription' => $this->short_description,
            'description' => $this->description,
            'usage' => $this->usage,
            'storage' => $this->storage,
            'category' => [
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ],
            'series' => $this->whenLoaded('series', fn (): ?array => $this->series === null ? null : [
                'name' => $this->series->name,
                'slug' => $this->series->slug,
                'tagline' => $this->series->tagline,
            ]),
            'fruits' => $this->whenLoaded('fruits', fn (): array => $this->fruits
                ->map(fn ($fruit): array => [
                    'name' => $fruit->name,
                    'slug' => $fruit->slug,
                    'isSignature' => $fruit->is_signature,
                ])->all()),
            'image' => $this->images->first()?->path,
            'images' => $this->whenLoaded('images', fn (): array => $this->images
                ->map(fn (ProductImage $image): array => [
                    'path' => $image->path,
                    'alt' => $image->alt,
                ])->all()),
            'variants' => $this->variants->map(fn (ProductVariant $variant): array => [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'label' => $variant->label,
                'dilution' => $variant->dilution,
                'packaging' => $variant->packaging,
                'priceMinor' => $variant->price_minor,
                'price' => $variant->price()->eur(),
                'priceBgn' => $variant->price()->bgn(),
                'stock' => $variant->stock,
                'inStock' => $variant->isInStock(),
            ])->all(),
            'ingredients' => $this->whenLoaded('ingredients', fn (): array => $this->ingredients
                ->map(fn (ProductIngredient $ingredient): array => [
                    'name' => $ingredient->name,
                    'percentage' => $ingredient->percentage === null ? null : (float) $ingredient->percentage,
                    'isAdded' => $ingredient->is_added,
                ])->all()),
            'nutrition' => $this->whenLoaded('nutrition', fn (): ?array => $this->nutrition === null ? null : [
                'kcalPer100' => $this->nutrition->kcal_per_100,
                'kjPer100' => $this->nutrition->kj_per_100,
                'fruitContentPercent' => $this->nutrition->fruit_content_percent === null
                    ? null
                    : (float) $this->nutrition->fruit_content_percent,
            ]),
            'hasNothingAdded' => $this->hasNothingAdded(),
            'inStock' => $this->isInStock(),
            'fromPrice' => $cheapest?->price()->eur(),
            'fromPriceBgn' => $cheapest?->price()->bgn(),
            'hasMultipleVariants' => $this->variants->count() > 1,
        ];
    }
}
