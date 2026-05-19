<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\AvatarColor;
use Pajak\Ui\Common\Enums\AvatarSize;
use Pajak\Ui\Common\Enums\AvatarStatus;
use Pajak\Ui\Tests\Integration\TestCase;

final class AvatarSnapshotTest extends TestCase
{
    public function testDefaultAvatar(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::avatar initials="JK" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testAvatarSizes(): void
    {
        foreach (AvatarSize::cases() as $size) {
            $html = (string) $this->blade(
                '<x-pajak::avatar initials="JK" :size="$size" />',
                ['size' => $size],
            );

            $this->assertMatchesHtmlSnapshot($html);
        }
    }

    public function testAvatarColors(): void
    {
        foreach (AvatarColor::cases() as $color) {
            $html = (string) $this->blade(
                '<x-pajak::avatar initials="JK" :color="$color" />',
                ['color' => $color],
            );

            $this->assertMatchesHtmlSnapshot($html);
        }
    }

    public function testAvatarWithOnlineStatus(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::avatar initials="JK" :status="$status" />',
            ['status' => AvatarStatus::Online],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testAvatarWithAwayStatus(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::avatar initials="AW" :color="$color" :status="$status" />',
            ['color' => AvatarColor::Sand, 'status' => AvatarStatus::Away],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testAvatarWithOfflineStatus(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::avatar initials="MN" :color="$color" :status="$status" />',
            ['color' => AvatarColor::Teal, 'status' => AvatarStatus::Offline],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testAvatarWithRing(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::avatar initials="JK" :ring="true" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testAvatarGroup(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::avatar-group :overflow="$overflow">
                <x-pajak::avatar initials="JK" />
                <x-pajak::avatar initials="AW" :color="$sand" />
                <x-pajak::avatar initials="MN" :color="$teal" />
            </x-pajak::avatar-group>',
            ['overflow' => 4, 'sand' => AvatarColor::Sand, 'teal' => AvatarColor::Teal],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testAvatarGroupNoOverflow(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::avatar-group>
                <x-pajak::avatar initials="JK" />
                <x-pajak::avatar initials="AW" :color="$sand" />
            </x-pajak::avatar-group>',
            ['sand' => AvatarColor::Sand],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
