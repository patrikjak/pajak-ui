<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class EmailHeader extends Component
{
    public function __construct(public readonly ?string $tag = null)
    {
    }

    public function render(): View
    {
        return view('pajak::common.email-header');
    }
}
