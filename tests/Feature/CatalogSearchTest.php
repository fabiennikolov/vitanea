<?php

declare(strict_types=1);
use Database\Seeders\CatalogSeeder;

use function Pest\Laravel\get;

beforeEach(function () {
    $this->seed(CatalogSeeder::class);
});

test('a search term narrows the catalog to matching names', function () {
    $products = get(route('products.index', ['q' => 'пектин']))
        ->assertOk()
        ->viewData('page')['props']['products'];

    expect($products)->not->toBeEmpty();

    foreach ($products as $product) {
        expect(mb_strtolower($product['name'].' '.($product['shortDescription'] ?? '')))
            ->toContain('пектин');
    }
});

test('a search term that matches nothing returns an empty catalog rather than everything', function () {
    get(route('products.index', ['q' => 'няма-такъв-продукт']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('shop/Products')->has('products', 0));
});

test('the search term survives into the filters so it can be shown and removed', function () {
    get(route('products.index', ['q' => 'сироп']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->where('filters.q', 'сироп'));
});

test('suggestions come back as json for the overlay', function () {
    $response = get(route('search.suggest', ['q' => 'арония']))->assertOk();

    $products = $response->json('products');

    expect($products)->not->toBeEmpty();
    // The overlay shows a short list, never the whole catalog.
    expect(count($products))->toBeLessThanOrEqual(6);

    foreach ($products as $product) {
        expect(mb_strtolower($product['name'].' '.($product['shortDescription'] ?? '')))
            ->toContain('арония');
    }
});

test('an empty search suggests nothing rather than the whole catalog', function () {
    get(route('search.suggest', ['q' => ' ']))
        ->assertOk()
        ->assertExactJson(['products' => []]);
});

test('an over-long search term is rejected', function () {
    get(route('search.suggest', ['q' => str_repeat('а', 81)]))
        ->assertSessionHasErrors('q');
});
