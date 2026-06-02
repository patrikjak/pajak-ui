<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\SpinnerSize;

final class Async extends Component
{
    public function __construct(
        public readonly string $url,
        public readonly SpinnerSize $size = SpinnerSize::Md,
        public readonly string $label = 'Loading',
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.async');
    }
}
