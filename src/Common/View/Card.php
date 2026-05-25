<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Card\CardVariant;

final class Card extends Component
{
    public function __construct(public readonly CardVariant $variant = CardVariant::Default)
    {
    }

    public function render(): View
    {
        return view('pajak::common.card');
    }
}
