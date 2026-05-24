<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Table;

use Pajak\Ui\Common\ValueObject\Money;
use Pajak\Ui\Tests\Unit\TestCase;

final class MoneyTest extends TestCase
{
    public function testPositiveAmountFormatsCorrectly(): void
    {
        $money = new Money(10050);

        $this->assertSame('100.50 €', (string) $money);
    }

    public function testNegativeAmountUsesMinusSign(): void
    {
        $money = new Money(-2550);

        $this->assertSame('−25.50 €', (string) $money);
    }

    public function testCurrencyBeforeFlag(): void
    {
        $money = new Money(1000, '$', 100, true);

        $this->assertSame('$ 10.00', (string) $money);
    }

    public function testNegativeCurrencyBefore(): void
    {
        $money = new Money(-500, '$', 100, true);

        $this->assertSame('$− 5.00', (string) $money);
    }

    public function testToFloat(): void
    {
        $money = new Money(1234);

        $this->assertEqualsWithDelta(12.34, $money->toFloat(), 0.0001);
    }

    public function testDecimalsAreComputedFromMultiplier(): void
    {
        $money = new Money(1000, '€', 1000);

        $this->assertSame(3, $money->decimals);
    }

    public function testZeroAmount(): void
    {
        $money = new Money(0);

        $this->assertSame('0.00 €', (string) $money);
    }
}
