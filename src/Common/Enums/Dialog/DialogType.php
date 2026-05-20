<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums\Dialog;

enum DialogType: string
{
    case Info = 'info';
    case Success = 'success';
    case Warning = 'warning';
    case Danger = 'danger';
}
