<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\AccordionMode;
use Pajak\Ui\Common\Enums\AccordionVariant;
use Pajak\Ui\Tests\Integration\TestCase;

final class AccordionSnapshotTest extends TestCase
{
    public function testDefaultAccordion(): void
    {
        $html = (string) $this->blade('<x-pajak::accordion></x-pajak::accordion>');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDefaultAccordionItem(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::accordion-item title="Personal information" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testOpenAccordionItem(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
<x-pajak::accordion-item title="Personal information" :open="true">Content here.</x-pajak::accordion-item>
BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testAccordionItemWithSubtitle(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::accordion-item title="Income sources" subtitle="Salary, contracts, and other earnings" />
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testAccordionItemWithBadge(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::accordion-item title="Documents" badge="12" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testFlushVariant(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::accordion :variant="$variant"></x-pajak::accordion>',
            ['variant' => AccordionVariant::Flush],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testFaqVariant(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::accordion :variant="$variant"></x-pajak::accordion>',
            ['variant' => AccordionVariant::Faq],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testFilledVariant(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::accordion :variant="$variant"></x-pajak::accordion>',
            ['variant' => AccordionVariant::Filled],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testMultiMode(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::accordion :mode="$mode"></x-pajak::accordion>',
            ['mode' => AccordionMode::Multi],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testFullDefaultAccordion(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::accordion>
                <x-pajak::accordion-item title="Step 1" badge="Done" :open="true">
                    Identity confirmed.
                </x-pajak::accordion-item>
                <x-pajak::accordion-item title="Step 2" subtitle="Income and documents">
                    Add your income sources.
                </x-pajak::accordion-item>
            </x-pajak::accordion>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
