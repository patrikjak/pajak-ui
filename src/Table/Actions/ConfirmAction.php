<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Actions;

class ConfirmAction extends FormAction
{
    protected ?string $confirmTitle = null;

    protected ?string $confirmMessage = null;

    protected ?string $confirmButton = null;

    public function confirmTitle(string $title): static
    {
        $this->confirmTitle = $title;

        return $this;
    }

    public function confirmMessage(string $message): static
    {
        $this->confirmMessage = $message;

        return $this;
    }

    public function confirmButton(string $label): static
    {
        $this->confirmButton = $label;

        return $this;
    }

    public function resolvedConfirmTitle(): string
    {
        return $this->confirmTitle ?? __('pajak::table.confirm.title');
    }

    public function resolvedConfirmMessage(): string
    {
        return $this->confirmMessage ?? __('pajak::table.confirm.message');
    }

    public function resolvedConfirmButton(): string
    {
        return $this->confirmButton ?? __('pajak::table.confirm.button');
    }
}
