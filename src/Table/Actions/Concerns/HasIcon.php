<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Actions\Concerns;

trait HasIcon
{
    protected ?string $actionIcon = null;

    protected bool $iconOnlyMode = false;

    public function icon(string $icon): static
    {
        $this->actionIcon = $icon;

        return $this;
    }

    public function iconOnly(): static
    {
        $this->iconOnlyMode = true;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->actionIcon;
    }

    public function isIconOnly(): bool
    {
        return $this->iconOnlyMode && $this->actionIcon !== null;
    }
}
