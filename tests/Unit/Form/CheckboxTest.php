<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Form;

use Pajak\Ui\Form\View\Components\Checkbox;
use Pajak\Ui\Tests\Unit\TestCase;

final class CheckboxTest extends TestCase
{
    public function testInputIdFallsBackToName(): void
    {
        $checkbox = new Checkbox('agree', 1, 'I agree');

        $this->assertSame('agree', $checkbox->inputId());
    }

    public function testInputIdUsesExplicitId(): void
    {
        $checkbox = new Checkbox('agree', 1, 'I agree', false, false, null, 'agree-checkbox');

        $this->assertSame('agree-checkbox', $checkbox->inputId());
    }
}
