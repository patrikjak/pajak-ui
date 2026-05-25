<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Contracts;

interface TableColumn
{
    public function key(): string;

    public function getLabel(): string;

    public function isSortable(): bool;

    public function isHidden(): bool;

    public function cellView(): string;

    public function thModifier(): string;
}
