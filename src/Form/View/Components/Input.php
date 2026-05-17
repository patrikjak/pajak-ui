<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Size;
use Pajak\Ui\Common\Enums\State;

class Input extends Component
{
    public function __construct(
        public readonly string $name,
        public readonly string $type = 'text',
        public readonly ?string $placeholder = null,
        public readonly mixed $value = null,
        public readonly State $state = State::Default,
        public readonly bool $disabled = false,
        public readonly Size $size = Size::Md,
        public readonly ?string $id = null,
        public readonly ?string $error = null,
        public readonly ?string $autocomplete = null,
        public readonly ?string $pattern = null,
        public readonly ?string $label = null,
    ) {
    }

    public function labelText(): ?string
    {
        return $this->label;
    }

    public function inputId(): string
    {
        return $this->id ?? $this->name;
    }

    public function resolvedState(): State
    {
        return $this->error !== null ? State::Error : $this->state;
    }

    public function render(): View
    {
        return view('pajak::form.input');
    }
}
