<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Tabs\TabsVariant;

final class Tabs extends Component
{
    public function __construct(public readonly TabsVariant $variant = TabsVariant::Underline)
    {
    }

    public function render(): View
    {
        return view('pajak::common.tabs');
    }
}
