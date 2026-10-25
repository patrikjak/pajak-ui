<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class ColorPickerTest extends TestCase
{
    public function testRendersSwatchHexInputAndHiddenPanel(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::color-picker name="primary_color" label="Primary colour" value="#2a9d8f" />',
        );

        $this->assertStringContainsString('data-pajak-color-picker', $html);
        $this->assertStringContainsString('for="primary_color">Primary colour', $html);
        $this->assertStringContainsString('data-pajak-color-swatch', $html);
        $this->assertStringContainsString('name="primary_color"', $html);
        $this->assertStringContainsString('value="#2a9d8f"', $html);
        $this->assertStringContainsString('--pajak-color: #2A9D8F', $html);
        $this->assertStringContainsString('maxlength="7"', $html);
        $this->assertStringNotContainsString('is-empty', $html);
        $this->assertStringContainsString('data-pajak-color-area', $html);
        $this->assertStringContainsString('data-pajak-color-hue', $html);
        $this->assertMatchesRegularExpression('/data-pajak-color-panel\s+hidden/', $html);
    }

    public function testOnlyTheTextInputIsSubmitted(): void
    {
        $html = (string) $this->blade('<x-pajak-form::color-picker name="c" value="#FFFFFF" />');

        $this->assertSame(1, substr_count($html, 'name="'));
    }

    public function testEmptyOrInvalidValueMarksSwatchEmpty(): void
    {
        $empty = (string) $this->blade('<x-pajak-form::color-picker name="c" />');
        $invalid = (string) $this->blade('<x-pajak-form::color-picker name="c" value="red" />');

        $this->assertStringContainsString('is-empty', $empty);
        $this->assertStringContainsString('is-empty', $invalid);
        $this->assertStringNotContainsString('--pajak-color', $empty);
    }

    public function testPresetsRenderValidColoursOnly(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::color-picker name="c" :swatches="[\'#e76f51\', \'nope\', \'#2A9D8F\']" />',
        );

        $this->assertSame(2, substr_count($html, 'data-pajak-color-preset='));
        $this->assertStringContainsString('data-pajak-color-preset="#E76F51"', $html);
        $this->assertStringNotContainsString('nope', $html);
    }

    public function testNoPresetsContainerWithoutSwatches(): void
    {
        $html = (string) $this->blade('<x-pajak-form::color-picker name="c" />');

        $this->assertStringNotContainsString('data-pajak-color-presets', $html);
    }

    public function testInlineHasNoSwatchButtonAndAnOpenPanel(): void
    {
        $html = (string) $this->blade('<x-pajak-form::color-picker name="c" :inline="true" value="#112233" />');

        $this->assertStringContainsString('pajak-color-picker--inline', $html);
        $this->assertStringNotContainsString('data-pajak-color-swatch', $html);
        $this->assertDoesNotMatchRegularExpression('/data-pajak-color-panel\s+hidden/', $html);
    }

    public function testErrorSetsErrorState(): void
    {
        $html = (string) $this->blade('<x-pajak-form::color-picker name="c" error="Pick a colour." />');

        $this->assertStringContainsString('pajak-input--error', $html);
        $this->assertStringContainsString('Pick a colour.', $html);
    }

    public function testDisabledDisablesTextSwatchAndPresets(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::color-picker name="c" value="#FFFFFF" :swatches="[\'#000000\']" :disabled="true" />',
        );

        $this->assertSame(3, preg_match_all('/\sdisabled\b/', $html));
    }
}
