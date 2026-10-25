<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class TabPanel extends Component
{
    public function __construct(public readonly string $id, public readonly bool $active = false)
    {
    }

    public function render(): View
    {
        return view('pajak::common.tab-panel');
    }
}
