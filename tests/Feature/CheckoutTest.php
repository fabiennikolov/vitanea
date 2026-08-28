<?php

declare(strict_types=1);

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Support\Cart;
use Database\Seeders\CatalogSeeder;
use Illuminate\Support\Facades\Mail;
use Inertia\Support\SessionKey;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

beforeEach(function () {
    $this->seed(CatalogSeeder::class);
});

test('a variant can be added to the cart', function () {
    $variant = ProductVariant::where('stock', '>', 0)->firstOrFail();

    post(route('cart.store'), ['variant_id' => $variant->id, 'quantity' => 2])
        ->assertRedirect();

    get(route('cart.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('shop/Cart')
            ->has('lines', 1)
            ->where('lines.0.quantity', 2));
});

test('the cart badge counts every unit, not every line', function () {
    $variants = ProductVariant::where('stock', '>', 0)->take(2)->get();

    post(route('cart.store'), ['variant_id' => $variants[0]->id, 'quantity' => 2]);
    post(route('cart.store'), ['variant_id' => $variants[1]->id, 'quantity' => 3]);

    get(route('cart.index'))
        ->assertInertia(fn ($page) => $page->where('cartCount', 5));
});

test('adding the same variant twice increases its quantity', function () {
    $variant = ProductVariant::where('stock', '>', 0)->firstOrFail();

    post(route('cart.store'), ['variant_id' => $variant->id, 'quantity' => 1]);
    post(route('cart.store'), ['variant_id' => $variant->id, 'quantity' => 2]);

    get(route('cart.index'))
        ->assertInertia(fn ($page) => $page->has('lines', 1)->where('lines.0.quantity', 3));
});

test('setting a quantity to zero removes the line', function () {
    $variant = ProductVariant::where('stock', '>', 0)->firstOrFail();
    post(route('cart.store'), ['variant_id' => $variant->id, 'quantity' => 1]);

    $this->patch(route('cart.update'), ['variant_id' => $variant->id, 'quantity' => 0])
        ->assertRedirect();

    get(route('cart.index'))->assertInertia(fn ($page) => $page->has('lines', 0));
});

test('checkout redirects to the cart when the cart is empty', function () {
    get(route('checkout.create'))->assertRedirect(route('cart.index'));
});

test('checkout creates an order with correct totals and clears the cart', function () {
    Mail::fake();

    $variant = ProductVariant::where('stock', '>', 0)->firstOrFail();
    post(route('cart.store'), ['variant_id' => $variant->id, 'quantity' => 3]);

    post(route('checkout.store'), [
        'customer_name' => 'Иван Петров',
        'customer_email' => 'ivan@example.com',
        'customer_phone' => '+359888123456',
        'delivery_method' => 'econt_office',
        'delivery_city' => 'Пловдив',
        'delivery_address' => 'Офис Рогошко шосе',
        'payment_method' => 'cash_on_delivery',
        'terms' => '1',
    ])->assertRedirect();

    $order = Order::firstOrFail();
    $expectedSubtotal = $variant->price_minor * 3;

    expect($order->subtotal_minor)->toBe($expectedSubtotal);
    expect($order->shipping_minor)->toBe(499);
    expect($order->total_minor)->toBe($expectedSubtotal + 499);
    expect($order->status)->toBe(OrderStatus::Pending);
    expect($order->payment_method)->toBe(PaymentMethod::CashOnDelivery);
    expect($order->items)->toHaveCount(1);
    expect($order->items->first()->quantity)->toBe(3);

    // The cart is emptied so a refresh cannot double-order.
    get(route('cart.index'))->assertInertia(fn ($page) => $page->has('lines', 0));
});

test('order line items snapshot the price at purchase time', function () {
    Mail::fake();

    $variant = ProductVariant::where('stock', '>', 0)->firstOrFail();
    $originalPrice = $variant->price_minor;

    post(route('cart.store'), ['variant_id' => $variant->id, 'quantity' => 1]);
    post(route('checkout.store'), [
        'customer_name' => 'Иван Петров',
        'customer_email' => 'ivan@example.com',
        'customer_phone' => '+359888123456',
        'delivery_method' => 'econt_office',
        'delivery_city' => 'Пловдив',
        'delivery_address' => 'Офис',
        'payment_method' => 'bank_transfer',
        'terms' => '1',
    ]);

    $variant->update(['price_minor' => $originalPrice + 500]);

    expect(Order::firstOrFail()->items->first()->unit_price_minor)->toBe($originalPrice);
});

test('free shipping applies to courier offices above the threshold', function () {
    Mail::fake();

    $variant = ProductVariant::where('stock', '>', 0)->firstOrFail();
    $quantity = (int) ceil(Cart::FREE_SHIPPING_THRESHOLD_MINOR / $variant->price_minor);

    post(route('cart.store'), ['variant_id' => $variant->id, 'quantity' => $quantity]);
    post(route('checkout.store'), [
        'customer_name' => 'Иван Петров',
        'customer_email' => 'ivan@example.com',
        'customer_phone' => '+359888123456',
        'delivery_method' => 'econt_office',
        'delivery_city' => 'Пловдив',
        'delivery_address' => 'Офис',
        'payment_method' => 'cash_on_delivery',
        'terms' => '1',
    ]);

    expect(Order::firstOrFail()->shipping_minor)->toBe(0);
});

test('delivery to an address never qualifies for free shipping', function () {
    Mail::fake();

    $variant = ProductVariant::where('stock', '>', 0)->firstOrFail();
    $quantity = (int) ceil(Cart::FREE_SHIPPING_THRESHOLD_MINOR / $variant->price_minor);

    post(route('cart.store'), ['variant_id' => $variant->id, 'quantity' => $quantity]);
    post(route('checkout.store'), [
        'customer_name' => 'Иван Петров',
        'customer_email' => 'ivan@example.com',
        'customer_phone' => '+359888123456',
        'delivery_method' => 'address',
        'delivery_city' => 'Пловдив',
        'delivery_address' => 'ул. Тест 1',
        'payment_method' => 'cash_on_delivery',
        'terms' => '1',
    ]);

    expect(Order::firstOrFail()->shipping_minor)->toBe(699);
});

test('checkout requires accepting the terms', function () {
    $variant = ProductVariant::where('stock', '>', 0)->firstOrFail();
    post(route('cart.store'), ['variant_id' => $variant->id, 'quantity' => 1]);

    post(route('checkout.store'), [
        'customer_name' => 'Иван Петров',
        'customer_email' => 'ivan@example.com',
        'customer_phone' => '+359888123456',
        'delivery_method' => 'econt_office',
        'delivery_city' => 'Пловдив',
        'delivery_address' => 'Офис',
        'payment_method' => 'cash_on_delivery',
    ])->assertSessionHasErrors('terms');

    expect(Order::count())->toBe(0);
});

test('a confirmation email is sent to the customer', function () {
    Mail::fake();

    $variant = ProductVariant::where('stock', '>', 0)->firstOrFail();
    post(route('cart.store'), ['variant_id' => $variant->id, 'quantity' => 1]);
    post(route('checkout.store'), [
        'customer_name' => 'Иван Петров',
        'customer_email' => 'ivan@example.com',
        'customer_phone' => '+359888123456',
        'delivery_method' => 'speedy_office',
        'delivery_city' => 'Пловдив',
        'delivery_address' => 'Офис',
        'payment_method' => 'cash_on_delivery',
        'terms' => '1',
    ]);

    Mail::assertSent(OrderConfirmation::class, fn ($mail) => $mail->hasTo('ivan@example.com'));
});

test('the confirmation page shows the order', function () {
    Mail::fake();

    $variant = ProductVariant::where('stock', '>', 0)->firstOrFail();
    post(route('cart.store'), ['variant_id' => $variant->id, 'quantity' => 1]);
    post(route('checkout.store'), [
        'customer_name' => 'Иван Петров',
        'customer_email' => 'ivan@example.com',
        'customer_phone' => '+359888123456',
        'delivery_method' => 'econt_office',
        'delivery_city' => 'Пловдив',
        'delivery_address' => 'Офис',
        'payment_method' => 'cash_on_delivery',
        'terms' => '1',
    ]);

    $order = Order::firstOrFail();

    get(route('checkout.confirmation', $order))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('shop/OrderConfirmation')
            ->where('order.number', $order->number));
});

test('adding to the cart flashes a toast naming the product', function () {
    $variant = ProductVariant::with('product')->where('stock', '>', 0)->firstOrFail();

    post(route('cart.store'), ['variant_id' => $variant->id, 'quantity' => 2]);

    $flash = session(SessionKey::FLASH_DATA);

    expect($flash)->toHaveKey('toast');
    expect($flash['toast']['type'])->toBe('success');
    expect($flash['toast']['message'])->toContain($variant->product->name);
    expect($flash['toast']['message'])->toContain('× 2');
});

test('removing from the cart flashes a toast', function () {
    $variant = ProductVariant::where('stock', '>', 0)->firstOrFail();
    post(route('cart.store'), ['variant_id' => $variant->id, 'quantity' => 1]);

    $this->delete(route('cart.destroy', $variant->id));

    $flash = session(SessionKey::FLASH_DATA);

    expect($flash['toast']['message'])->toContain('премахнат');
});
