<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Form;

use Pajak\Ui\Form\View\Components\Radio;
use Pajak\Ui\Tests\Unit\TestCase;

final class RadioTest extends TestCase
{
    public function testInputIdFallsBackToSlug(): void
    {
        $radio = new Radio(name: 'foo', value: 'bar');

        $this->assertSame('foo_bar', $radio->inputId());
    }

    public function testInputIdUsesExplicitId(): void
    {
        $radio = new Radio(name: 'foo', value: 'bar', id: 'my-radio');

        $this->assertSame('my-radio', $radio->inputId());
    }
}
