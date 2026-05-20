<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Navbar\NavbarVariant;

final class Navbar extends Component
{
    public function __construct(public readonly NavbarVariant $variant = NavbarVariant::Standard)
    {
    }

    public function render(): View
    {
        return view('pajak::common.navbar');
    }
}
