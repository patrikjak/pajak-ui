<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Filters;

class SelectFilter extends TextFilter
{
    /**
     * @var array<string, string>
     */
    protected array $selectOptions = [];

    /**
     * @param array<string, string> $opts
     */
    public function options(array $opts): static
    {
        $this->selectOptions = $opts;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getOptions(): array
    {
        return $this->selectOptions;
    }

    public function editorPartial(): string
    {
        return 'pajak::table.partials.filter-editor-select';
    }
}
