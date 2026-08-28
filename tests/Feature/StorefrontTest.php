<?php

declare(strict_types=1);

use function Pest\Laravel\get;

test('the storefront pages are publicly reachable', function (string $routeName) {
    get(route($routeName))->assertOk();
})->with([
    'home',
    'products.index',
    'series.index',
    'aronia',
    'science',
    'about',
    'contact',
    'cart.index',
    'pages.delivery',
    'pages.terms',
    'pages.privacy',
    'pages.returns',
]);

test('storefront routes use bulgarian slugs', function () {
    expect(route('products.index', absolute: false))->toBe('/'.rawurlencode('продукти'));
    expect(route('aronia', absolute: false))->toBe('/'.rawurlencode('защо-арония'));
    expect(route('science', absolute: false))->toBe('/'.rawurlencode('науката'));
});

test('the home page renders the storefront hero', function () {
    get(route('home'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('shop/Home'));
});

test('the cms page shells receive their heading', function () {
    get(route('pages.terms'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('shop/Page')
            ->where('heading', 'Общи условия'));
});

test('the cart count is shared with every storefront page', function () {
    get(route('home'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->where('cartCount', 0));
});

test('the cart count reflects quantities held in the session', function () {
    // Session shape is variant id => quantity, per App\Support\Cart.
    $this->withSession(['cart.items' => [1 => 2, 2 => 3]]);

    get(route('home'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->where('cartCount', 5));
});

test('a malformed cart session yields zero rather than an error', function () {
    $this->withSession(['cart.items' => 'not-an-array']);

    get(route('home'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->where('cartCount', 0));
});
