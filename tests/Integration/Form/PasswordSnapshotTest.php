<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class PasswordSnapshotTest extends TestCase
{
    public function testDefaultPassword(): void
    {
        $html = (string) $this->blade('<x-pajak-form::password name="password" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testPasswordWithConfirmation(): void
    {
        $html = (string) $this->blade('<x-pajak-form::password name="password" :confirmation="true" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
