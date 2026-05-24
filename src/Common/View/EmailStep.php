<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class EmailStep extends Component
{
    public function __construct(
        public readonly int $number,
        public readonly string $title,
        public readonly ?string $description = null,
        public readonly bool $done = false,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.email-step');
    }
}
