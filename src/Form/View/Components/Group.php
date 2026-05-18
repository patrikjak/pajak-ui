<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Group extends Component
{
    public function __construct(public readonly bool $inline = false)
    {
    }

    public function render(): View
    {
        return view('pajak::form.group');
    }
}
