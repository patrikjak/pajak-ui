<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Actions;

use Closure;

class ModalAction extends LinkAction
{
    protected ?Closure $modalIdResolver = null;

    public function modalId(Closure $resolver): static
    {
        $this->modalIdResolver = $resolver;

        return $this;
    }

    public function resolveModalId(mixed $row): ?string
    {
        if ($this->modalIdResolver === null) {
            return null;
        }

        return ($this->modalIdResolver)($row);
    }
}
