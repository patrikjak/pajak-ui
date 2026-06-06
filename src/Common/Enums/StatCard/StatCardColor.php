<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums\StatCard;

enum StatCardColor: string
{
    case Primary = 'primary';
    case Success = 'success';
    case Warning = 'warning';
    case Sand = 'sand';
    case Error = 'error';
}
