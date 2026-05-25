<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Form;

use ArrayIterator;
use Pajak\Ui\Common\Dto\UploadedFile;
use Pajak\Ui\Common\Enums\State;
use Pajak\Ui\Form\View\Components\Dropzone;
use Pajak\Ui\Tests\Unit\TestCase;

final class DropzoneTest extends TestCase
{
    public function testInputIdFallsBackToName(): void
    {
        $dropzone = new Dropzone('documents');

        $this->assertSame('documents', $dropzone->inputId());
    }

    public function testInputIdUsesExplicitId(): void
    {
        $dropzone = new Dropzone('documents', true, [], null, State::Default, false, 'my-dropzone');

        $this->assertSame('my-dropzone', $dropzone->inputId());
    }

    public function testResolvedStateReturnsErrorWhenErrorSet(): void
    {
        $dropzone = new Dropzone('documents', true, [], null, State::Default, false, null, 'File is required');

        $this->assertSame(State::Error, $dropzone->resolvedState());
    }

    public function testResolvedStateReturnsStatePropWhenNoError(): void
    {
        $dropzone = new Dropzone('documents', true, [], null, State::Success);

        $this->assertSame(State::Success, $dropzone->resolvedState());
    }

    public function testResolvedStateDefaultsToDefaultWhenNoErrorAndNoState(): void
    {
        $dropzone = new Dropzone('documents');

        $this->assertSame(State::Default, $dropzone->resolvedState());
    }

    public function testFilesArrayIsStoredFromArray(): void
    {
        $file = new UploadedFile(1, 'document.pdf', 1024, '/uploads/document.pdf');
        $dropzone = new Dropzone('documents', true, [$file]);

        $this->assertCount(1, $dropzone->files);
        $this->assertSame($file, $dropzone->files[0]);
    }

    public function testFilesArrayIsStoredFromIterable(): void
    {
        $file = new UploadedFile(1, 'document.pdf', 1024, '/uploads/document.pdf');
        $dropzone = new Dropzone('documents', true, new ArrayIterator([$file]));

        $this->assertCount(1, $dropzone->files);
        $this->assertSame($file, $dropzone->files[0]);
    }

    public function testFormatSizeBytes(): void
    {
        $dropzone = new Dropzone('documents');

        $this->assertSame('512 B', $dropzone->formatSize(512));
    }

    public function testFormatSizeKilobytes(): void
    {
        $dropzone = new Dropzone('documents');

        $this->assertSame('1.5 KB', $dropzone->formatSize(1536));
    }

    public function testFormatSizeMegabytes(): void
    {
        $dropzone = new Dropzone('documents');

        $this->assertSame('2.5 MB', $dropzone->formatSize(2621440));
    }

    public function testFormatSizeExactlyOneKilobyte(): void
    {
        $dropzone = new Dropzone('documents');

        $this->assertSame('1.0 KB', $dropzone->formatSize(1024));
    }

    public function testFormatSizeExactlyOneMegabyte(): void
    {
        $dropzone = new Dropzone('documents');

        $this->assertSame('1.0 MB', $dropzone->formatSize(1_048_576));
    }
}
