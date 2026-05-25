<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Calendar;

use Pajak\Ui\Calendar\View\Components\DatePicker;
use Pajak\Ui\Tests\Unit\TestCase;

final class DatePickerTest extends TestCase
{
    public function testInputIdFallsBackToName(): void
    {
        $picker = new DatePicker('birthday');

        $this->assertSame('birthday', $picker->inputId());
    }

    public function testInputIdUsesExplicitId(): void
    {
        $picker = new DatePicker('birthday', false, false, null, null, null, null, false, null, null, 'bday-picker');

        $this->assertSame('bday-picker', $picker->inputId());
    }

    public function testResolvedPlaceholderUsesExplicitValue(): void
    {
        // name, range, time, value, start, end, placeholder
        $picker = new DatePicker('birthday', false, false, null, null, null, 'Choose a date');

        $this->assertSame('Choose a date', $picker->resolvedPlaceholder());
    }

    public function testResolvedPlaceholderFallsBackToRangePlaceholder(): void
    {
        // name, range
        $picker = new DatePicker('period', true);

        $this->assertSame(__('pajak::ui.calendar.range_placeholder'), $picker->resolvedPlaceholder());
    }

    public function testResolvedPlaceholderFallsBackToSinglePlaceholder(): void
    {
        $picker = new DatePicker('birthday');

        $this->assertSame(__('pajak::ui.calendar.placeholder'), $picker->resolvedPlaceholder());
    }
}
