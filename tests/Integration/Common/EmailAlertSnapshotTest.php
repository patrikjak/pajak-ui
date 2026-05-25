<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\AlertType;
use Pajak\Ui\Tests\Integration\TestCase;

final class EmailAlertSnapshotTest extends TestCase
{
    public function testInfoEmailAlert(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-alert>This is an informational message.</x-pajak::email-alert>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSuccessEmailAlert(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-alert :type="$type">Your order has been confirmed.</x-pajak::email-alert>',
            ['type' => AlertType::Success],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWarningEmailAlert(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-alert :type="$type">Please verify your email.</x-pajak::email-alert>',
            ['type' => AlertType::Warning],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testErrorEmailAlert(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-alert :type="$type">Something went wrong.</x-pajak::email-alert>',
            ['type' => AlertType::Error],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testEmailAlertWithTitle(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-alert>
                <x-slot:title>Action required</x-slot:title>
                Please update your billing information.
            </x-pajak::email-alert>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
