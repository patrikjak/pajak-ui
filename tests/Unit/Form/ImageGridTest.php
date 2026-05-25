<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Form;

use ArrayIterator;
use Pajak\Ui\Common\Dto\UploadedFile;
use Pajak\Ui\Common\Enums\State;
use Pajak\Ui\Form\View\Components\ImageGrid;
use Pajak\Ui\Tests\Unit\TestCase;

final class ImageGridTest extends TestCase
{
    public function testInputIdFallsBackToName(): void
    {
        $grid = new ImageGrid('gallery');

        $this->assertSame('gallery', $grid->inputId());
    }

    public function testInputIdUsesExplicitId(): void
    {
        $grid = new ImageGrid('gallery', [], null, State::Default, false, 'my-grid');

        $this->assertSame('my-grid', $grid->inputId());
    }

    public function testResolvedStateReturnsErrorWhenErrorSet(): void
    {
        $grid = new ImageGrid('gallery', [], null, State::Default, false, null, 'At least one image required');

        $this->assertSame(State::Error, $grid->resolvedState());
    }

    public function testResolvedStateReturnsStatePropWhenNoError(): void
    {
        $grid = new ImageGrid('gallery', [], null, State::Success);

        $this->assertSame(State::Success, $grid->resolvedState());
    }

    public function testResolvedStateDefaultsToDefaultWhenNoErrorAndNoState(): void
    {
        $grid = new ImageGrid('gallery');

        $this->assertSame(State::Default, $grid->resolvedState());
    }

    public function testImagesArrayIsStoredFromArray(): void
    {
        $image = new UploadedFile(1, 'photo.jpg', 204800, '/uploads/photo.jpg');
        $grid = new ImageGrid('gallery', [$image]);

        $this->assertCount(1, $grid->images);
        $this->assertSame($image, $grid->images[0]);
    }

    public function testImagesArrayIsStoredFromIterable(): void
    {
        $image = new UploadedFile(1, 'photo.jpg', 204800, '/uploads/photo.jpg');
        $grid = new ImageGrid('gallery', new ArrayIterator([$image]));

        $this->assertCount(1, $grid->images);
        $this->assertSame($image, $grid->images[0]);
    }

    public function testEmptyImagesArray(): void
    {
        $grid = new ImageGrid('gallery');

        $this->assertSame([], $grid->images);
    }
}
