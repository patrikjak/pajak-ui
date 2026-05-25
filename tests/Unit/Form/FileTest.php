<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Form;

use Pajak\Ui\Common\Enums\State;
use Pajak\Ui\Form\View\Components\File;
use Pajak\Ui\Tests\Unit\TestCase;

final class FileTest extends TestCase
{
    public function testInputIdFallsBackToName(): void
    {
        $file = new File(name: 'attachment');

        $this->assertSame('attachment', $file->inputId());
    }

    public function testInputIdUsesExplicitId(): void
    {
        $file = new File(name: 'attachment', id: 'my-file');

        $this->assertSame('my-file', $file->inputId());
    }

    public function testResolvedStateReturnsErrorWhenErrorSet(): void
    {
        $file = new File(name: 'attachment', error: 'File is required');

        $this->assertSame(State::Error, $file->resolvedState());
    }

    public function testResolvedStateReturnsStatePropWhenNoError(): void
    {
        $file = new File(name: 'attachment', state: State::Success);

        $this->assertSame(State::Success, $file->resolvedState());
    }

    public function testResolvedStateDefaultsToDefaultWhenNoErrorAndNoState(): void
    {
        $file = new File(name: 'attachment');

        $this->assertSame(State::Default, $file->resolvedState());
    }
}
