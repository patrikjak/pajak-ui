<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class EmailBody extends Component
{
    public function render(): View
    {
        return view('pajak::common.email-body');
    }
}
