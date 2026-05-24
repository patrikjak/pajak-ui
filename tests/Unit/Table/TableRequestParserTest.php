<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Table;

use Illuminate\Http\Request;
use Pajak\Ui\Table\Enums\SortDirection;
use Pajak\Ui\Table\Enums\TextOperator;
use Pajak\Ui\Table\Services\TableRequestParser;
use Pajak\Ui\Tests\Unit\TestCase;

final class TableRequestParserTest extends TestCase
{
    private TableRequestParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new TableRequestParser();
    }

    public function testParsesEmptyRequest(): void
    {
        $request = Request::create('/table', 'GET');
        $parsed = $this->parser->parse($request);

        $this->assertNull($parsed->search);
        $this->assertNull($parsed->sort);
        $this->assertSame([], $parsed->filters);
        $this->assertSame(1, $parsed->page);
    }

    public function testParsesSearchFromGet(): void
    {
        $request = Request::create('/table', 'GET', ['search' => 'hello']);
        $parsed = $this->parser->parse($request);

        $this->assertSame('hello', $parsed->search);
    }

    public function testParsesEmptySearchAsNull(): void
    {
        $request = Request::create('/table', 'GET', ['search' => '']);
        $parsed = $this->parser->parse($request);

        $this->assertNull($parsed->search);
    }

    public function testParsesSortFromGet(): void
    {
        $request = Request::create('/table', 'GET', [
            'sort' => ['column' => 'name', 'direction' => 'desc'],
        ]);
        $parsed = $this->parser->parse($request);

        $this->assertNotNull($parsed->sort);
        $this->assertSame('name', $parsed->sort->column);
        $this->assertSame(SortDirection::Desc, $parsed->sort->direction);
    }

    public function testInvalidSortDirectionReturnsNull(): void
    {
        $request = Request::create('/table', 'GET', [
            'sort' => ['column' => 'name', 'direction' => 'invalid'],
        ]);
        $parsed = $this->parser->parse($request);

        $this->assertNull($parsed->sort);
    }

    public function testParsesFiltersFromGet(): void
    {
        $request = Request::create('/table', 'GET', [
            'filters' => [
                'name' => [
                    ['value' => 'John', 'operator' => 'contains'],
                ],
            ],
        ]);
        $parsed = $this->parser->parse($request);

        $this->assertCount(1, $parsed->filters);
        $this->assertSame('name', $parsed->filters[0]->key);
        $this->assertSame('John', $parsed->filters[0]->value);
        $this->assertSame(TextOperator::Contains, $parsed->filters[0]->operator);
    }

    public function testParsesMultipleEntriesForSameColumn(): void
    {
        $request = Request::create('/table', 'GET', [
            'filters' => [
                'name' => [
                    ['value' => 'John', 'operator' => 'contains'],
                    ['value' => 'Doe', 'operator' => 'not_contains'],
                ],
            ],
        ]);
        $parsed = $this->parser->parse($request);

        $this->assertCount(2, $parsed->filters);
        $this->assertSame('name', $parsed->filters[0]->key);
        $this->assertSame('John', $parsed->filters[0]->value);
        $this->assertSame(TextOperator::Contains, $parsed->filters[0]->operator);
        $this->assertSame('name', $parsed->filters[1]->key);
        $this->assertSame('Doe', $parsed->filters[1]->value);
        $this->assertSame(TextOperator::NotContains, $parsed->filters[1]->operator);
    }

    public function testParsesPageFromGet(): void
    {
        $request = Request::create('/table', 'GET', ['page' => '3']);
        $parsed = $this->parser->parse($request);

        $this->assertSame(3, $parsed->page);
    }

    public function testParsesJsonBody(): void
    {
        $request = Request::create('/table', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'search' => 'test',
            'page' => 2,
        ]));
        $parsed = $this->parser->parse($request);

        $this->assertSame('test', $parsed->search);
        $this->assertSame(2, $parsed->page);
    }
}
