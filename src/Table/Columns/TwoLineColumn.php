<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Columns;

class TwoLineColumn extends TextColumn
{
    protected ?string $secondaryKey = null;

    public function secondary(string $key): static
    {
        $this->secondaryKey = $key;

        return $this;
    }

    public function secondaryKey(): ?string
    {
        return $this->secondaryKey;
    }

    public function cellView(): string
    {
        return 'pajak::table.cells.two-line';
    }
}
