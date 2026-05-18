<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class SelectSnapshotTest extends TestCase
{
    public function testDefaultSelect(): void
    {
        $html = (string) $this->blade('<x-pajak-form::select name="country" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSelectWithOptions(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::select name="country" :options="$options" />',
            ['options' => ['sk' => 'Slovakia', 'cz' => 'Czech Republic']],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSelectWithError(): void
    {
        $html = (string) $this->blade('<x-pajak-form::select name="country" error="Please select a country" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testMultipleSelect(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::select name="tags" :multiple="true" :options="$options" />',
            ['options' => ['php' => 'PHP', 'js' => 'JavaScript']],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
