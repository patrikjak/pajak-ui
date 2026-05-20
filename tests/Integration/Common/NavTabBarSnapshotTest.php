<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Tests\Integration\TestCase;

final class NavTabBarSnapshotTest extends TestCase
{
    public function testDefaultTabBar(): void
    {
        $html = (string) $this->blade('<x-pajak::nav-tab-bar />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithTabs(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::nav-tab-bar>
                <x-pajak::nav-tab label="Home" :active="true">
                    <x-slot:icon>
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        </svg>
                    </x-slot:icon>
                </x-pajak::nav-tab>
                <x-pajak::nav-tab label="Returns">
                    <x-slot:icon>
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        </svg>
                    </x-slot:icon>
                </x-pajak::nav-tab>
                <x-pajak::nav-tab label="Docs" :dot="true">
                    <x-slot:icon>
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                        </svg>
                    </x-slot:icon>
                </x-pajak::nav-tab>
                <x-pajak::nav-tab label="Profile">
                    <x-slot:icon>
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </x-slot:icon>
                </x-pajak::nav-tab>
            </x-pajak::nav-tab-bar>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTabWithoutIcon(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::nav-tab label="Home" :active="true" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTabWithDot(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::nav-tab label="Docs" :dot="true">
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="3" width="7" height="9" rx="1.5"/>
                    </svg>
                </x-slot:icon>
            </x-pajak::nav-tab>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
