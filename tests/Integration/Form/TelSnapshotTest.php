<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Form\Enums\TelPattern;
use Pajak\Ui\Tests\Integration\TestCase;

final class TelSnapshotTest extends TestCase
{
    public function testDefaultTel(): void
    {
        $html = (string) $this->blade('<x-pajak-form::tel name="phone" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTelWithLabel(): void
    {
        $html = (string) $this->blade('<x-pajak-form::tel name="phone" label="Phone number" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTelWithError(): void
    {
        $html = (string) $this->blade('<x-pajak-form::tel name="phone" error="Invalid phone number" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTelWithSkPattern(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::tel name="phone" :pattern="$pattern" />',
            ['pattern' => TelPattern::Sk],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTelWithCzPattern(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::tel name="phone" :pattern="$pattern" />',
            ['pattern' => TelPattern::Cz],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTelWithCustomStringPattern(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::tel name="phone" pattern="[0-9]{10}" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDisabledTel(): void
    {
        $html = (string) $this->blade('<x-pajak-form::tel name="phone" :disabled="true" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
