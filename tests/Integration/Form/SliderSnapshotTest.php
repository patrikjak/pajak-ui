<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class SliderSnapshotTest extends TestCase
{
    public function testDefaultSlider(): void
    {
        $html = (string) $this->blade('<x-pajak-form::slider name="volume" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testRangeSlider(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::slider name="price" :range="true" :value="20" :value-max="80" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
