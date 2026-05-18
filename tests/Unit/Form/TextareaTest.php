<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Form;

use Pajak\Ui\Common\Enums\State;
use Pajak\Ui\Form\View\Components\Textarea;
use Pajak\Ui\Tests\Unit\TestCase;

final class TextareaTest extends TestCase
{
    public function testInputIdFallsBackToName(): void
    {
        $textarea = new Textarea(name: 'bio');

        $this->assertSame('bio', $textarea->inputId());
    }

    public function testInputIdUsesExplicitId(): void
    {
        $textarea = new Textarea(name: 'bio', id: 'bio-field');

        $this->assertSame('bio-field', $textarea->inputId());
    }

    public function testResolvedStateReturnsErrorWhenErrorSet(): void
    {
        $textarea = new Textarea(name: 'bio', error: 'Too long');

        $this->assertSame(State::Error, $textarea->resolvedState());
    }

    public function testResolvedStateReturnsStatePropWhenNoError(): void
    {
        $textarea = new Textarea(name: 'bio', state: State::Success);

        $this->assertSame(State::Success, $textarea->resolvedState());
    }
}
