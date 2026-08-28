<?php

use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\PageController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\SearchController;
use App\Http\Controllers\Shop\SeriesController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Storefront
|--------------------------------------------------------------------------
|
| Slugs are Bulgarian because the audience is. The five primary routes mirror
| the client's mockup navigation and map onto the four brand pillars —
| "защо-арония" and "науката" are top-level on purpose, not footer links.
|
| These render page shells today; phases 3–4 fill them with real catalog and
| brand content.
|
*/

Route::get('/', HomeController::class)->name('home');

Route::get('продукти', [ProductController::class, 'index'])->name('products.index');
Route::get('продукти/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('серии', [SeriesController::class, 'index'])->name('series.index');
Route::get('търсене', [SearchController::class, 'suggest'])->name('search.suggest');
Route::inertia('защо-арония', 'shop/Aronia')->name('aronia');
Route::inertia('науката', 'shop/Science')->name('science');
Route::inertia('за-нас', 'shop/About')->name('about');
Route::get('контакти', [PageController::class, 'contact'])->name('contact');
Route::get('количка', [CartController::class, 'index'])->name('cart.index');
Route::post('количка', [CartController::class, 'store'])->name('cart.store');
Route::patch('количка', [CartController::class, 'update'])->name('cart.update');
Route::delete('количка/{variant}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('поръчка', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('поръчка', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('поръчка/{order}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');

Route::get('доставка', [PageController::class, 'delivery'])->name('pages.delivery');
Route::get('общи-условия', [PageController::class, 'terms'])->name('pages.terms');
Route::get('поверителност', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('връщане-на-стока', [PageController::class, 'returns'])->name('pages.returns');

Route::get('sitemap.xml', SitemapController::class)->name('sitemap');
