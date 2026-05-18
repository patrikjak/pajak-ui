<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class GroupSnapshotTest extends TestCase
{
    public function testDefaultGroup(): void
    {
        $html = (string) $this->blade('<x-pajak-form::group></x-pajak-form::group>');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testInlineGroup(): void
    {
        $html = (string) $this->blade('<x-pajak-form::group :inline="true"></x-pajak-form::group>');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
