<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\BadgeColor;
use Pajak\Ui\Common\Enums\Size;

final class Badge extends Component
{
    public function __construct(
        public readonly BadgeColor $color = BadgeColor::Primary,
        public readonly Size $size = Size::Md,
        public readonly bool $outline = false,
        public readonly bool $dot = false,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.badge');
    }
}
