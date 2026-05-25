<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class NumberSnapshotTest extends TestCase
{
    public function testDefaultNumber(): void
    {
        $html = (string) $this->blade('<x-pajak-form::number name="quantity" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testNumberWithLabel(): void
    {
        $html = (string) $this->blade('<x-pajak-form::number name="quantity" label="Quantity" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testNumberWithError(): void
    {
        $html = (string) $this->blade('<x-pajak-form::number name="quantity" error="Must be a positive number" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDisabledNumber(): void
    {
        $html = (string) $this->blade('<x-pajak-form::number name="quantity" :disabled="true" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testNumberWithValue(): void
    {
        $html = (string) $this->blade('<x-pajak-form::number name="quantity" :value="42" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
