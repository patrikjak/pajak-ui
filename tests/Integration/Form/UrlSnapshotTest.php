<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class UrlSnapshotTest extends TestCase
{
    public function testDefaultUrl(): void
    {
        $html = (string) $this->blade('<x-pajak-form::url name="website" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testUrlWithLabel(): void
    {
        $html = (string) $this->blade('<x-pajak-form::url name="website" label="Website URL" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testUrlWithError(): void
    {
        $html = (string) $this->blade('<x-pajak-form::url name="website" error="Invalid URL" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDisabledUrl(): void
    {
        $html = (string) $this->blade('<x-pajak-form::url name="website" :disabled="true" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testUrlWithValue(): void
    {
        $html = (string) $this->blade('<x-pajak-form::url name="website" value="https://example.com" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
