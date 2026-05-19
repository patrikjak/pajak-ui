<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\ProgressColor;
use Pajak\Ui\Common\Enums\ProgressSize;

final class Progress extends Component
{
    public function __construct(
        public readonly int $value,
        public readonly int $max = 100,
        public readonly ProgressSize $size = ProgressSize::Md,
        public readonly ProgressColor $color = ProgressColor::Primary,
        public readonly ?string $label = null,
        public readonly bool $showValue = false,
    ) {
    }

    public function percentage(): int
    {
        if ($this->max === 0) {
            return 0;
        }

        return (int) round(min(100, max(0, $this->value / $this->max * 100)));
    }

    public function render(): View
    {
        return view('pajak::common.progress');
    }
}
