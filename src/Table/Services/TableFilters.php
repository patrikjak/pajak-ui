<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Services;

use Illuminate\Support\Collection;
use Pajak\Ui\Table\Dto\TableFilterValue;
use Pajak\Ui\Table\Enums\TextOperator;

final class TableFilters
{
    /**
     * @param Collection<int, mixed> $items
     * @param array<int, TableFilterValue> $filters
     * @return Collection<int, mixed>
     */
    public function apply(Collection $items, array $filters): Collection
    {
        foreach ($filters as $filter) {
            $items = $this->applyFilter($items, $filter);
        }

        return $items->values();
    }

    /**
     * @param Collection<int, mixed> $items
     * @return Collection<int, mixed>
     */
    private function applyFilter(Collection $items, TableFilterValue $filter): Collection
    {
        $value = $filter->value;

        if ($value === null || $value === '' || $value === []) {
            return $items;
        }

        return $items->filter(function (mixed $row) use ($filter, $value): bool {
            $rowValue = is_array($row)
                ? ($row[$filter->key] ?? null)
                : ($row->{$filter->key} ?? null);

            if ($filter->operator !== null && is_string($value)) {
                return $this->matchesTextOperator((string) $rowValue, $value, $filter->operator);
            }

            if (is_array($value) && (isset($value['from']) || isset($value['to']))) {
                return $this->matchesRange($rowValue, $value);
            }

            if (is_array($value)) {
                return in_array($rowValue, $value, strict: true);
            }

            return $rowValue === $value;
        });
    }

    private function matchesTextOperator(string $rowValue, string $filterValue, TextOperator $operator): bool
    {
        $rowLower = mb_strtolower($rowValue);
        $filterLower = mb_strtolower($filterValue);

        return match ($operator) {
            TextOperator::Contains => str_contains($rowLower, $filterLower),
            TextOperator::NotContains => !str_contains($rowLower, $filterLower),
            TextOperator::Equals => $rowLower === $filterLower,
            TextOperator::NotEquals => $rowLower !== $filterLower,
        };
    }

    private function matchesRange(mixed $rowValue, mixed $range): bool
    {
        $from = $range['from'] ?? null;
        $to = $range['to'] ?? null;

        if ($from !== null && $rowValue < $from) {
            return false;
        }

        if ($to !== null && $rowValue > $to) {
            return false;
        }

        return true;
    }
}
