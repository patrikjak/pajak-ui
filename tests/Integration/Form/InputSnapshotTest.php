<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class InputSnapshotTest extends TestCase
{
    public function testDefaultInput(): void
    {
        $html = (string) $this->blade('<x-pajak-form::input name="email" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testInputWithLabel(): void
    {
        $html = (string) $this->blade('<x-pajak-form::input name="email" label="Email address" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testInputWithError(): void
    {
        $html = (string) $this->blade('<x-pajak-form::input name="email" error="Invalid email address" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDisabledInput(): void
    {
        $html = (string) $this->blade('<x-pajak-form::input name="email" :disabled="true" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
