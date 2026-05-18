<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Checkbox extends Component
{
    public function __construct(
        public readonly string $name,
        public readonly string|int $value,
        public readonly string $label,
        public readonly bool $checked = false,
        public readonly bool $disabled = false,
        public readonly ?string $description = null,
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
        return view('pajak::form.checkbox');
    }
}
