<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums;

enum ProgressColor: string
{
    case Primary = 'primary';
    case Success = 'success';
    case Warning = 'warning';
    case Danger = 'danger';
}
