<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Tests\Integration\TestCase;

final class EmailBodySnapshotTest extends TestCase
{
    public function testEmailBody(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-body>Body content here</x-pajak::email-body>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
