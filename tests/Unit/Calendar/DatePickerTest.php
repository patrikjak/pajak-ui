<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Calendar;

use Pajak\Ui\Calendar\View\Components\DatePicker;
use Pajak\Ui\Tests\Unit\TestCase;

final class DatePickerTest extends TestCase
{
    public function testInputIdFallsBackToName(): void
    {
        $picker = new DatePicker(name: 'birthday');

        $this->assertSame('birthday', $picker->inputId());
    }

    public function testInputIdUsesExplicitId(): void
    {
        $picker = new DatePicker(name: 'birthday', id: 'bday-picker');

        $this->assertSame('bday-picker', $picker->inputId());
    }

    public function testResolvedPlaceholderUsesExplicitValue(): void
    {
        $picker = new DatePicker(name: 'birthday', placeholder: 'Choose a date');

        $this->assertSame('Choose a date', $picker->resolvedPlaceholder());
    }

    public function testResolvedPlaceholderFallsBackToRangePlaceholder(): void
    {
        $picker = new DatePicker(name: 'period', range: true);

        $this->assertSame(__('pajak::ui.calendar.range_placeholder'), $picker->resolvedPlaceholder());
    }

    public function testResolvedPlaceholderFallsBackToSinglePlaceholder(): void
    {
        $picker = new DatePicker(name: 'birthday');

        $this->assertSame(__('pajak::ui.calendar.placeholder'), $picker->resolvedPlaceholder());
    }
}
