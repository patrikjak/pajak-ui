<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\SpinnerSize;
use Pajak\Ui\Tests\Integration\TestCase;

final class AsyncSnapshotTest extends TestCase
{
    public function testDefault(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::async url="/api/stats" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithSlot(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::async url="/api/stats"><p>Loading…</p></x-pajak::async>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testCustomSizeAndLabel(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::async url="/api/stats" :size="$size" label="Refreshing data" />',
            ['size' => SpinnerSize::Lg],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithSkeletonSlot(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::async url="/api/stats"><x-slot:skeleton><div class="skeleton-placeholder">Loading…</div></x-slot:skeleton></x-pajak::async>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
