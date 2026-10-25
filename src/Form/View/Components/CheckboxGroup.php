<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class CheckboxGroup extends Component
{
    public function __construct(
        public readonly ?string $label = null,
        public readonly ?string $selectAll = null,
    ) {
    }

    public function render(): View
    {
        return view('pajak::form.checkbox-group');
    }
}
