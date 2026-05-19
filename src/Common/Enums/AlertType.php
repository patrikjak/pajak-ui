<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums;

enum AlertType: string
{
    case Info = 'info';
    case Success = 'success';
    case Warning = 'warning';
    case Error = 'error';
}
