<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\BannerType;
use Pajak\Ui\Tests\Integration\TestCase;

final class BannerSnapshotTest extends TestCase
{
    public function testDefaultInfoBanner(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::banner>We prefilled your return from KAS.</x-pajak::banner>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSuccessBanner(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::banner :type="$type">Return submitted successfully.</x-pajak::banner>',
            ['type' => BannerType::Success],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWarningBanner(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::banner :type="$type">Your filing deadline is in 6 days.</x-pajak::banner>',
            ['type' => BannerType::Warning],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testErrorBanner(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::banner :type="$type">Couldn\'t connect to KAS.</x-pajak::banner>',
            ['type' => BannerType::Error],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testNeutralBanner(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::banner :type="$type">Importing documents from KAS.</x-pajak::banner>',
            ['type' => BannerType::Neutral],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testPromoBanner(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::banner :type="$type">Add your spouse for joint filing.</x-pajak::banner>',
            ['type' => BannerType::Promo],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testBannerWithTitle(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::banner :type="$type" title="We've prefilled your return from KAS">
                3 documents were imported automatically.
            </x-pajak::banner>
            BLADE,
            ['type' => BannerType::Info],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDismissibleBanner(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::banner :dismissible="true">You can dismiss this notice.</x-pajak::banner>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTopVariant(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::banner :top="true">Scheduled maintenance tonight.</x-pajak::banner>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testBannerWithProgress(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::banner :type="$type" :progress="$progress">5 of 8 documents synced.</x-pajak::banner>',
            ['type' => BannerType::Neutral, 'progress' => 62],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testBannerWithActions(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::banner :type="$type">
                Your filing deadline is approaching.
                <x-slot name="actions"><button type="button">Continue filing</button></x-slot>
            </x-pajak::banner>
            BLADE,
            ['type' => BannerType::Warning],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testProgressClamping(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::banner :progress="$progress">Processing.</x-pajak::banner>',
            ['progress' => 150],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
