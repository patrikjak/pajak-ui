<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Size;

final class Toggle extends Component
{
    public function __construct(
        public readonly string $name,
        public readonly bool $checked = false,
        public readonly bool $disabled = false,
        public readonly Size $size = Size::Md,
        public readonly ?string $label = null,
        public readonly ?string $id = null,
        public readonly ?string $error = null,
    ) {
    }

    public function inputId(): string
    {
        return $this->id ?? $this->name;
    }

    public function render(): View
    {
        return view('pajak::form.toggle');
    }
}
