<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\EmptyState\EmptyStateVariant;

final class EmptyState extends Component
{
    public function __construct(public readonly EmptyStateVariant $variant = EmptyStateVariant::Default)
    {
    }

    public function render(): View
    {
        return view('pajak::common.empty-state');
    }
}
