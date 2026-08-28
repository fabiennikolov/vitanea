<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\DeliveryMethod;
use App\Models\ProductVariant;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Collection;

/**
 * A session-backed cart.
 *
 * Only the variant id and quantity are stored. Prices, names and stock are
 * always re-read from the database when the cart is rendered, so a price
 * change or a sell-out can never be carried along in a stale session.
 */
class Cart
{
    /**
     * Orders over this qualify for free delivery to a courier office.
     *
     * The current shop sets this at €80, which is roughly 27 bottles of the
     * €3.00 juice — high enough that almost nobody reaches it. Flagged in the
     * docs as worth revisiting with the client.
     */
    public const int FREE_SHIPPING_THRESHOLD_MINOR = 8000;

    private const string SESSION_KEY = 'cart.items';

    public function __construct(private readonly Session $session)
    {
        //
    }

    public function add(int $variantId, int $quantity = 1): void
    {
        $items = $this->rawItems();
        $items[$variantId] = ($items[$variantId] ?? 0) + max(1, $quantity);

        $this->persist($items);
    }

    public function update(int $variantId, int $quantity): void
    {
        $items = $this->rawItems();

        if ($quantity < 1) {
            unset($items[$variantId]);
        } else {
            $items[$variantId] = $quantity;
        }

        $this->persist($items);
    }

    public function remove(int $variantId): void
    {
        $items = $this->rawItems();
        unset($items[$variantId]);

        $this->persist($items);
    }

    public function clear(): void
    {
        $this->session->forget('cart');
    }

    public function isEmpty(): bool
    {
        return $this->rawItems() === [];
    }

    public function count(): int
    {
        return array_sum($this->rawItems());
    }

    /**
     * Cart lines, resolved against the live catalog.
     *
     * A variant that has been deleted or deactivated simply drops out — the
     * cart heals itself rather than erroring on stale data.
     *
     * @return Collection<int, CartLine>
     */
    public function lines(): Collection
    {
        $items = $this->rawItems();

        if ($items === []) {
            return collect();
        }

        /** @var Collection<int, ProductVariant> $variants */
        $variants = ProductVariant::query()
            ->whereIn('id', array_keys($items))
            ->where('is_active', true)
            ->with('product.images')
            ->get();

        return $variants->map(function (ProductVariant $variant) use ($items): CartLine {
            $quantity = $items[$variant->id];

            return new CartLine(
                variantId: $variant->id,
                productName: $variant->product->name,
                productSlug: $variant->product->slug,
                variantLabel: $variant->label,
                sku: $variant->sku,
                image: $variant->product->images->first()?->path,
                quantity: $quantity,
                inStock: $variant->isInStock(),
                availableStock: $variant->stock,
                unitPriceMinor: $variant->price_minor,
                lineTotalMinor: $variant->price_minor * $quantity,
            );
        })->values();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function linesForDisplay(): array
    {
        return $this->lines()->map(fn (CartLine $line): array => $line->toArray())->all();
    }

    public function subtotalMinor(): int
    {
        return (int) $this->lines()->sum(fn (CartLine $line): int => $line->lineTotalMinor);
    }

    public function shippingMinor(DeliveryMethod $method): int
    {
        if ($this->isEmpty()) {
            return 0;
        }

        if ($method->qualifiesForFreeShipping()
            && $this->subtotalMinor() >= self::FREE_SHIPPING_THRESHOLD_MINOR) {
            return 0;
        }

        return $method->baseCostMinor();
    }

    public function totalMinor(DeliveryMethod $method): int
    {
        return $this->subtotalMinor() + $this->shippingMinor($method);
    }

    /**
     * @return array<int, int> variant id => quantity
     */
    private function rawItems(): array
    {
        $stored = $this->session->get(self::SESSION_KEY, []);

        if (! is_array($stored)) {
            return [];
        }

        $items = [];

        foreach ($stored as $variantId => $quantity) {
            if (! is_numeric($variantId) || ! is_numeric($quantity) || (int) $quantity < 1) {
                continue;
            }

            $items[(int) $variantId] = (int) $quantity;
        }

        return $items;
    }

    /**
     * @param  array<int, int>  $items
     */
    private function persist(array $items): void
    {
        if ($items === []) {
            $this->session->forget('cart');

            return;
        }

        $this->session->put(self::SESSION_KEY, $items);
    }
}
