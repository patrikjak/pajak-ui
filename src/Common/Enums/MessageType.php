<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums;

enum MessageType: string
{
    case Error = 'error';
    case Success = 'success';
    case Hint = 'hint';
}
