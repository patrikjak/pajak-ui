<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

final class RadioCard extends Component
{
    public function __construct(
        public readonly string $name,
        public readonly string|int $value,
        public readonly bool $checked = false,
        public readonly bool $disabled = false,
        public readonly ?string $label = null,
        public readonly ?string $hint = null,
        public readonly ?string $id = null,
        public readonly ?string $error = null,
    ) {
    }

    public function inputId(): string
    {
        return $this->id ?? Str::slug(sprintf('%s_%s', $this->name, $this->value), '_');
    }

    public function render(): View
    {
        return view('pajak::form.radio-card');
    }
}
