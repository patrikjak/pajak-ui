<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Sidebar\SidebarVariant;

final class Sidebar extends Component
{
    public function __construct(public readonly SidebarVariant $variant = SidebarVariant::Standard)
    {
    }

    public function render(): View
    {
        return view('pajak::common.sidebar');
    }
}
