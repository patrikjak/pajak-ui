<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Dto\BreadcrumbItem;
use Pajak\Ui\Common\Enums\BreadcrumbSeparator;
use Pajak\Ui\Common\Enums\BreadcrumbVariant;
use Pajak\Ui\Tests\Integration\TestCase;

final class BreadcrumbsSnapshotTest extends TestCase
{
    public function testDefaultBreadcrumbs(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::breadcrumbs :items="$items" />',
            [
                'items' => [
                    BreadcrumbItem::link('Returns', '/returns'),
                    BreadcrumbItem::link('2025', '/returns/2025'),
                    BreadcrumbItem::current('Donations'),
                ],
            ],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSlashSeparator(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::breadcrumbs :items="$items" :separator="$separator" />',
            [
                'items' => [
                    BreadcrumbItem::link('Settings', '/settings'),
                    BreadcrumbItem::link('Account', '/settings/account'),
                    BreadcrumbItem::current('Payment methods'),
                ],
                'separator' => BreadcrumbSeparator::Slash,
            ],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithHomeIcon(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::breadcrumbs :items="$items" />',
            [
                'items' => [
                    BreadcrumbItem::home(),
                    BreadcrumbItem::link('Clients', '/clients'),
                    BreadcrumbItem::current('Acme Sp. z o.o.'),
                ],
            ],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testCompactVariant(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::breadcrumbs :items="$items" :variant="$variant" />',
            [
                'items' => [
                    BreadcrumbItem::link('Settings', '/settings'),
                    BreadcrumbItem::link('Notifications', '/settings/notifications'),
                    BreadcrumbItem::current('Email'),
                ],
                'variant' => BreadcrumbVariant::Compact,
            ],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testPillVariant(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::breadcrumbs :items="$items" :variant="$variant" />',
            [
                'items' => [
                    BreadcrumbItem::home('/'),
                    BreadcrumbItem::link('Reports', '/reports'),
                    BreadcrumbItem::current('Q1 2026'),
                ],
                'variant' => BreadcrumbVariant::Pill,
            ],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSingleItem(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::breadcrumbs :items="$items" />',
            [
                'items' => [
                    BreadcrumbItem::current('Dashboard'),
                ],
            ],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testEmptyItems(): void
    {
        $html = (string) $this->blade('<x-pajak::breadcrumbs />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
