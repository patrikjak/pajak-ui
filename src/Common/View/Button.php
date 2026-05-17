<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Size;

final class Button extends Component
{
    public function __construct(
        public readonly string $type = 'button',
        public readonly Size $size = Size::Md,
        public readonly bool $disabled = false,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.button');
    }
}
