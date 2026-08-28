<?php

declare(strict_types=1);
use Database\Seeders\CatalogSeeder;

use function Pest\Laravel\get;

beforeEach(function () {
    $this->seed(CatalogSeeder::class);
});

test('the catalog lists every active product by default', function () {
    get(route('products.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('shop/Products')->has('products', 22));
});

test('a single fruit narrows the catalog', function () {
    $response = get(route('products.index', ['fruit' => ['malina']]));

    $products = $response->viewData('page')['props']['products'];

    expect($products)->not->toBeEmpty();

    foreach ($products as $product) {
        expect(collect($product['fruits'])->pluck('slug'))->toContain('malina');
    }
});

test('several fruits OR together rather than narrowing further', function () {
    $single = get(route('products.index', ['fruit' => ['malina']]))
        ->viewData('page')['props']['products'];

    $both = get(route('products.index', ['fruit' => ['malina', 'yagoda']]))
        ->viewData('page')['props']['products'];

    // Selecting a second fruit must widen the result, never shrink it.
    expect(count($both))->toBeGreaterThan(count($single));

    foreach ($both as $product) {
        $slugs = collect($product['fruits'])->pluck('slug');
        expect($slugs->contains('malina') || $slugs->contains('yagoda'))->toBeTrue();
    }
});

test('fruit combines with category as an AND', function () {
    $products = get(route('products.index', [
        'fruit' => ['aroniya'],
        'category' => 'chayove',
    ]))->viewData('page')['props']['products'];

    expect($products)->not->toBeEmpty();

    foreach ($products as $product) {
        expect($product['category']['slug'])->toBe('chayove');
        expect(collect($product['fruits'])->pluck('slug'))->toContain('aroniya');
    }
});

test('an unknown fruit slug is rejected', function () {
    get(route('products.index', ['fruit' => ['not-a-fruit']]))
        ->assertSessionHasErrors('fruit.0');
});

test('the in-stock filter hides sold-out products', function () {
    $products = get(route('products.index', ['in_stock' => 1]))
        ->viewData('page')['props']['products'];

    expect($products)->not->toBeEmpty();

    foreach ($products as $product) {
        expect($product['inStock'])->toBeTrue();
    }
});

test('sorting by price ascending orders by the cheapest variant', function () {
    $products = get(route('products.index', ['sort' => 'price-asc']))
        ->viewData('page')['props']['products'];

    $prices = collect($products)
        ->map(fn (array $product): int => collect($product['variants'])->min('priceMinor'))
        ->all();

    expect($prices)->toBe(collect($prices)->sort()->values()->all());
});
