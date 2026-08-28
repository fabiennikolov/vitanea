<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CartStoreRequest;
use App\Http\Requests\Shop\CartUpdateRequest;
use App\Models\ProductVariant;
use App\Support\Cart;
use App\Support\Money;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __construct(private readonly Cart $cart)
    {
        //
    }

    public function index(): Response
    {
        $subtotal = $this->cart->subtotalMinor();
        $remaining = Cart::FREE_SHIPPING_THRESHOLD_MINOR - $subtotal;

        return Inertia::render('shop/Cart', [
            'lines' => $this->cart->linesForDisplay(),
            'subtotal' => Money::fromMinor($subtotal)->eur(),
            'subtotalBgn' => Money::fromMinor($subtotal)->bgn(),
            'freeShippingThreshold' => Money::fromMinor(Cart::FREE_SHIPPING_THRESHOLD_MINOR)->eur(),
            'freeShippingRemaining' => $remaining > 0 ? Money::fromMinor($remaining)->eur() : null,
        ]);
    }

    public function store(CartStoreRequest $request): RedirectResponse
    {
        $variantId = (int) $request->integer('variant_id');
        $quantity = (int) $request->integer('quantity');

        $this->cart->add($variantId, $quantity);

        $variant = ProductVariant::with('product')->find($variantId);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => $variant === null
                ? 'Продуктът е добавен в количката.'
                : trim(sprintf(
                    '%s · %s%s',
                    $variant->product->name,
                    $variant->label,
                    $quantity > 1 ? ' × '.$quantity : '',
                )),
        ]);

        return back();
    }

    public function update(CartUpdateRequest $request): RedirectResponse
    {
        $this->cart->update(
            (int) $request->integer('variant_id'),
            (int) $request->integer('quantity'),
        );

        return back();
    }

    public function destroy(int $variant): RedirectResponse
    {
        $this->cart->remove($variant);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Продуктът е премахнат от количката.',
        ]);

        return back();
    }
}
