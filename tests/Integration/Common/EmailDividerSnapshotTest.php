<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Tests\Integration\TestCase;

final class EmailDividerSnapshotTest extends TestCase
{
    public function testEmailDivider(): void
    {
        $html = (string) $this->blade('<x-pajak::email-divider />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
