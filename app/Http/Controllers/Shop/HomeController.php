<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        // The hero and "product in focus" both lead with the 100% juice: it is
        // the only product whose ingredient list is a single line, which makes
        // it the clearest statement of the brand's central claim.
        $focus = Product::query()
            ->active()
            ->where('slug', 'sok-ot-aroniya-100')
            ->with(['category', 'variants', 'ingredients', 'images', 'nutrition'])
            ->first();

        $featured = Product::query()
            ->active()
            ->whereHas('variants', fn ($query) => $query->where('stock', '>', 0))
            ->with(['category', 'variants', 'ingredients', 'images'])
            ->orderBy('position')
            ->limit(3)
            ->get();

        return Inertia::render('shop/Home', [
            'focus' => $focus === null ? null : (new ProductResource($focus))->resolve(),
            'featured' => ProductResource::collection($featured)->resolve(),
            'categories' => Category::query()
                ->withCount('products')
                ->with(['products' => fn ($query) => $query->active()->with('images')->orderBy('position')->limit(1)])
                ->orderBy('position')
                ->limit(3)
                ->get()
                ->map(fn (Category $category): array => [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'productsCount' => $category->products_count,
                    'image' => $category->products->first()?->images->first()?->path,
                ])->all(),
        ]);
    }
}
