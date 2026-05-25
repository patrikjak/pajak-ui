<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Tests\Integration\TestCase;

final class EmailWrapSnapshotTest extends TestCase
{
    public function testEmailWrap(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-wrap>Email content goes here</x-pajak::email-wrap>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
