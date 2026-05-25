<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Form;

use Pajak\Ui\Common\Enums\Size;
use Pajak\Ui\Common\Enums\State;
use Pajak\Ui\Form\View\Components\Input;
use Pajak\Ui\Tests\Unit\TestCase;

final class InputTest extends TestCase
{
    public function testInputIdFallsBackToName(): void
    {
        $input = new Input('email');

        $this->assertSame('email', $input->inputId());
    }

    public function testInputIdUsesExplicitId(): void
    {
        $input = new Input('email', 'text', null, null, State::Default, false, Size::Md, 'my-email');

        $this->assertSame('my-email', $input->inputId());
    }

    public function testResolvedStateReturnsErrorWhenErrorSet(): void
    {
        $input = new Input('email', 'text', null, null, State::Default, false, Size::Md, null, 'Invalid email');

        $this->assertSame(State::Error, $input->resolvedState());
    }

    public function testResolvedStateReturnsStatePropWhenNoError(): void
    {
        $input = new Input('email', 'text', null, null, State::Success);

        $this->assertSame(State::Success, $input->resolvedState());
    }
}
