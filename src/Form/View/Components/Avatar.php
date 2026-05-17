<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\State;

final class Avatar extends Component
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $initials = null,
        public readonly ?string $src = null,
        public readonly ?string $label = null,
        public readonly State $state = State::Default,
        public readonly bool $disabled = false,
        public readonly ?string $id = null,
        public readonly ?string $error = null,
        public readonly ?string $accept = null,
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

    public function resolvedAccept(): string
    {
        return $this->accept ?? 'image/*';
    }

    public function hasImage(): bool
    {
        return $this->src !== null;
    }

    public function render(): View
    {
        return view('pajak::form.avatar');
    }
}
