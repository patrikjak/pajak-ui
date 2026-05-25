<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Tests\Integration\TestCase;

final class CopyButtonSnapshotTest extends TestCase
{
    public function testPlainTextTrigger(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::copy-button value="Hello, world!">Hello, world!</x-pajak::copy-button>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testButtonTrigger(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::copy-button value="token-abc"><button type="button">Copy token</button></x-pajak::copy-button>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testCodeTrigger(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::copy-button value="npm install pajak/ui">'
            . '<code>npm install pajak/ui</code>'
            . '</x-pajak::copy-button>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithExtraAttributes(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::copy-button value="extra" class="ml-2" id="copy-extra">Copy</x-pajak::copy-button>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testIconVariant(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::copy-button value="sk-abc123xyz" :icon="true" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
