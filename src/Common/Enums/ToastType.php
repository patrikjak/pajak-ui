<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums;

enum ToastType: string
{
    case Success = 'success';
    case Error = 'error';
    case Warning = 'warning';
    case Info = 'info';
}
