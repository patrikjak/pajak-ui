<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Columns;

use Closure;

class DateColumn extends TextColumn
{
    protected string $dateFormat = 'd.m.Y';

    protected string|Closure|null $displayTimezone = null;

    public function format(string $format): static
    {
        $this->dateFormat = $format;

        return $this;
    }

    public function timezone(string|Closure $timezone): static
    {
        $this->displayTimezone = $timezone;

        return $this;
    }

    public function dateFormat(): string
    {
        return $this->dateFormat;
    }

    public function resolveTimezone(): ?string
    {
        $timezone = $this->displayTimezone instanceof Closure ? ($this->displayTimezone)() : $this->displayTimezone;

        return $timezone === null || $timezone === '' ? null : (string) $timezone;
    }

    public function cellView(): string
    {
        return 'pajak::table.cells.date';
    }
}
