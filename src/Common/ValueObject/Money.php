<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\ValueObject;

final readonly class Money
{
    public int $decimals;

    public function __construct(
        public int $minorUnits,
        public string $currency = '€',
        public int $multiplier = 100,
        public bool $currencyBefore = false,
    ) {
        $this->decimals = (int) log10($multiplier);
    }

    public function toFloat(): float
    {
        return $this->minorUnits / $this->multiplier;
    }

    public function __toString(): string
    {
        $value = $this->toFloat();
        $sign = $value < 0 ? '−' : '';
        $formatted = number_format(abs($value), $this->decimals);

        return $this->currencyBefore
            ? sprintf('%s%s %s', $this->currency, $sign, $formatted)
            : sprintf('%s%s %s', $sign, $formatted, $this->currency);
    }
}
