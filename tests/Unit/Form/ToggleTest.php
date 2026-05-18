<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Form;

use Pajak\Ui\Form\View\Components\Toggle;
use Pajak\Ui\Tests\Unit\TestCase;

final class ToggleTest extends TestCase
{
    public function testInputIdFallsBackToName(): void
    {
        $toggle = new Toggle(name: 'notifications');

        $this->assertSame('notifications', $toggle->inputId());
    }

    public function testInputIdUsesExplicitId(): void
    {
        $toggle = new Toggle(name: 'notifications', id: 'notif-toggle');

        $this->assertSame('notif-toggle', $toggle->inputId());
    }
}
