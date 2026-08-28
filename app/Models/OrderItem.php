<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Money;
use Database\Factories\OrderItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $order_id
 * @property int|null $product_variant_id
 * @property string $product_name
 * @property string $variant_label
 * @property string $sku
 * @property int $unit_price_minor
 * @property int $quantity
 * @property int $line_total_minor
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Order $order
 */
#[Fillable([
    'order_id', 'product_variant_id', 'product_name', 'variant_label', 'sku',
    'unit_price_minor', 'quantity', 'line_total_minor',
])]
class OrderItem extends Model
{
    /** @use HasFactory<OrderItemFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function unitPrice(): Money
    {
        return Money::fromMinor($this->unit_price_minor);
    }

    public function lineTotal(): Money
    {
        return Money::fromMinor($this->line_total_minor);
    }
}
