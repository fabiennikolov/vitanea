<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Live suggestions for the search overlay.
     *
     * Returns JSON rather than an Inertia response: the overlay queries on
     * every keystroke and must not push history entries or re-render the page
     * behind it. Pressing enter is what performs a real catalog visit.
     */
    public function suggest(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:80'],
        ]);

        $term = trim($filters['q'] ?? '');

        if ($term === '') {
            return response()->json(['products' => []]);
        }

        $products = Product::query()
            ->active()
            ->with(['category', 'variants', 'images'])
            ->where(fn ($match) => $match
                ->where('name', 'like', '%'.$term.'%')
                ->orWhere('short_description', 'like', '%'.$term.'%'))
            ->orderBy('position')
            ->orderBy('name')
            ->limit(6)
            ->get();

        return response()->json([
            'products' => ProductResource::collection($products)->resolve(),
        ]);
    }
}
