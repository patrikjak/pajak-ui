<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Pajak\Ui\Common\Enums\Size;
use Pajak\Ui\Common\Enums\State;

final class Email extends Input
{
    public function __construct(
        string $name,
        ?string $placeholder = null,
        mixed $value = null,
        State $state = State::Default,
        bool $disabled = false,
        Size $size = Size::Md,
        ?string $id = null,
        ?string $error = null,
        string $autocomplete = 'email',
        ?string $label = null,
    ) {
        parent::__construct(
            $name,
            'email',
            $placeholder,
            $value,
            $state,
            $disabled,
            $size,
            $id,
            $error,
            $autocomplete,
            null,
            $label,
        );
    }

    public function render(): View
    {
        return view('pajak::form.input');
    }
}
