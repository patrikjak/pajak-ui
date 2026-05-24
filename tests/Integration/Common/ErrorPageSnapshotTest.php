<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Tests\Integration\TestCase;

final class ErrorPageSnapshotTest extends TestCase
{
    public function testDefault404(): void
    {
        $html = (string) $this->blade('<x-pajak::error-page :code="404" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDefault500(): void
    {
        $html = (string) $this->blade('<x-pajak::error-page :code="500" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDefault403(): void
    {
        $html = (string) $this->blade('<x-pajak::error-page :code="403" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDefault401(): void
    {
        $html = (string) $this->blade('<x-pajak::error-page :code="401" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDefault503(): void
    {
        $html = (string) $this->blade('<x-pajak::error-page :code="503" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testCustomTitleAndDescription(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::error-page
                :code="404"
                title="That page is gone"
                description="We couldn't locate the resource you requested."
            />
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithBrandSlot(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::error-page :code="404">
                <x-slot:brand>
                    <div style="width:30px;height:30px;background:#5386E4;border-radius:9px;"></div>
                    <span style="font-weight:700;">MyApp</span>
                </x-slot:brand>
            </x-pajak::error-page>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithActionsSlot(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::error-page :code="500">
                <x-slot:actions>
                    <button type="button">Refresh page</button>
                </x-slot:actions>
            </x-pajak::error-page>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithFooterSlot(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::error-page :code="404">
                <x-slot:footer>
                    Having trouble? <a href="mailto:support@example.com">Contact support</a>
                </x-slot:footer>
            </x-pajak::error-page>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testUnknownCodeRendersNeutral(): void
    {
        $html = (string) $this->blade('<x-pajak::error-page :code="418" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
