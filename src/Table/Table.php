<?php

declare(strict_types=1);

namespace Pajak\Ui\Table;

use Pajak\Ui\Table\Contracts\TableAction;
use Pajak\Ui\Table\Contracts\TableColumn;
use Pajak\Ui\Table\Contracts\TableFilter;

final class Table
{
    /**
     * @var array<int, TableColumn>
     */
    private array $columnDefinitions = [];

    /**
     * @var array<int, TableFilter>
     */
    private array $filterDefinitions = [];

    /**
     * @var array<int, TableAction>
     */
    private array $actionDefinitions = [];

    /**
     * @var array<int, TableAction>
     */
    private array $bulkActionDefinitions = [];

    private ?string $dataEndpoint = null;

    private ?string $tableHeading = null;

    private bool $withSearch = false;

    private bool $withSelection = false;

    private bool $withColumnVisibility = false;

    /**
     * @var array<int, int>
     */
    private array $perPageOptions = [];

    public function __construct(private readonly string $tableName)
    {
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    /**
     * @param array<int, TableColumn> $columns
     */
    public function columns(array $columns): self
    {
        $this->columnDefinitions = $columns;

        return $this;
    }

    /**
     * @param array<int, TableFilter> $filters
     */
    public function filters(array $filters): self
    {
        $this->filterDefinitions = $filters;

        return $this;
    }

    /**
     * @param array<int, TableAction> $actions
     */
    public function actions(array $actions): self
    {
        $this->actionDefinitions = $actions;

        return $this;
    }

    /**
     * @param array<int, TableAction> $actions
     */
    public function bulkActions(array $actions): self
    {
        $this->bulkActionDefinitions = $actions;

        return $this;
    }

    public function heading(string $heading): self
    {
        $this->tableHeading = $heading;

        return $this;
    }

    public function dataUrl(string $url): self
    {
        $this->dataEndpoint = $url;

        return $this;
    }

    public function searchable(): self
    {
        $this->withSearch = true;

        return $this;
    }

    public function selectable(): self
    {
        $this->withSelection = true;

        return $this;
    }

    public function columnVisibility(): self
    {
        $this->withColumnVisibility = true;

        return $this;
    }

    /**
     * @param array<int, int> $options
     */
    public function perPageOptions(array $options): self
    {
        $this->perPageOptions = $options;

        return $this;
    }

    public function name(): string
    {
        return $this->tableName;
    }

    public function getHeading(): ?string
    {
        return $this->tableHeading;
    }

    public function url(): ?string
    {
        return $this->dataEndpoint;
    }

    public function isSearchable(): bool
    {
        return $this->withSearch;
    }

    public function hasColumnVisibility(): bool
    {
        return $this->withColumnVisibility;
    }

    public function isSelectable(): bool
    {
        return $this->withSelection || count($this->bulkActionDefinitions) > 0;
    }

    /**
     * @return array<int, TableColumn>
     */
    public function getColumns(): array
    {
        return $this->columnDefinitions;
    }

    /**
     * @return array<int, TableFilter>
     */
    public function getFilters(): array
    {
        return $this->filterDefinitions;
    }

    /**
     * @return array<int, TableAction>
     */
    public function getActions(): array
    {
        return $this->actionDefinitions;
    }

    /**
     * @return array<int, TableAction>
     */
    public function getBulkActions(): array
    {
        return $this->bulkActionDefinitions;
    }

    public function hasActions(): bool
    {
        return count($this->actionDefinitions) > 0;
    }

    public function hasBulkActions(): bool
    {
        return count($this->bulkActionDefinitions) > 0;
    }

    public function hasFilters(): bool
    {
        return count($this->filterDefinitions) > 0;
    }

    /**
     * @return array<int, int>
     */
    public function getPerPageOptions(): array
    {
        return $this->perPageOptions;
    }

    public function hasPerPageOptions(): bool
    {
        return count($this->perPageOptions) > 0;
    }

    public function totalColumnCount(): int
    {
        return count($this->columnDefinitions)
            + ($this->isSelectable() ? 1 : 0)
            + ($this->hasActions() ? 1 : 0);
    }
}
