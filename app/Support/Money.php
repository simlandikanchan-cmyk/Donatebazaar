<?php

namespace App\Support;

use InvalidArgumentException;

/**
 * Immutable decimal money value (2 decimal places) backed by bcmath strings.
 *
 * All wallet/settlement arithmetic must go through this class so financial
 * balances are never computed with binary floats. Eloquent `decimal:2` casts
 * return strings, so `Money::of()` accepts those as-is.
 */
final class Money
{
    private const SCALE = 2;

    private string $amount;

    private function __construct(string $amount)
    {
        $this->amount = $amount;
    }

    public static function of(float|int|string|null $amount): self
    {
        if ($amount === null) {
            $amount = '0.00';
        }

        if (is_float($amount) || is_int($amount)) {
            $amount = number_format((float) $amount, self::SCALE, '.', '');
        }

        $amount = trim((string) $amount);

        if (! preg_match('/^-?\d+(?:\.\d{1,2})?$/', $amount)) {
            throw new InvalidArgumentException("Invalid money value: {$amount}");
        }

        [$whole, $fraction] = array_pad(explode('.', $amount), 2, null);
        $fraction = str_pad((string) $fraction, self::SCALE, '0');

        return new self($whole.'.'.substr($fraction, 0, self::SCALE));
    }

    public static function zero(): self
    {
        return self::of('0.00');
    }

    public static function sum(iterable $values): self
    {
        $total = self::zero();

        foreach ($values as $value) {
            $total = $total->add($value);
        }

        return $total;
    }

    public function add(self|float|int|string $other): self
    {
        return self::of(bcadd($this->amount, self::of($other)->amount, self::SCALE));
    }

    public function sub(self|float|int|string $other): self
    {
        return self::of(bcsub($this->amount, self::of($other)->amount, self::SCALE));
    }

    public function isEqualTo(self|float|int|string $other): bool
    {
        return bccomp($this->amount, self::of($other)->amount, self::SCALE) === 0;
    }

    public function isGreaterThan(self|float|int|string $other): bool
    {
        return bccomp($this->amount, self::of($other)->amount, self::SCALE) > 0;
    }

    public function isGreaterThanOrEqualTo(self|float|int|string $other): bool
    {
        return bccomp($this->amount, self::of($other)->amount, self::SCALE) >= 0;
    }

    public function isLessThan(self|float|int|string $other): bool
    {
        return bccomp($this->amount, self::of($other)->amount, self::SCALE) < 0;
    }

    public function isPositive(): bool
    {
        return bccomp($this->amount, '0.00', self::SCALE) > 0;
    }

    public function isNegative(): bool
    {
        return bccomp($this->amount, '0.00', self::SCALE) < 0;
    }

    public function isZero(): bool
    {
        return bccomp($this->amount, '0.00', self::SCALE) === 0;
    }

    public function toFloat(): float
    {
        return (float) $this->amount;
    }

    public function toString(): string
    {
        return $this->amount;
    }

    public function __toString(): string
    {
        return $this->amount;
    }
}
