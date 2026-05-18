<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class SectionSnapshotTest extends TestCase
{
    public function testDefaultSection(): void
    {
        $html = (string) $this->blade('<x-pajak-form::section></x-pajak-form::section>');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSectionWithTitle(): void
    {
        $blade = '<x-pajak-form::section title="Personal information"'
            . ' description="Tell us about yourself"></x-pajak-form::section>';
        $html = (string) $this->blade($blade);

        $this->assertMatchesHtmlSnapshot($html);
    }
}
