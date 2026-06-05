<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Navbar\NavbarVariant;

final class Navbar extends Component
{
    public readonly string $menuDialogId;

    public function __construct(public readonly NavbarVariant $variant = NavbarVariant::Standard)
    {
        $this->menuDialogId = sprintf('pajak-navbar-menu-%s', Str::uuid()->toString());
    }

    public function render(): View
    {
        return view('pajak::common.navbar');
    }
}
