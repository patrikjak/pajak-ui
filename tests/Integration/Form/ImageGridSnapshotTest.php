<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Common\Dto\UploadedFile;
use Pajak\Ui\Tests\Integration\TestCase;

final class ImageGridSnapshotTest extends TestCase
{
    public function testDefaultImageGrid(): void
    {
        $html = (string) $this->blade('<x-pajak-form::image-grid name="gallery" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testImageGridWithLabel(): void
    {
        $html = (string) $this->blade('<x-pajak-form::image-grid name="gallery" label="Gallery images" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testImageGridWithImages(): void
    {
        $images = [
            new UploadedFile(1, 'photo1.jpg', 102400, '/uploads/photo1.jpg'),
            new UploadedFile(2, 'photo2.jpg', 204800, '/uploads/photo2.jpg'),
        ];

        $html = (string) $this->blade(
            '<x-pajak-form::image-grid name="gallery" :images="$images" />',
            ['images' => $images],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testImageGridWithError(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::image-grid name="gallery" error="At least one image required" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDisabledImageGrid(): void
    {
        $html = (string) $this->blade('<x-pajak-form::image-grid name="gallery" :disabled="true" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
