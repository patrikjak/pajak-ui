<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\Modal\ModalSize;
use Pajak\Ui\Tests\Integration\TestCase;

final class ModalSnapshotTest extends TestCase
{
    public function testDefaultModal(): void
    {
        $html = (string) $this->blade('<x-pajak::modal id="test-modal" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithTitleAndDescription(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::modal id="test-modal" title="Submit your tax return?">
                <x-slot:description>Once submitted, your PIT-37 will be sent to the tax office.</x-slot:description>
            </x-pajak::modal>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithIcon(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::modal id="test-modal" title="Delete this draft?">
                <x-slot:icon><svg width="20" height="20" aria-hidden="true"></svg></x-slot:icon>
                <x-slot:description>This can't be undone.</x-slot:description>
            </x-pajak::modal>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithBody(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::modal id="test-modal" title="Privacy policy">
                <p>Your data is used exclusively to prepare your tax return.</p>
            </x-pajak::modal>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithFooter(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::modal id="test-modal" title="Submit your tax return?">
                <x-slot:footer>
                    <button>Cancel</button>
                    <button>Submit return</button>
                </x-slot:footer>
            </x-pajak::modal>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSmallSize(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::modal id="test-modal" :size="$size" title="Notice" />',
            ['size' => ModalSize::Sm],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testLargeSize(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::modal id="test-modal" :size="$size" title="Add a deduction" />',
            ['size' => ModalSize::Lg],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSheetVariant(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::modal id="test-modal" :sheet="true" title="Sign with mObywatel?">
                <x-slot:description>You'll be redirected to the mObywatel app.</x-slot:description>
                <x-slot:footer>
                    <button>Not now</button>
                    <button>Continue</button>
                </x-slot:footer>
            </x-pajak::modal>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testNotDismissible(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::modal id="test-modal" :dismissible="false" title="Submitting your return…" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testOpenProp(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::modal id="test-modal" :open="true" title="Return submitted" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testFullModal(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::modal id="test-modal" title="Add a deduction" :size="$size">
                <x-slot:icon><svg width="20" height="20" aria-hidden="true"></svg></x-slot:icon>
                <x-slot:description>Categorize the expense and we'll apply it to your return.</x-slot:description>
                <p>Form content goes here.</p>
                <x-slot:footer>
                    <button>Cancel</button>
                    <button>Add deduction</button>
                </x-slot:footer>
            </x-pajak::modal>
            BLADE,
            ['size' => ModalSize::Lg],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
