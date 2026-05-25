<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Enums;

enum TextOperator: string
{
    case Contains = 'contains';
    case NotContains = 'not_contains';
    case Equals = 'equals';
    case NotEquals = 'not_equals';

    public function label(): string
    {
        return match ($this) {
            self::Contains => __('pajak::table.filter.operator.contains'),
            self::NotContains => __('pajak::table.filter.operator.not_contains'),
            self::Equals => __('pajak::table.filter.operator.equals'),
            self::NotEquals => __('pajak::table.filter.operator.not_equals'),
        };
    }
}
