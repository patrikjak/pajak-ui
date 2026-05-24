<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Columns;

use Pajak\Ui\Table\Contracts\TableColumn;

class TextColumn implements TableColumn
{
    protected bool $sortable = false;

    protected bool $hidden = false;

    protected string $columnLabel;

    public function __construct(protected string $columnKey)
    {
        $this->columnLabel = $columnKey;
    }

    public static function make(string $key): static
    {
        return new static($key);
    }

    public function label(string $label): static
    {
        $this->columnLabel = $label;

        return $this;
    }

    public function sortable(): static
    {
        $this->sortable = true;

        return $this;
    }

    public function hidden(): static
    {
        $this->hidden = true;

        return $this;
    }

    public function key(): string
    {
        return $this->columnKey;
    }

    public function getLabel(): string
    {
        return $this->columnLabel;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    public function isHidden(): bool
    {
        return $this->hidden;
    }

    public function cellView(): string
    {
        return 'pajak::table.cells.text';
    }

    public function thModifier(): string
    {
        return '';
    }
}
