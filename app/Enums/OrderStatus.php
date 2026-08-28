<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Очаква обработка',
            self::Confirmed => 'Потвърдена',
            self::Shipped => 'Изпратена',
            self::Delivered => 'Доставена',
            self::Cancelled => 'Отказана',
        };
    }
}
