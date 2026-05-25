<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Form;

use Pajak\Ui\Form\View\Components\Slider;
use Pajak\Ui\Tests\Unit\TestCase;

final class SliderTest extends TestCase
{
    public function testInputIdFallsBackToName(): void
    {
        $slider = new Slider('volume');

        $this->assertSame('volume', $slider->inputId());
    }

    public function testInputIdUsesExplicitId(): void
    {
        $slider = new Slider('volume', 0, 100, 1, 0, false, false, null, false, 'vol-slider');

        $this->assertSame('vol-slider', $slider->inputId());
    }

    public function testResolvedValueMaxFallsBackToMax(): void
    {
        $slider = new Slider('volume', 0, 80.0);

        $this->assertSame(80.0, $slider->resolvedValueMax());
    }

    public function testResolvedValueMaxUsesExplicitValue(): void
    {
        $slider = new Slider('volume', 0, 100.0, 1, 0, false, false, 75.0);

        $this->assertSame(75.0, $slider->resolvedValueMax());
    }

    public function testFillPercentReturnsZeroWhenMinEqualsMax(): void
    {
        $slider = new Slider('volume', 50.0, 50.0, 1, 50.0);

        $this->assertSame(0.0, $slider->fillPercent());
    }

    public function testFillMinPercent(): void
    {
        $slider = new Slider('volume', 0.0, 100.0, 1, 25.0);

        $this->assertSame(25.0, $slider->fillMinPercent());
    }

    public function testFillMaxPercent(): void
    {
        $slider = new Slider('volume', 0.0, 100.0, 1, 0, false, false, 75.0);

        $this->assertSame(75.0, $slider->fillMaxPercent());
    }
}
