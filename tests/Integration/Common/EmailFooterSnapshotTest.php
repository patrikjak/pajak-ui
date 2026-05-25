<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Tests\Integration\TestCase;

final class EmailFooterSnapshotTest extends TestCase
{
    public function testEmailFooter(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-footer>Footer content</x-pajak::email-footer>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
