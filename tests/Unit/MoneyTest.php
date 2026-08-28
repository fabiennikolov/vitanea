<?php

declare(strict_types=1);

use App\Support\Money;

test('it formats euro amounts the bulgarian way', function () {
    expect(Money::fromMinor(270)->eur())->toBe('2,70 €');
    expect(Money::fromMinor(5)->eur())->toBe('0,05 €');
    expect(Money::fromMinor(0)->eur())->toBe('0,00 €');
});

test('it separates thousands', function () {
    expect(Money::fromMinor(123456)->eur())->toBe("1\u{202F}234,56 €");
});

test('it converts to lev at the fixed changeover rate', function () {
    // The catalog shows 2.70 € as ≈ 5.28 лв. and 3.00 € as ≈ 5.87 лв.
    expect(Money::fromMinor(270)->bgn())->toBe('5,28 лв.');
    expect(Money::fromMinor(300)->bgn())->toBe('5,87 лв.');
    expect(Money::fromMinor(530)->bgn())->toBe('10,37 лв.');
});

test('it renders both currencies for dual display', function () {
    expect(Money::fromMinor(270)->dual())->toBe('2,70 € (5,28 лв.)');
});

test('it builds from a decimal amount without float drift', function () {
    expect(Money::fromDecimal(2.70)->minor)->toBe(270);
    expect(Money::fromDecimal('9.45')->minor)->toBe(945);
    expect(Money::fromDecimal(1.44)->minor)->toBe(144);
});

test('it rejects a non-numeric amount', function () {
    Money::fromDecimal('not a price');
})->throws(InvalidArgumentException::class);

test('it adds and multiplies', function () {
    expect(Money::fromMinor(270)->plus(Money::fromMinor(300))->minor)->toBe(570);
    expect(Money::fromMinor(270)->times(3)->minor)->toBe(810);
});

test('it formats negative amounts with a real minus sign', function () {
    expect(Money::fromMinor(-270)->eur())->toBe('−2,70 €');
});

test('it knows when it is zero', function () {
    expect(Money::fromMinor(0)->isZero())->toBeTrue();
    expect(Money::fromMinor(1)->isZero())->toBeFalse();
});
