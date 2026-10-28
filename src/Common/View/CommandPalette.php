<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class CommandPalette extends Component
{
    public function __construct(
        public readonly string $id = 'pajak-command-palette',
        public readonly ?string $placeholder = null,
        public readonly ?string $empty = null,
    ) {
    }

    public function placeholderText(): string
    {
        return $this->placeholder ?? __('pajak::ui.command_palette.placeholder');
    }

    public function emptyText(): string
    {
        return $this->empty ?? __('pajak::ui.command_palette.empty');
    }

    public function render(): View
    {
        return view('pajak::common.command-palette');
    }
}
