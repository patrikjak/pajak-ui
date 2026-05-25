<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\Detail\DetailVariant;
use Pajak\Ui\Tests\Integration\TestCase;

final class DetailSnapshotTest extends TestCase
{
    public function testBasicDetail(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::detail>
                <x-pajak::detail-row key="Name">Jan Kowalski</x-pajak::detail-row>
                <x-pajak::detail-row key="Email">jan.kowalski@example.com</x-pajak::detail-row>
                <x-pajak::detail-row key="Role">Administrator</x-pajak::detail-row>
            </x-pajak::detail>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDetailWithHeader(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::detail title="Taxpayer profile" action-label="Edit" action-href="/edit">
                <x-pajak::detail-row key="Full name">Jan Kowalski</x-pajak::detail-row>
                <x-pajak::detail-row key="PESEL" :mono="true">90010112345</x-pajak::detail-row>
                <x-pajak::detail-row key="Notes" :muted="true">No notes added</x-pajak::detail-row>
            </x-pajak::detail>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testCompactVariant(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::detail :variant="$variant" title="Employer">
                <x-pajak::detail-row key="Company">Acme Sp. z o.o.</x-pajak::detail-row>
                <x-pajak::detail-row key="NIP" :mono="true">123-456-78-90</x-pajak::detail-row>
            </x-pajak::detail>',
            ['variant' => DetailVariant::Compact],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testGrid2Variant(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::detail :variant="$variant" title="Personal details" action-label="Edit" action-href="/edit">
                <x-slot:body>
                    <x-pajak::detail-row key="First name">Jan</x-pajak::detail-row>
                    <x-pajak::detail-row key="Last name">Kowalski</x-pajak::detail-row>
                    <x-pajak::detail-row key="Birth date">01 Jan 1990</x-pajak::detail-row>
                    <x-pajak::detail-row key="Citizenship">Polish</x-pajak::detail-row>
                </x-slot:body>
            </x-pajak::detail>
            BLADE,
            ['variant' => DetailVariant::Grid2],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testFlushVariant(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::detail :variant="$variant">
                <x-pajak::detail-row key="Year">2025</x-pajak::detail-row>
                <x-pajak::detail-row key="Form">PIT-37</x-pajak::detail-row>
                <x-pajak::detail-row key="Submitted" :muted="true">Not yet submitted</x-pajak::detail-row>
            </x-pajak::detail>',
            ['variant' => DetailVariant::Flush],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testCopyableRow(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::detail>
                <x-pajak::detail-row key="Reference no." :copyable="true">
                    <x-pajak::copy-button value="PIT-2025-00184736">
                        <span class="pajak-detail__val--mono">PIT-2025-00184736</span>
                    </x-pajak::copy-button>
                </x-pajak::detail-row>
            </x-pajak::detail>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testMonoAndMutedModifiers(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::detail>
                <x-pajak::detail-row key="Code" :mono="true">ABC-123</x-pajak::detail-row>
                <x-pajak::detail-row key="Note" :muted="true">None added</x-pajak::detail-row>
            </x-pajak::detail>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
