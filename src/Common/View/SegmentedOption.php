<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class SegmentedOption extends Component
{
    public function __construct(
        public readonly ?string $label = null,
        public readonly bool $active = false,
        public readonly bool $disabled = false,
        public readonly ?string $value = null,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.segmented-option');
    }
}
