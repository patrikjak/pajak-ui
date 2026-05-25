<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class FileSnapshotTest extends TestCase
{
    public function testDefaultFile(): void
    {
        $html = (string) $this->blade('<x-pajak-form::file name="attachment" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testFileWithLabel(): void
    {
        $html = (string) $this->blade('<x-pajak-form::file name="attachment" label="Attachment" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testFileWithError(): void
    {
        $html = (string) $this->blade('<x-pajak-form::file name="attachment" error="File is required" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDisabledFile(): void
    {
        $html = (string) $this->blade('<x-pajak-form::file name="attachment" :disabled="true" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testFileWithAccept(): void
    {
        $html = (string) $this->blade('<x-pajak-form::file name="attachment" accept=".pdf,.docx" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
