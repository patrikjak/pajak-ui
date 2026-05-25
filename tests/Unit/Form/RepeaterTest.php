<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Form;

use Pajak\Ui\Form\View\Components\Repeater;
use Pajak\Ui\Tests\Unit\TestCase;

final class RepeaterTest extends TestCase
{
    public function testResolvedAddLabelUsesCustomLabel(): void
    {
        $repeater = new Repeater(name: 'items', addLabel: 'Add item');

        $this->assertSame('Add item', $repeater->resolvedAddLabel());
    }

    public function testResolvedAddLabelFallsBackToTranslation(): void
    {
        $repeater = new Repeater(name: 'items');

        $this->assertIsString($repeater->resolvedAddLabel());
        $this->assertNotEmpty($repeater->resolvedAddLabel());
    }

    public function testResolvedRemoveLabelUsesCustomLabel(): void
    {
        $repeater = new Repeater(name: 'items', removeLabel: 'Remove item');

        $this->assertSame('Remove item', $repeater->resolvedRemoveLabel());
    }

    public function testResolvedRemoveLabelFallsBackToTranslation(): void
    {
        $repeater = new Repeater(name: 'items');

        $this->assertIsString($repeater->resolvedRemoveLabel());
        $this->assertNotEmpty($repeater->resolvedRemoveLabel());
    }

    public function testReplaceIndexRewritesNameAttribute(): void
    {
        $repeater = new Repeater(name: 'items');
        $html = ' name="title"';

        $result = $repeater->replaceIndex($html, 0);

        $this->assertSame(' name="items[0][title]"', $result);
    }

    public function testReplaceIndexRewritesIdAttribute(): void
    {
        $repeater = new Repeater(name: 'items');
        $html = ' id="title"';

        $result = $repeater->replaceIndex($html, 0);

        $this->assertSame(' id="items_0_title"', $result);
    }

    public function testReplaceIndexRewritesForAttribute(): void
    {
        $repeater = new Repeater(name: 'items');
        $html = ' for="title"';

        $result = $repeater->replaceIndex($html, 0);

        $this->assertSame(' for="items_0_title"', $result);
    }

    public function testReplaceIndexSubstitutesIndexPlaceholder(): void
    {
        $repeater = new Repeater(name: 'items');
        $html = ' id="row-__INDEX__"';

        $result = $repeater->replaceIndex($html, 3);

        $this->assertSame(' id="items_3_row-3"', $result);
    }

    public function testReplaceIndexSubstitutesNamePlaceholder(): void
    {
        $repeater = new Repeater(name: 'products');
        $html = ' id="__NAME__-row"';

        $result = $repeater->replaceIndex($html, 1);

        $this->assertSame(' id="products_1_products-row"', $result);
    }

    public function testReplaceIndexDoesNotDoubleWrapAlreadyBracketedName(): void
    {
        $repeater = new Repeater(name: 'items');
        $html = ' name="items[0][title]"';

        $result = $repeater->replaceIndex($html, 0);

        $this->assertSame(' name="items[0][title]"', $result);
    }

    public function testReplaceIndexWorksWithStringIndex(): void
    {
        $repeater = new Repeater(name: 'items');
        $html = ' name="title"';

        $result = $repeater->replaceIndex($html, 'abc');

        $this->assertSame(' name="items[abc][title]"', $result);
    }

    public function testReplaceIndexReplacesMultipleAttributes(): void
    {
        $repeater = new Repeater(name: 'rows');
        $html = ' name="email" id="email" for="email"';

        $result = $repeater->replaceIndex($html, 2);

        $this->assertSame(' name="rows[2][email]" id="rows_2_email" for="rows_2_email"', $result);
    }
}
