<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Table;

use Pajak\Ui\Table\Actions\LinkAction;
use Pajak\Ui\Table\Columns\TextColumn;
use Pajak\Ui\Table\Filters\TextFilter;
use Pajak\Ui\Table\Pagination\ArrayPaginator;
use Pajak\Ui\Table\Table;
use Pajak\Ui\Tests\Integration\TestCase;

final class TableSnapshotTest extends TestCase
{
    public function testEmptyTable(): void
    {
        $table = Table::make('users')
            ->columns([
                TextColumn::make('name')->label('Name'),
                TextColumn::make('email')->label('Email'),
            ]);

        $paginator = ArrayPaginator::fromArray([]);

        $html = (string) $this->blade(
            '<x-pajak-table::table :table="$table" :paginator="$paginator" />',
            ['table' => $table, 'paginator' => $paginator],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTableWithRows(): void
    {
        $table = Table::make('users')
            ->columns([
                TextColumn::make('name')->label('Name'),
                TextColumn::make('email')->label('Email'),
            ]);

        $paginator = ArrayPaginator::fromArray([
            ['id' => 1, 'name' => 'Alice Smith', 'email' => 'alice@example.com'],
            ['id' => 2, 'name' => 'Bob Jones', 'email' => 'bob@example.com'],
        ]);

        $html = (string) $this->blade(
            '<x-pajak-table::table :table="$table" :paginator="$paginator" />',
            ['table' => $table, 'paginator' => $paginator],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTableWithHeading(): void
    {
        $table = Table::make('users')
            ->heading('Users')
            ->columns([TextColumn::make('name')->label('Name')]);

        $paginator = ArrayPaginator::fromArray([]);

        $html = (string) $this->blade(
            '<x-pajak-table::table :table="$table" :paginator="$paginator" />',
            ['table' => $table, 'paginator' => $paginator],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSearchableTable(): void
    {
        $table = Table::make('users')
            ->columns([TextColumn::make('name')->label('Name')])
            ->searchable();

        $paginator = ArrayPaginator::fromArray([]);

        $html = (string) $this->blade(
            '<x-pajak-table::table :table="$table" :paginator="$paginator" />',
            ['table' => $table, 'paginator' => $paginator],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTableWithFilters(): void
    {
        $table = Table::make('users')
            ->columns([TextColumn::make('name')->label('Name')])
            ->filters([TextFilter::make('name')->label('Name')]);

        $paginator = ArrayPaginator::fromArray([]);

        $html = (string) $this->blade(
            '<x-pajak-table::table :table="$table" :paginator="$paginator" />',
            ['table' => $table, 'paginator' => $paginator],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTableWithActions(): void
    {
        $table = Table::make('users')
            ->columns([TextColumn::make('name')->label('Name')])
            ->actions([
                LinkAction::make('edit')->label('Edit')->url(fn ($row) => '/users/' . $row['id'] . '/edit'),
            ]);

        $paginator = ArrayPaginator::fromArray([
            ['id' => 1, 'name' => 'Alice Smith'],
        ]);

        $html = (string) $this->blade(
            '<x-pajak-table::table :table="$table" :paginator="$paginator" />',
            ['table' => $table, 'paginator' => $paginator],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTableWithBulkActions(): void
    {
        $table = Table::make('users')
            ->columns([TextColumn::make('name')->label('Name')])
            ->bulkActions([
                LinkAction::make('delete')->label('Delete')->danger(),
            ]);

        $paginator = ArrayPaginator::fromArray([
            ['id' => 1, 'name' => 'Alice Smith'],
        ]);

        $html = (string) $this->blade(
            '<x-pajak-table::table :table="$table" :paginator="$paginator" />',
            ['table' => $table, 'paginator' => $paginator],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSelectableTable(): void
    {
        $table = Table::make('users')
            ->columns([TextColumn::make('name')->label('Name')])
            ->selectable();

        $paginator = ArrayPaginator::fromArray([
            ['id' => 1, 'name' => 'Alice Smith'],
        ]);

        $html = (string) $this->blade(
            '<x-pajak-table::table :table="$table" :paginator="$paginator" />',
            ['table' => $table, 'paginator' => $paginator],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTableWithPagination(): void
    {
        $table = Table::make('users')
            ->columns([TextColumn::make('name')->label('Name')]);

        $rows = array_map(
            fn (int $i) => ['id' => $i, 'name' => sprintf('User %d', $i)],
            range(1, 30),
        );

        $paginator = ArrayPaginator::fromArray($rows, 10, 1);

        $html = (string) $this->blade(
            '<x-pajak-table::table :table="$table" :paginator="$paginator" />',
            ['table' => $table, 'paginator' => $paginator],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTableWithPerPageOptions(): void
    {
        $table = Table::make('users')
            ->columns([TextColumn::make('name')->label('Name')])
            ->perPageOptions([10, 25, 50]);

        $rows = array_map(
            fn (int $i) => ['id' => $i, 'name' => sprintf('User %d', $i)],
            range(1, 30),
        );

        $paginator = ArrayPaginator::fromArray($rows, 10, 1);

        $html = (string) $this->blade(
            '<x-pajak-table::table :table="$table" :paginator="$paginator" />',
            ['table' => $table, 'paginator' => $paginator],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTableWithSortableColumns(): void
    {
        $table = Table::make('users')
            ->columns([
                TextColumn::make('name')->label('Name')->sortable(),
                TextColumn::make('email')->label('Email')->sortable(),
                TextColumn::make('role')->label('Role'),
            ]);

        $paginator = ArrayPaginator::fromArray([
            ['id' => 1, 'name' => 'Alice Smith', 'email' => 'alice@example.com', 'role' => 'Admin'],
        ]);

        $html = (string) $this->blade(
            '<x-pajak-table::table :table="$table" :paginator="$paginator" />',
            ['table' => $table, 'paginator' => $paginator],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
