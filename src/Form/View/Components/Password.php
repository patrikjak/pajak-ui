<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Pajak\Ui\Common\Enums\Size;
use Pajak\Ui\Common\Enums\State;

final class Password extends Input
{
    public function __construct(
        string $name,
        ?string $label = null,
        public readonly bool $confirmation = false,
        public readonly ?string $confirmationLabel = null,
        public readonly ?string $confirmationPlaceholder = null,
        public readonly ?string $confirmationError = null,
        public readonly string $confirmationAutocomplete = 'new-password',
        ?string $placeholder = null,
        mixed $value = null,
        State $state = State::Default,
        bool $disabled = false,
        Size $size = Size::Md,
        ?string $id = null,
        ?string $error = null,
        string $autocomplete = 'new-password',
    ) {
        parent::__construct(
            $name,
            'password',
            $placeholder,
            $value,
            $state,
            $disabled,
            $size,
            $id,
            $error,
            $autocomplete,
            label: $label,
        );
    }

    public function labelText(): string
    {
        return $this->label ?? __('pajak::ui.form.password.label');
    }

    public function confirmationId(): string
    {
        return sprintf('%s_confirmation', $this->name);
    }

    public function confirmationLabelText(): string
    {
        return $this->confirmationLabel ?? __('pajak::ui.form.password.confirmation_label');
    }

    public function confirmationState(): State
    {
        return $this->confirmationError !== null ? State::Error : $this->state;
    }

    public function render(): View
    {
        return view('pajak::form.password');
    }
}
