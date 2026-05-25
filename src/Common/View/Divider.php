<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Divider\DividerOrientation;
use Pajak\Ui\Common\Enums\Divider\DividerStrength;
use Pajak\Ui\Common\Enums\Divider\DividerStyle;

final class Divider extends Component
{
    public function __construct(
        public readonly DividerOrientation $orientation = DividerOrientation::Horizontal,
        public readonly DividerStrength $strength = DividerStrength::Default,
        public readonly DividerStyle $style = DividerStyle::Solid,
        public readonly bool $labeled = false,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.divider');
    }
}
