<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Tests\Integration\TestCase;

final class EmailHeaderSnapshotTest extends TestCase
{
    public function testDefaultEmailHeader(): void
    {
        $html = (string) $this->blade('<x-pajak::email-header />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testEmailHeaderWithTag(): void
    {
        $html = (string) $this->blade('<x-pajak::email-header tag="Newsletter" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
