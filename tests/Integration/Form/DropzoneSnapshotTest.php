<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Common\Dto\UploadedFile;
use Pajak\Ui\Tests\Integration\TestCase;

final class DropzoneSnapshotTest extends TestCase
{
    public function testDefaultDropzone(): void
    {
        $html = (string) $this->blade('<x-pajak-form::dropzone name="documents" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDropzoneWithLabel(): void
    {
        $html = (string) $this->blade('<x-pajak-form::dropzone name="documents" label="Upload documents" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDropzoneWithError(): void
    {
        $html = (string) $this->blade('<x-pajak-form::dropzone name="documents" error="At least one file required" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDisabledDropzone(): void
    {
        $html = (string) $this->blade('<x-pajak-form::dropzone name="documents" :disabled="true" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDropzoneWithFiles(): void
    {
        $files = [
            new UploadedFile(1, 'invoice.pdf', 204800, '/uploads/invoice.pdf'),
            new UploadedFile(2, 'contract.docx', 51200, '/uploads/contract.docx'),
        ];

        $html = (string) $this->blade(
            '<x-pajak-form::dropzone name="documents" :files="$files" />',
            ['files' => $files],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDropzoneWithAccept(): void
    {
        $html = (string) $this->blade('<x-pajak-form::dropzone name="documents" accept=".pdf,.docx" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSingleFileDropzone(): void
    {
        $html = (string) $this->blade('<x-pajak-form::dropzone name="document" :multiple="false" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
