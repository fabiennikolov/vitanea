<?php

declare(strict_types=1);

namespace App\Support;

/**
 * One resolved line in the cart.
 *
 * A value object rather than a loose array: the shape is enforced at
 * construction, the controllers get real property access, and PHPStan can
 * check callers instead of guessing at array keys.
 */
final readonly class CartLine
{
    public function __construct(
        public int $variantId,
        public string $productName,
        public string $productSlug,
        public string $variantLabel,
        public string $sku,
        public ?string $image,
        public int $quantity,
        public bool $inStock,
        public int $availableStock,
        public int $unitPriceMinor,
        public int $lineTotalMinor,
    ) {
        //
    }

    public function unitPrice(): Money
    {
        return Money::fromMinor($this->unitPriceMinor);
    }

    public function lineTotal(): Money
    {
        return Money::fromMinor($this->lineTotalMinor);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'variantId' => $this->variantId,
            'productName' => $this->productName,
            'productSlug' => $this->productSlug,
            'variantLabel' => $this->variantLabel,
            'sku' => $this->sku,
            'image' => $this->image,
            'quantity' => $this->quantity,
            'inStock' => $this->inStock,
            'availableStock' => $this->availableStock,
            'unitPriceMinor' => $this->unitPriceMinor,
            'unitPrice' => $this->unitPrice()->eur(),
            'lineTotalMinor' => $this->lineTotalMinor,
            'lineTotal' => $this->lineTotal()->eur(),
        ];
    }
}
