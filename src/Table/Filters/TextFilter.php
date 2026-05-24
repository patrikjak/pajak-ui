<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Filters;

use Pajak\Ui\Table\Contracts\TableFilter;

class TextFilter implements TableFilter
{
    protected string $filterLabel;

    final public function __construct(protected string $filterKey)
    {
        $this->filterLabel = $filterKey;
    }

    public static function make(string $key): static
    {
        return new static($key);
    }

    public function label(string $label): static
    {
        $this->filterLabel = $label;

        return $this;
    }

    public function key(): string
    {
        return $this->filterKey;
    }

    public function getLabel(): string
    {
        return $this->filterLabel;
    }

    public function editorPartial(): string
    {
        return 'pajak::table.partials.filter-editor-text';
    }
}
