<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Tab extends Component
{
    public function __construct(
        public readonly string $label,
        public readonly bool $active = false,
        public readonly bool $disabled = false,
        public readonly ?int $count = null,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.tab');
    }
}
