<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Fruit;
use App\Models\Product;
use App\Models\Series;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * The catalog, filterable by category, fruit and series.
     *
     * Fruit is a facet rather than a category so a visitor can see every
     * aronia product across juices, nectars, syrups and teas — something the
     * previous shop could not do, because it split syrups by dilution ratio.
     */
    public function index(Request $request): Response
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:80'],
            'category' => ['nullable', 'string', 'exists:categories,slug'],
            'fruit' => ['nullable', 'array'],
            'fruit.*' => ['string', 'exists:fruits,slug'],
            'series' => ['nullable', 'string', 'exists:series,slug'],
            'sort' => ['nullable', 'string', 'in:default,price-asc,price-desc,name-asc'],
            'in_stock' => ['nullable', 'boolean'],
        ]);

        $products = Product::query()
            ->active()
            ->with(['category', 'series', 'fruits', 'variants', 'ingredients', 'images'])
            // Name and short description only: the long description mentions
            // aronia on nearly every product, so including it would make the
            // signature fruit match the entire catalog.
            ->when($filters['q'] ?? null, fn ($query, string $term) => $query
                ->where(fn ($match) => $match
                    ->where('name', 'like', '%'.$term.'%')
                    ->orWhere('short_description', 'like', '%'.$term.'%')))
            ->when($filters['category'] ?? null, fn ($query, string $slug) => $query
                ->whereHas('category', fn ($category) => $category->where('slug', $slug)))
            // Fruit is multi-select and ORs within the facet: picking малина and
            // арония shows everything containing either, which is what a shopper
            // browsing by flavour expects.
            ->when($filters['fruit'] ?? null, fn ($query, array $slugs) => $query
                ->whereHas('fruits', fn ($fruit) => $fruit->whereIn('slug', $slugs)))
            ->when($filters['series'] ?? null, fn ($query, string $slug) => $query
                ->whereHas('series', fn ($series) => $series->where('slug', $slug)))
            ->when(($filters['sort'] ?? null) === 'name-asc', fn ($query) => $query->orderBy('name'))
            ->when(! isset($filters['sort']) || $filters['sort'] === 'default', fn ($query) => $query
                ->orderBy('position')->orderBy('name'))
            ->get();

        // Sorting by price needs the variant prices, which are loaded rather
        // than joined so a product's cheapest variant decides its position.
        $products = match ($filters['sort'] ?? 'default') {
            'price-asc' => $products->sortBy(fn (Product $product): int => (int) $product->variants->min('price_minor'))->values(),
            'price-desc' => $products->sortByDesc(fn (Product $product): int => (int) $product->variants->min('price_minor'))->values(),
            default => $products,
        };

        if ($filters['in_stock'] ?? false) {
            $products = $products->filter->isInStock()->values();
        }

        return Inertia::render('shop/Products', [
            'products' => ProductResource::collection($products)->resolve(),
            'filters' => $filters,
            'categories' => Category::orderBy('position')->get(['name', 'slug'])->all(),
            'fruits' => Fruit::orderBy('position')->get(['name', 'slug', 'is_signature'])
                ->map(fn (Fruit $fruit): array => [
                    'name' => $fruit->name,
                    'slug' => $fruit->slug,
                    'isSignature' => $fruit->is_signature,
                ])->all(),
            'seriesOptions' => Series::orderBy('position')->get(['name', 'slug'])->all(),
        ]);
    }

    public function show(Product $product): Response
    {
        abort_unless($product->is_active, 404);

        $product->load(['category', 'series', 'fruits', 'variants', 'ingredients', 'images', 'nutrition']);

        $related = Product::query()
            ->active()
            ->where('id', '!=', $product->id)
            ->whereHas('fruits', fn ($query) => $query->whereIn('fruits.id', $product->fruits->pluck('id')))
            ->with(['category', 'variants', 'images', 'ingredients'])
            ->limit(3)
            ->get();

        return Inertia::render('shop/Product', [
            'product' => (new ProductResource($product))->resolve(),
            'related' => ProductResource::collection($related)->resolve(),
        ]);
    }
}
