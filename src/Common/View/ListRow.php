<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class ListRow extends Component
{
    public function __construct(public readonly bool $clickable = false)
    {
    }

    public function render(): View
    {
        return view('pajak::common.list-row');
    }
}
