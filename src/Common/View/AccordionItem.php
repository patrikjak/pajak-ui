<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class AccordionItem extends Component
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $subtitle = null,
        public readonly ?string $badge = null,
        public readonly bool $open = false,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.accordion-item');
    }
}
