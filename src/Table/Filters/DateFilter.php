<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Filters;

class DateFilter extends TextFilter
{
    public function editorPartial(): string
    {
        return 'pajak::table.partials.filter-editor-date';
    }
}
