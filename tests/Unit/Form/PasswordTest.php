<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Form;

use Pajak\Ui\Common\Enums\State;
use Pajak\Ui\Form\View\Components\Password;
use Pajak\Ui\Tests\Unit\TestCase;

final class PasswordTest extends TestCase
{
    public function testConfirmationId(): void
    {
        $password = new Password('password');

        $this->assertSame('password_confirmation', $password->confirmationId());
    }

    public function testLabelTextFallsBackToTranslation(): void
    {
        $password = new Password('password');

        $this->assertSame(__('pajak::ui.form.password.label'), $password->labelText());
    }

    public function testLabelTextUsesExplicitLabel(): void
    {
        $password = new Password('password', 'Your password');

        $this->assertSame('Your password', $password->labelText());
    }

    public function testConfirmationStateReturnsErrorWhenConfirmationErrorSet(): void
    {
        $password = new Password('password', null, false, null, null, 'Passwords do not match');

        $this->assertSame(State::Error, $password->confirmationState());
    }

    public function testConfirmationStateReturnsDefaultStateWhenNoError(): void
    {
        $password = new Password('password');

        $this->assertSame(State::Default, $password->confirmationState());
    }
}
