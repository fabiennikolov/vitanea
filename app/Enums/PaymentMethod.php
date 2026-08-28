<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * The existing OpenCart shop has no card gateway installed at all — no Borica,
 * myPOS, ePay.bg or Stripe — so card payment is a future addition rather than
 * a migration. These two are what Vitanea can actually accept today.
 */
enum PaymentMethod: string
{
    case CashOnDelivery = 'cash_on_delivery';
    case BankTransfer = 'bank_transfer';

    public function label(): string
    {
        return match ($this) {
            self::CashOnDelivery => 'Наложен платеж',
            self::BankTransfer => 'Банков превод',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::CashOnDelivery => 'Плащате в брой при получаване на пратката.',
            self::BankTransfer => 'Ще получите данни за превода по имейл.',
        };
    }
}
