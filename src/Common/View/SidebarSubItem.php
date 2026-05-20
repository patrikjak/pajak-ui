<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class SidebarSubItem extends Component
{
    public function __construct(
        public readonly string $label,
        public readonly string $href = '#',
        public readonly bool $active = false,
        public readonly ?int $count = null,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.sidebar-sub-item');
    }
}
