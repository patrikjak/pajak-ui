<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class DetailRow extends Component
{
    public function __construct(
        public readonly string $key,
        public readonly bool $mono = false,
        public readonly bool $muted = false,
        public readonly bool $copyable = false,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.detail-row');
    }
}
