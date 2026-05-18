<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\TooltipPlacement;

final class Tooltip extends Component
{
    public function __construct(
        public readonly string $text,
        public readonly TooltipPlacement $placement = TooltipPlacement::Top,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.tooltip');
    }
}
