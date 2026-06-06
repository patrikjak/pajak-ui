<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\StatCard\StatCardColor;
use Pajak\Ui\Common\Enums\StatCard\StatCardTrend;
use Pajak\Ui\Tests\Integration\TestCase;

final class StatCardSnapshotTest extends TestCase
{
    public function testDefaultStatCard(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::stat-card label="Tenants" value="8" />
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithIcon(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::stat-card label="Tenants" value="8">
                <x-slot:icon><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg></x-slot:icon>
            </x-pajak::stat-card>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSuccessColorWithIcon(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::stat-card label="Active Sites" value="47" :color="$color">
                <x-slot:icon><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg></x-slot:icon>
            </x-pajak::stat-card>
            BLADE,
            ['color' => StatCardColor::Success],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWarningColorWithIcon(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::stat-card label="Open Alerts" value="3" :color="$color">
                <x-slot:icon><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg></x-slot:icon>
            </x-pajak::stat-card>
            BLADE,
            ['color' => StatCardColor::Warning],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSandColorWithIcon(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::stat-card label="Users" value="124" :color="$color">
                <x-slot:icon><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg></x-slot:icon>
            </x-pajak::stat-card>
            BLADE,
            ['color' => StatCardColor::Sand],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testErrorColorWithIcon(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::stat-card label="Errors" value="1" :color="$color">
                <x-slot:icon><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg></x-slot:icon>
            </x-pajak::stat-card>
            BLADE,
            ['color' => StatCardColor::Error],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithUpTrend(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::stat-card label="Tenants" value="8" :trend-direction="$trend">
                <x-slot:trend>+2 this month · 7 active, 1 trial</x-slot:trend>
            </x-pajak::stat-card>',
            ['trend' => StatCardTrend::Up],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithDownTrend(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::stat-card label="Revenue" value="PLN 1,240" :trend-direction="$trend">
                <x-slot:trend>-5% vs last month</x-slot:trend>
            </x-pajak::stat-card>',
            ['trend' => StatCardTrend::Down],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithWarnTrend(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::stat-card label="Open Alerts" value="3" :trend-direction="$trend">
                <x-slot:trend>2 warnings · 1 error</x-slot:trend>
            </x-pajak::stat-card>',
            ['trend' => StatCardTrend::Warn],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithTrendNoDirection(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::stat-card label="Tenants" value="8">
                <x-slot:trend>Data as of yesterday</x-slot:trend>
            </x-pajak::stat-card>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithSubText(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::stat-card label="Active Sites" value="47">
                <x-slot:sub>Across all tenants · 38 published</x-slot:sub>
            </x-pajak::stat-card>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testFullCard(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::stat-card label="Tenants" value="8" :color="$color" :trend-direction="$trend">
                <x-slot:icon><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg></x-slot:icon>
                <x-slot:trend>+2 this month</x-slot:trend>
                <x-slot:sub>7 active, 1 trial</x-slot:sub>
            </x-pajak::stat-card>
            BLADE,
            ['color' => StatCardColor::Primary, 'trend' => StatCardTrend::Up],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
