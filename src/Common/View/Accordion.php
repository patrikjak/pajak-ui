<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\AccordionMode;
use Pajak\Ui\Common\Enums\AccordionVariant;

final class Accordion extends Component
{
    public function __construct(
        public readonly AccordionVariant $variant = AccordionVariant::Default,
        public readonly AccordionMode $mode = AccordionMode::Single,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.accordion');
    }
}
