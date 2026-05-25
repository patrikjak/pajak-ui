<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class EmailSnapshotTest extends TestCase
{
    public function testDefaultEmail(): void
    {
        $html = (string) $this->blade('<x-pajak-form::email name="email" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testEmailWithLabel(): void
    {
        $html = (string) $this->blade('<x-pajak-form::email name="email" label="Email address" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testEmailWithError(): void
    {
        $html = (string) $this->blade('<x-pajak-form::email name="email" error="Invalid email address" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDisabledEmail(): void
    {
        $html = (string) $this->blade('<x-pajak-form::email name="email" :disabled="true" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testEmailWithValue(): void
    {
        $html = (string) $this->blade('<x-pajak-form::email name="email" value="user@example.com" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
