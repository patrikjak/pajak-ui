<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class NavTabBar extends Component
{
    public function render(): View
    {
        return view('pajak::common.nav-tab-bar');
    }
}
