<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Calendar;

use Pajak\Ui\Tests\Integration\TestCase;

final class DatePickerSnapshotTest extends TestCase
{
    public function testDefaultDatePicker(): void
    {
        $html = (string) $this->blade('<x-pajak-calendar::date-picker name="birthday" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testRangeDatePicker(): void
    {
        $html = (string) $this->blade('<x-pajak-calendar::date-picker name="period" :range="true" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
