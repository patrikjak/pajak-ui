<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Columns;

class AvatarColumn extends TextColumn
{
    protected ?string $subtitleKey = null;

    protected ?string $imageKey = null;

    public function subtitle(string $key): static
    {
        $this->subtitleKey = $key;

        return $this;
    }

    public function image(string $key): static
    {
        $this->imageKey = $key;

        return $this;
    }

    public function subtitleKey(): ?string
    {
        return $this->subtitleKey;
    }

    public function imageKey(): ?string
    {
        return $this->imageKey;
    }

    public function cellView(): string
    {
        return 'pajak::table.cells.avatar';
    }
}
