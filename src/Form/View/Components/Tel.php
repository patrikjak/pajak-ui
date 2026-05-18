<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Pajak\Ui\Common\Enums\Size;
use Pajak\Ui\Common\Enums\State;
use Pajak\Ui\Form\Enums\TelPattern;

final class Tel extends Input
{
    public function __construct(
        string $name,
        TelPattern|string|null $pattern = null,
        ?string $placeholder = null,
        mixed $value = null,
        State $state = State::Default,
        bool $disabled = false,
        Size $size = Size::Md,
        ?string $id = null,
        ?string $error = null,
        string $autocomplete = 'tel',
        ?string $label = null,
    ) {
        parent::__construct(
            $name,
            'tel',
            $placeholder,
            $value,
            $state,
            $disabled,
            $size,
            $id,
            $error,
            $autocomplete,
            $pattern instanceof TelPattern ? $pattern->value : $pattern,
            $label,
        );
    }

    public function render(): View
    {
        return view('pajak::form.input');
    }
}
