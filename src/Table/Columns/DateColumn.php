<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Columns;

class DateColumn extends TextColumn
{
    protected string $dateFormat = 'd.m.Y';

    public function format(string $format): static
    {
        $this->dateFormat = $format;

        return $this;
    }

    public function dateFormat(): string
    {
        return $this->dateFormat;
    }

    public function cellView(): string
    {
        return 'pajak::table.cells.date';
    }
}
