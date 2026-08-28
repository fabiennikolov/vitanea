<?php

declare(strict_types=1);

namespace App\Enums;

enum DeliveryMethod: string
{
    case EcontOffice = 'econt_office';
    case SpeedyOffice = 'speedy_office';
    case Address = 'address';

    public function label(): string
    {
        return match ($this) {
            self::EcontOffice => 'До офис на Еконт',
            self::SpeedyOffice => 'До офис на Спиди',
            self::Address => 'До адрес',
        };
    }

    /**
     * Free shipping applies to courier offices only, matching the policy
     * published on the current site.
     */
    public function qualifiesForFreeShipping(): bool
    {
        return $this !== self::Address;
    }

    public function baseCostMinor(): int
    {
        return match ($this) {
            self::EcontOffice, self::SpeedyOffice => 499,
            self::Address => 699,
        };
    }
}
