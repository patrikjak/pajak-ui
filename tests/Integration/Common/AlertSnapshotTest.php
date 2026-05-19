<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\AlertType;
use Pajak\Ui\Common\Enums\AlertVariant;
use Pajak\Ui\Tests\Integration\TestCase;

final class AlertSnapshotTest extends TestCase
{
    public function testDefaultAlert(): void
    {
        $html = (string) $this->blade('<x-pajak::alert>Something to know.</x-pajak::alert>');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testInfoAlertWithTitle(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::alert :type="$type" title="Have your PIT-11 ready">It contains all figures you need.</x-pajak::alert>',
            ['type' => AlertType::Info],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSuccessAlert(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::alert :type="$type" title="Return submitted successfully">Confirmation sent.</x-pajak::alert>',
            ['type' => AlertType::Success],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWarningAlert(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::alert :type="$type" title="Deadline approaching">26 days remaining.</x-pajak::alert>',
            ['type' => AlertType::Warning],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testErrorAlert(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::alert :type="$type" title="Missing document">Upload PIT-11 before submitting.</x-pajak::alert>',
            ['type' => AlertType::Error],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDismissibleAlert(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::alert :dismissible="true">You can dismiss this.</x-pajak::alert>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testOutlineVariant(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::alert :type="$type" :variant="$variant" title="Deadline approaching">26 days remaining.</x-pajak::alert>',
            ['type' => AlertType::Warning, 'variant' => AlertVariant::Outline],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testInlineVariant(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::alert :type="$type" :variant="$variant">We auto-saved your progress 2 minutes ago.</x-pajak::alert>',
            ['type' => AlertType::Info, 'variant' => AlertVariant::Inline],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
