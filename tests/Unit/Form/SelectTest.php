<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Form;

use ArrayIterator;
use Pajak\Ui\Common\Enums\State;
use Pajak\Ui\Form\View\Components\Select;
use Pajak\Ui\Tests\Unit\TestCase;

final class SelectTest extends TestCase
{
    public function testInputIdFallsBackToName(): void
    {
        $select = new Select(name: 'country');

        $this->assertSame('country', $select->inputId());
    }

    public function testInputIdUsesExplicitId(): void
    {
        $select = new Select(name: 'country', id: 'country-select');

        $this->assertSame('country-select', $select->inputId());
    }

    public function testResolvedStateReturnsErrorWhenErrorSet(): void
    {
        $select = new Select(name: 'country', error: 'Required');

        $this->assertSame(State::Error, $select->resolvedState());
    }

    public function testResolvedStateReturnsStatePropWhenNoError(): void
    {
        $select = new Select(name: 'country', state: State::Success);

        $this->assertSame(State::Success, $select->resolvedState());
    }

    public function testIterableOptionsConvertedToArray(): void
    {
        $iterator = new ArrayIterator(['a' => 'Option A', 'b' => 'Option B']);
        $select = new Select(name: 'country', options: $iterator);

        $this->assertIsArray($select->options);
        $this->assertSame(['a' => 'Option A', 'b' => 'Option B'], $select->options);
    }

    public function testResolvedPlaceholderUsesExplicitValue(): void
    {
        $select = new Select(name: 'country', placeholder: 'Pick a country');

        $this->assertSame('Pick a country', $select->resolvedPlaceholder());
    }

    public function testResolvedPlaceholderFallsBackToTranslation(): void
    {
        $select = new Select(name: 'country');

        $this->assertSame(__('pajak::ui.form.select.placeholder'), $select->resolvedPlaceholder());
    }

    public function testResolvedSearchPlaceholderUsesExplicitValue(): void
    {
        $select = new Select(name: 'country', searchPlaceholder: 'Find…');

        $this->assertSame('Find…', $select->resolvedSearchPlaceholder());
    }

    public function testResolvedSearchPlaceholderFallsBackToTranslation(): void
    {
        $select = new Select(name: 'country');

        $this->assertSame(__('pajak::ui.form.select.search_placeholder'), $select->resolvedSearchPlaceholder());
    }
}
