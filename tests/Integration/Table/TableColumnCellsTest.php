<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Table;

use Pajak\Ui\Table\Actions\ConfirmAction;
use Pajak\Ui\Table\Actions\FormAction;
use Pajak\Ui\Table\Columns\AvatarColumn;
use Pajak\Ui\Table\Columns\BadgeColumn;
use Pajak\Ui\Table\Columns\BadgeListColumn;
use Pajak\Ui\Table\Columns\DateColumn;
use Pajak\Ui\Table\Columns\StatusColumn;
use Pajak\Ui\Table\Columns\TextColumn;
use Pajak\Ui\Table\Pagination\ArrayPaginator;
use Pajak\Ui\Table\Table;
use Pajak\Ui\Tests\Integration\TestCase;

final class TableColumnCellsTest extends TestCase
{
    public function testBadgeColumnUsesLabelMapAndComputedValue(): void
    {
        $html = $this->renderRows(
            [
                BadgeColumn::make('is_system')
                    ->value(static fn (array $row): bool => $row['is_system'])
                    ->labels([1 => 'System', 0 => 'Custom'])
                    ->colors([1 => 'blue', 0 => 'gray']),
            ],
            [['id' => 1, 'is_system' => true], ['id' => 2, 'is_system' => false]],
        );

        $this->assertStringContainsString('pajak-table-badge pajak-table-badge--blue', $html);
        $this->assertStringContainsString('pajak-table-badge pajak-table-badge--gray', $html);
        $this->assertStringContainsString('System', $html);
        $this->assertStringContainsString('Custom', $html);
    }

    public function testStatusColumnLabelsAcceptClosure(): void
    {
        $html = $this->renderRows(
            [
                StatusColumn::make('status')
                    ->labels(static fn (string $value): string => ucfirst($value))
                    ->colors(['active' => 'green']),
            ],
            [['id' => 1, 'status' => 'active']],
        );

        $this->assertStringContainsString('pajak-table-status pajak-table-status--green', $html);
        $this->assertStringContainsString('Active', $html);
    }

    public function testBadgeColumnWithoutLabelsPrintsRawValue(): void
    {
        $html = $this->renderRows([BadgeColumn::make('type')], [['id' => 1, 'type' => 'invoice']]);

        $this->assertStringContainsString('invoice', $html);
    }

    public function testBadgeListColumnRendersOneBadgePerItem(): void
    {
        $html = $this->renderRows(
            [
                BadgeListColumn::make('roles')
                    ->labels(['1' => 'Admin', '2' => 'Editor'])
                    ->colors(['1' => 'purple']),
            ],
            [['id' => 1, 'roles' => [1, 2]]],
        );

        $this->assertSame(2, preg_match_all('/class="pajak-table-badge[ "]/', $html));
        $this->assertStringContainsString('pajak-table-badge--purple', $html);
        $this->assertStringContainsString('Admin', $html);
        $this->assertStringContainsString('Editor', $html);
    }

    public function testBadgeListColumnShowsEmptyText(): void
    {
        $html = $this->renderRows(
            [BadgeListColumn::make('roles')->empty('No roles')],
            [['id' => 1, 'roles' => []]],
        );

        $this->assertStringContainsString('pajak-table-badge-list__empty', $html);
        $this->assertStringContainsString('No roles', $html);
    }

    public function testBadgeListColumnValueCallback(): void
    {
        $html = $this->renderRows(
            [BadgeListColumn::make('roles')->value(static fn (array $row): array => ['a', 'b', 'c'])],
            [['id' => 1]],
        );

        $this->assertSame(3, preg_match_all('/class="pajak-table-badge[ "]/', $html));
    }

    public function testAvatarColumnDefaultsToTwoLetterInitials(): void
    {
        $html = $this->renderRows(
            [AvatarColumn::make('name')],
            [['id' => 1, 'name' => 'Alice Smith'], ['id' => 2, 'name' => 'bob']],
        );

        $this->assertStringContainsString('>AS</span>', $html);
        $this->assertStringContainsString('>BO</span>', $html);
    }

    public function testAvatarColumnComputedNameAndInitials(): void
    {
        $html = $this->renderRows(
            [
                AvatarColumn::make('last_name')
                    ->name(static fn (array $row): string => $row['first_name'] . ' ' . $row['last_name'])
                    ->initials(static fn (array $row): string => 'X' . $row['first_name'][0]),
            ],
            [['id' => 1, 'first_name' => 'Jane', 'last_name' => 'Doe']],
        );

        $this->assertStringContainsString('pajak-table-avatar__name">Jane Doe</span>', $html);
        $this->assertStringContainsString('>XJ</span>', $html);
    }

    public function testAvatarColumnInitialsFromKey(): void
    {
        $html = $this->renderRows(
            [AvatarColumn::make('name')->initials('abbr')],
            [['id' => 1, 'name' => 'Alice Smith', 'abbr' => 'ZZ']],
        );

        $this->assertStringContainsString('>ZZ</span>', $html);
    }

    public function testDateColumnConvertsToDisplayTimezone(): void
    {
        $html = $this->renderRows(
            [DateColumn::make('at')->format('Y-m-d H:i')->timezone('Europe/Bratislava')],
            [['id' => 1, 'at' => '2026-01-01 23:30:00+00:00']],
        );

        $this->assertStringContainsString('2026-01-02 00:30', $html);
    }

    public function testDateColumnTimezoneClosure(): void
    {
        $html = $this->renderRows(
            [DateColumn::make('at')->format('H:i')->timezone(static fn (): string => 'Asia/Tokyo')],
            [['id' => 1, 'at' => '2026-01-01 00:00:00+00:00']],
        );

        $this->assertStringContainsString('09:00', $html);
    }

    public function testDateColumnWithoutTimezoneKeepsParsedTime(): void
    {
        $html = $this->renderRows(
            [DateColumn::make('at')->format('H:i')],
            [['id' => 1, 'at' => '2026-01-01 10:15:00+00:00']],
        );

        $this->assertStringContainsString('10:15', $html);
    }

    public function testAsyncFormAndConfirmActionsRenderAsyncAttribute(): void
    {
        $table = Table::make('actions')
            ->columns([TextColumn::make('name')])
            ->actions([
                FormAction::make('resend')->url(static fn (array $row): string => '/r/' . $row['id'])->async(),
                ConfirmAction::make('delete')->url(static fn (array $row): string => '/d/' . $row['id'])->async(),
                FormAction::make('archive')->url(static fn (array $row): string => '/a/' . $row['id']),
            ]);
        $paginator = ArrayPaginator::fromArray([['id' => 1, 'name' => 'A']]);

        $html = (string) $this->blade(
            '<x-pajak-table::table :table="$table" :paginator="$paginator" />',
            ['table' => $table, 'paginator' => $paginator],
        );

        $this->assertSame(2, substr_count($html, 'data-pajak-table-async'));
        $this->assertFalse(FormAction::make('x')->isAsync());
        $this->assertTrue(ConfirmAction::make('x')->async()->isAsync());
    }

    /**
     * @param list<TextColumn> $columns
     * @param list<array<string, mixed>> $rows
     */
    private function renderRows(array $columns, array $rows): string
    {
        $table = Table::make('cells')->columns($columns);
        $paginator = ArrayPaginator::fromArray($rows);

        return (string) $this->blade(
            '<x-pajak-table::table :table="$table" :paginator="$paginator" />',
            ['table' => $table, 'paginator' => $paginator],
        );
    }
}
