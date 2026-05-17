<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums;

enum State: string
{
    case Default = 'default';
    case Error = 'error';
    case Success = 'success';
}
