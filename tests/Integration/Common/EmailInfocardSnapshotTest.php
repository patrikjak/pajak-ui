<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\Email\EmailAccent;
use Pajak\Ui\Tests\Integration\TestCase;

final class EmailInfocardSnapshotTest extends TestCase
{
    public function testDefaultEmailInfocard(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-infocard>Row content</x-pajak::email-infocard>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testEmailInfocardWithTitle(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-infocard title="Order Summary">Row content</x-pajak::email-infocard>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDefaultEmailInfocardRow(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-infocard-row label="Total" value="€99.00" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testEmailInfocardRowWithBlueAccent(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-infocard-row label="Status" value="Paid" :accent="$accent" />',
            ['accent' => EmailAccent::Blue],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testEmailInfocardRowWithGreenAccent(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-infocard-row label="Status" value="Confirmed" :accent="$accent" />',
            ['accent' => EmailAccent::Green],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
