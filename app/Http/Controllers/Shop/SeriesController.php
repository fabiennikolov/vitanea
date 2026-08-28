<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Series;
use Inertia\Inertia;
use Inertia\Response;

class SeriesController extends Controller
{
    public function index(): Response
    {
        $series = Series::query()
            ->with(['products' => fn ($query) => $query
                ->active()
                ->with(['category', 'variants', 'images', 'ingredients'])
                ->orderBy('position')])
            ->orderBy('position')
            ->get();

        return Inertia::render('shop/Series', [
            'series' => $series->map(fn (Series $entry): array => [
                'name' => $entry->name,
                'slug' => $entry->slug,
                'tagline' => $entry->tagline,
                'description' => $entry->description,
                'products' => ProductResource::collection($entry->products)->resolve(),
            ])->all(),
        ]);
    }
}
