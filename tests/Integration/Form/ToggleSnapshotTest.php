<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class ToggleSnapshotTest extends TestCase
{
    public function testDefaultToggle(): void
    {
        $html = (string) $this->blade('<x-pajak-form::toggle name="notifications" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testCheckedToggle(): void
    {
        $html = (string) $this->blade('<x-pajak-form::toggle name="notifications" :checked="true" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDisabledToggle(): void
    {
        $html = (string) $this->blade('<x-pajak-form::toggle name="notifications" :disabled="true" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
