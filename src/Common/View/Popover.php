<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Popover\PopoverPlacement;

final class Popover extends Component
{
    public function __construct(
        public readonly string $id,
        public readonly PopoverPlacement $placement = PopoverPlacement::Bottom,
        public readonly bool $dismissible = true,
        public readonly ?string $title = null,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.popover');
    }
}
