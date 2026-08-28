<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Enums\DeliveryMethod;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CheckoutRequest;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Support\Cart;
use App\Support\Money;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function __construct(private readonly Cart $cart)
    {
        //
    }

    public function create(): Response|RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return to_route('cart.index');
        }

        $subtotal = $this->cart->subtotalMinor();

        return Inertia::render('shop/Checkout', [
            'lines' => $this->cart->linesForDisplay(),
            'subtotal' => Money::fromMinor($subtotal)->eur(),
            'subtotalMinor' => $subtotal,
            'freeShippingThresholdMinor' => Cart::FREE_SHIPPING_THRESHOLD_MINOR,
            'deliveryMethods' => array_map(
                fn (DeliveryMethod $method): array => [
                    'value' => $method->value,
                    'label' => $method->label(),
                    'cost' => Money::fromMinor($method->baseCostMinor())->eur(),
                    'costMinor' => $method->baseCostMinor(),
                    'freeOverThreshold' => $method->qualifiesForFreeShipping(),
                ],
                DeliveryMethod::cases(),
            ),
            'paymentMethods' => array_map(
                fn (PaymentMethod $method): array => [
                    'value' => $method->value,
                    'label' => $method->label(),
                    'description' => $method->description(),
                ],
                PaymentMethod::cases(),
            ),
        ]);
    }

    /**
     * Creating an order snapshots every line, so later catalog edits never
     * rewrite what somebody actually bought.
     */
    public function store(CheckoutRequest $request): RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return to_route('cart.index');
        }

        $deliveryMethod = DeliveryMethod::from($request->string('delivery_method')->toString());
        $lines = $this->cart->lines();
        $subtotal = $this->cart->subtotalMinor();
        $shipping = $this->cart->shippingMinor($deliveryMethod);

        $order = DB::transaction(function () use ($request, $deliveryMethod, $lines, $subtotal, $shipping): Order {
            $order = Order::create([
                'number' => Order::nextNumber(),
                'user_id' => $request->user()?->id,
                'status' => OrderStatus::Pending,
                'customer_name' => $request->string('customer_name')->toString(),
                'customer_email' => $request->string('customer_email')->toString(),
                'customer_phone' => $request->string('customer_phone')->toString(),
                'delivery_method' => $deliveryMethod,
                'delivery_city' => $request->string('delivery_city')->toString(),
                'delivery_address' => $request->string('delivery_address')->toString(),
                'delivery_postcode' => $request->string('delivery_postcode')->toString() ?: null,
                'notes' => $request->string('notes')->toString() ?: null,
                'payment_method' => PaymentMethod::from($request->string('payment_method')->toString()),
                'subtotal_minor' => $subtotal,
                'shipping_minor' => $shipping,
                'total_minor' => $subtotal + $shipping,
            ]);

            foreach ($lines as $line) {
                $order->items()->create([
                    'product_variant_id' => $line->variantId,
                    'product_name' => $line->productName,
                    'variant_label' => $line->variantLabel,
                    'sku' => $line->sku,
                    'unit_price_minor' => $line->unitPriceMinor,
                    'quantity' => $line->quantity,
                    'line_total_minor' => $line->lineTotalMinor,
                ]);
            }

            return $order;
        });

        $this->cart->clear();

        Mail::to($order->customer_email)->send(new OrderConfirmation($order));

        return to_route('checkout.confirmation', $order);
    }

    public function confirmation(Order $order): Response
    {
        $order->load('items');

        return Inertia::render('shop/OrderConfirmation', [
            'order' => [
                'number' => $order->number,
                'status' => $order->status->label(),
                'customerName' => $order->customer_name,
                'customerEmail' => $order->customer_email,
                'deliveryMethod' => $order->delivery_method->label(),
                'deliveryCity' => $order->delivery_city,
                'deliveryAddress' => $order->delivery_address,
                'paymentMethod' => $order->payment_method->label(),
                'paymentDescription' => $order->payment_method->description(),
                'subtotal' => $order->subtotal()->eur(),
                'shipping' => $order->shipping()->isZero() ? 'Безплатна' : $order->shipping()->eur(),
                'total' => $order->total()->eur(),
                'totalBgn' => $order->total()->bgn(),
                'items' => $order->items->map(fn ($item): array => [
                    'name' => $item->product_name,
                    'variant' => $item->variant_label,
                    'quantity' => $item->quantity,
                    'lineTotal' => $item->lineTotal()->eur(),
                ])->all(),
            ],
        ]);
    }
}
