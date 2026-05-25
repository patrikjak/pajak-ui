<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class EmailCta extends Component
{
    public function __construct(
        public readonly string $href,
        public readonly bool $secondary = false,
        public readonly ?string $color = null,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.email-cta');
    }
}
