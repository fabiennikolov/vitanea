<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;

/**
 * The current site has no XML sitemap at all — /shop/sitemap.xml returns 404 —
 * and its robots.txt is still the one left behind by an old Joomla install.
 */
class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [
            route('home'),
            route('products.index'),
            route('series.index'),
            route('aronia'),
            route('science'),
            route('about'),
            route('contact'),
            route('pages.delivery'),
            route('pages.terms'),
            route('pages.privacy'),
            route('pages.returns'),
        ];

        foreach (Product::query()->active()->pluck('slug') as $slug) {
            $urls[] = route('products.show', $slug);
        }

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
