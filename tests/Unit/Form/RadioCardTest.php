<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Form;

use Pajak\Ui\Form\View\Components\RadioCard;
use Pajak\Ui\Tests\Unit\TestCase;

final class RadioCardTest extends TestCase
{
    public function testInputIdFallsBackToSlug(): void
    {
        $radioCard = new RadioCard(name: 'foo', value: 'bar');

        $this->assertSame('foo_bar', $radioCard->inputId());
    }

    public function testInputIdUsesExplicitId(): void
    {
        $radioCard = new RadioCard(name: 'foo', value: 'bar', id: 'my-radio-card');

        $this->assertSame('my-radio-card', $radioCard->inputId());
    }
}
