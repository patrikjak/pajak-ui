<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Tests\Integration\TestCase;

final class EmailHeroSnapshotTest extends TestCase
{
    public function testDefaultEmailHero(): void
    {
        $html = (string) $this->blade('<x-pajak::email-hero />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testEmailHeroWithSlots(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-hero color="#3b82f6">
                <x-slot:eyebrow>March Update</x-slot:eyebrow>
                <x-slot:title>Welcome aboard!</x-slot:title>
                Get started today.
            </x-pajak::email-hero>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
