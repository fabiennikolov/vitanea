<?php

declare(strict_types=1);

namespace App\Support;

use InvalidArgumentException;

/**
 * A monetary amount held in integer minor units.
 *
 * Prices are never stored or passed around as floats. Bulgaria's euro
 * changeover uses a fixed, legally mandated conversion rate, and shops are
 * required to show both currencies during the dual-display period — so every
 * amount can render as euro, as lev, or as both.
 */
final readonly class Money
{
    /**
     * The irrevocably fixed BGN/EUR conversion rate.
     */
    public const float BGN_PER_EUR = 1.95583;

    public function __construct(public int $minor)
    {
        //
    }

    public static function fromMinor(int $minor): self
    {
        return new self($minor);
    }

    /**
     * Build from a decimal amount, e.g. 2.70 becomes 270 minor units.
     */
    public static function fromDecimal(float|int|string $amount): self
    {
        if (! is_numeric($amount)) {
            throw new InvalidArgumentException("Amount [{$amount}] is not numeric.");
        }

        return new self((int) round((float) $amount * 100));
    }

    /**
     * The euro amount, e.g. "2,70 €".
     */
    public function eur(): string
    {
        return self::format($this->minor).' €';
    }

    /**
     * The lev equivalent at the fixed rate, e.g. "5,28 лв.".
     */
    public function bgn(): string
    {
        return self::format($this->bgnMinor()).' лв.';
    }

    /**
     * Both currencies, as required during the changeover, e.g. "2,70 € (5,28 лв.)".
     */
    public function dual(): string
    {
        return $this->eur().' ('.$this->bgn().')';
    }

    /**
     * The lev equivalent in minor units.
     */
    public function bgnMinor(): int
    {
        return (int) round($this->minor * self::BGN_PER_EUR);
    }

    public function plus(self $other): self
    {
        return new self($this->minor + $other->minor);
    }

    public function times(int $quantity): self
    {
        return new self($this->minor * $quantity);
    }

    public function isZero(): bool
    {
        return $this->minor === 0;
    }

    /**
     * Bulgarian convention: comma for decimals, narrow no-break space for thousands.
     */
    private static function format(int $minor): string
    {
        $negative = $minor < 0;
        $units = intdiv(abs($minor), 100);
        $fraction = abs($minor) % 100;

        $formatted = number_format($units, 0, ',', "\u{202F}")
            .','.str_pad((string) $fraction, 2, '0', STR_PAD_LEFT);

        return $negative ? '−'.$formatted : $formatted;
    }
}
