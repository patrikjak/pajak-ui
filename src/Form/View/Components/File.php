<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\State;

final class File extends Component
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $label = null,
        public readonly State $state = State::Default,
        public readonly bool $disabled = false,
        public readonly ?string $id = null,
        public readonly ?string $error = null,
        public readonly ?string $accept = null,
        public readonly ?string $placeholder = null,
    ) {
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
        return view('pajak::form.file');
    }
}
