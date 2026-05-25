<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Tests\Integration\TestCase;

final class EmailCtaSnapshotTest extends TestCase
{
    public function testPrimaryEmailCta(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-cta href="https://example.com">Click here</x-pajak::email-cta>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSecondaryEmailCta(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-cta href="https://example.com" :secondary="true">View details</x-pajak::email-cta>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testEmailCtaWithCustomColor(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-cta href="https://example.com" color="#10b981">Confirm</x-pajak::email-cta>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
