<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Tests\Integration\TestCase;

final class EmailNoteSnapshotTest extends TestCase
{
    public function testEmailNote(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-note>This is a note.</x-pajak::email-note>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
