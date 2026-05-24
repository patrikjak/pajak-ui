<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Services;

use Illuminate\Http\Request;
use Pajak\Ui\Table\Dto\TableFilterValue;
use Pajak\Ui\Table\Dto\TableRequest;
use Pajak\Ui\Table\Dto\TableSortState;
use Pajak\Ui\Table\Enums\SortDirection;
use Pajak\Ui\Table\Enums\TextOperator;

final class TableRequestParser
{
    public function parse(Request $request): TableRequest
    {
        $data = $request->all();

        return new TableRequest(
            $this->parseSearch($data),
            $this->parseSort($data),
            $this->parseFilters($data),
            (int) ($data['page'] ?? 1),
            $this->parsePerPage($data),
            (array) ($data['visible_columns'] ?? []),
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    private function parseSearch(array $data): ?string
    {
        $search = $data['search'] ?? null;

        return is_string($search) && $search !== '' ? $search : null;
    }

    /**
     * @param array<string, mixed> $data
     */
    private function parseSort(array $data): ?TableSortState
    {
        $sort = $data['sort'] ?? null;

        if (!is_array($sort) || !isset($sort['column'], $sort['direction'])) {
            return null;
        }

        $direction = SortDirection::tryFrom((string) $sort['direction']);

        if ($direction === null) {
            return null;
        }

        return new TableSortState((string) $sort['column'], $direction);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<int, TableFilterValue>
     */
    private function parseFilters(array $data): array
    {
        $filters = $data['filters'] ?? [];

        if (!is_array($filters)) {
            return [];
        }

        $result = [];

        foreach ($filters as $key => $entries) {
            if (!is_array($entries)) {
                continue;
            }

            foreach ($entries as $payload) {
                if (!is_array($payload)) {
                    continue;
                }

                $operator = isset($payload['operator'])
                    ? TextOperator::tryFrom((string) $payload['operator'])
                    : null;

                $result[] = new TableFilterValue(
                    (string) $key,
                    $payload['value'] ?? null,
                    $operator,
                );
            }
        }

        return $result;
    }

    /**
     * @param array<string, mixed> $data
     */
    private function parsePerPage(array $data): int
    {
        $perPage = (int) ($data['per_page'] ?? 0);

        return $perPage > 0 ? $perPage : 15;
    }
}
