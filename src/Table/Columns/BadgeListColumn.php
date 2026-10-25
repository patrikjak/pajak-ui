<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Columns;

use Pajak\Ui\Table\Columns\Concerns\HasColorMap;

class BadgeListColumn extends TextColumn
{
    use HasColorMap;

    protected string $emptyText = '';

    public function empty(string $text): static
    {
        $this->emptyText = $text;

        return $this;
    }

    public function emptyText(): string
    {
        return $this->emptyText;
    }

    /**
     * @param array<string, mixed>|object $row
     * @return list<mixed>
     */
    public function items(array|object $row): array
    {
        $value = $this->resolveValue($row);

        if ($value === '' || $value === null) {
            return [];
        }

        return array_values(is_iterable($value) ? [...$value] : [$value]);
    }

    public function cellView(): string
    {
        return 'pajak::table.cells.badge-list';
    }
}
