<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Drawer\DrawerSide;

final class Drawer extends Component
{
    public function __construct(
        public readonly string $id,
        public readonly DrawerSide $side = DrawerSide::Right,
        public readonly bool $open = false,
        public readonly bool $dismissible = true,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.drawer');
    }
}
