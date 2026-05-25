<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Segmented\SegmentedVariant;
use Pajak\Ui\Common\Enums\Size;

final class Segmented extends Component
{
    public function __construct(
        public readonly SegmentedVariant $variant = SegmentedVariant::Default,
        public readonly Size $size = Size::Md,
        public readonly bool $full = false,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.segmented');
    }
}
