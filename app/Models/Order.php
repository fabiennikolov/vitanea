<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DeliveryMethod;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Support\Money;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $number
 * @property int|null $user_id
 * @property OrderStatus $status
 * @property string $customer_name
 * @property string $customer_email
 * @property string $customer_phone
 * @property DeliveryMethod $delivery_method
 * @property string $delivery_city
 * @property string $delivery_address
 * @property string|null $delivery_postcode
 * @property string|null $notes
 * @property PaymentMethod $payment_method
 * @property int $subtotal_minor
 * @property int $shipping_minor
 * @property int $total_minor
 * @property string $currency
 * @property Carbon|null $paid_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, OrderItem> $items
 */
#[Fillable([
    'number', 'user_id', 'status', 'customer_name', 'customer_email',
    'customer_phone', 'delivery_method', 'delivery_city', 'delivery_address',
    'delivery_postcode', 'notes', 'payment_method', 'subtotal_minor',
    'shipping_minor', 'total_minor', 'currency', 'paid_at',
])]
class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    public function getRouteKeyName(): string
    {
        return 'number';
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function subtotal(): Money
    {
        return Money::fromMinor($this->subtotal_minor);
    }

    public function shipping(): Money
    {
        return Money::fromMinor($this->shipping_minor);
    }

    public function total(): Money
    {
        return Money::fromMinor($this->total_minor);
    }

    /**
     * Order numbers are human-readable and year-scoped, so support can read
     * one over the phone without spelling out a UUID.
     */
    public static function nextNumber(): string
    {
        $year = now()->year;
        $sequence = static::query()->whereYear('created_at', $year)->count() + 1;

        return sprintf('VIT-%d-%04d', $year, $sequence);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'payment_method' => PaymentMethod::class,
            'delivery_method' => DeliveryMethod::class,
            'paid_at' => 'datetime',
        ];
    }
}
