<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Section extends Component
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $description = null,
    ) {
    }

    public function render(): View
    {
        return view('pajak::form.section');
    }
}
