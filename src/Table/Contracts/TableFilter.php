<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Contracts;

interface TableFilter
{
    public function key(): string;

    public function getLabel(): string;

    public function editorPartial(): string;
}
