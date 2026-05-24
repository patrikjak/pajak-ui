<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Contracts;

use Pajak\Ui\Table\Enums\ActionPosition;

interface TableAction
{
    public function key(): string;

    public function getLabel(): string;

    /**
     * @return array<int, ActionPosition>
     */
    public function position(): array;

    public function isDanger(): bool;

    public function isVisibleFor(mixed $row): bool;
}
