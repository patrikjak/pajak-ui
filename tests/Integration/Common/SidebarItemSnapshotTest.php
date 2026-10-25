<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\Method;
use Pajak\Ui\Tests\Integration\TestCase;

final class SidebarItemSnapshotTest extends TestCase
{
    public function testSidebarItemActive(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::sidebar-item href="/dashboard" label="Dashboard" :active="true" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSidebarItemWithCount(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::sidebar-item href="/returns" label="Returns" :count="12" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSidebarItemWarn(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::sidebar-item href="/docs" label="Documents" :count="3" :warn="true" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSidebarItemDisabled(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::sidebar-item href="/admin" label="Admin" :disabled="true" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSidebarItemDanger(): void
    {
        $html = (string) $this->blade('<x-pajak::sidebar-item href="/x" label="Delete" :danger="true" />');

        $this->assertStringContainsString('is-danger', $html);
    }

    public function testSidebarItemWithActionRendersFormButton(): void
    {
        $html = (string) $this->blade('<x-pajak::sidebar-item action="/logout" label="Log out" :danger="true" />');

        $this->assertStringContainsString('<form action="/logout" method="POST"', $html);
        $this->assertStringContainsString('<button', $html);
        $this->assertStringContainsString('type="submit"', $html);
        $this->assertStringContainsString('name="_token"', $html);
        $this->assertStringNotContainsString('<a ', $html);
        $this->assertStringNotContainsString('_method', $html);
    }

    public function testSidebarItemWithActionSpoofsMethod(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::sidebar-item action="/session" :method="$method" label="Out" />',
            ['method' => Method::Delete],
        );

        $this->assertStringContainsString('method="POST"', $html);
        $this->assertStringContainsString('name="_method" value="DELETE"', $html);
    }
}
