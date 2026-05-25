<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Tests\Integration\TestCase;

final class ListSnapshotTest extends TestCase
{
    public function testDefaultList(): void
    {
        $html = (string) $this->blade('<x-pajak::list></x-pajak::list>');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testRowWithBodyOnly(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::list>
                <x-pajak::list-row>
                    <x-slot:title>PIT-11_2025_Acme.pdf</x-slot:title>
                    <x-slot:subtitle>248 KB · Mar 12, 2026</x-slot:subtitle>
                </x-pajak::list-row>
            </x-pajak::list>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testRowWithLeading(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::list>
                <x-pajak::list-row>
                    <x-slot:leading><span class="icon-tile">X</span></x-slot:leading>
                    <x-slot:title>Document</x-slot:title>
                </x-pajak::list-row>
            </x-pajak::list>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testRowWithTrailing(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::list>
                <x-pajak::list-row>
                    <x-slot:title>Two-factor authentication</x-slot:title>
                    <x-slot:subtitle>Adds an extra layer of security</x-slot:subtitle>
                    <x-slot:trailing><span>Enabled</span></x-slot:trailing>
                </x-pajak::list-row>
            </x-pajak::list>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testClickableRow(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::list>
                <x-pajak::list-row :clickable="true">
                    <x-slot:title>Email</x-slot:title>
                    <x-slot:subtitle>jan.kowalski@example.com</x-slot:subtitle>
                </x-pajak::list-row>
            </x-pajak::list>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testFullList(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::list>
                <x-pajak::list-row :clickable="true">
                    <x-slot:leading><span class="icon-tile">A</span></x-slot:leading>
                    <x-slot:title>PIT-11_2025_Acme.pdf</x-slot:title>
                    <x-slot:subtitle>248 KB · Mar 12, 2026</x-slot:subtitle>
                    <x-slot:trailing><span class="badge">Verified</span></x-slot:trailing>
                </x-pajak::list-row>
                <x-pajak::list-row :clickable="true">
                    <x-slot:leading><span class="icon-tile">B</span></x-slot:leading>
                    <x-slot:title>Faktura_internet_2025.pdf</x-slot:title>
                    <x-slot:subtitle>96 KB · Apr 2, 2026</x-slot:subtitle>
                    <x-slot:trailing><span class="badge">Pending</span></x-slot:trailing>
                </x-pajak::list-row>
            </x-pajak::list>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
