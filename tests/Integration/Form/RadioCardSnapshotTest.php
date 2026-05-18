<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class RadioCardSnapshotTest extends TestCase
{
    public function testDefaultRadioCard(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::radio-card name="plan" value="basic" label="Basic" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testCheckedRadioCard(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::radio-card name="plan" value="basic" label="Basic" :checked="true" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
