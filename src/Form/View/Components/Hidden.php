<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Hidden extends Component
{
    public function __construct(
        public readonly string $name,
        public readonly mixed $value = null,
    ) {
    }

    public function render(): View
    {
        return view('pajak::form.hidden');
    }
}
