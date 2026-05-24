<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Dto;

use Pajak\Ui\Table\Enums\TextOperator;

final readonly class TableFilterValue
{
    public function __construct(
        public string $key,
        public mixed $value,
        public ?TextOperator $operator = null,
    ) {
    }
}
