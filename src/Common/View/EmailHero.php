<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class EmailHero extends Component
{
    public function __construct(public readonly string $color = 'var(--color-primary-600)')
    {
    }

    public function render(): View
    {
        return view('pajak::common.email-hero');
    }
}
