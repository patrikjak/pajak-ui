<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\AlertType;

final class EmailAlert extends Component
{
    public function __construct(public readonly AlertType $type = AlertType::Info)
    {
    }

    public function render(): View
    {
        return view('pajak::common.email-alert');
    }
}
