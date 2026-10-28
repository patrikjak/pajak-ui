<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class CommandPaletteTrigger extends Component
{
    public function __construct(
        public readonly string $for = 'pajak-command-palette',
        public readonly ?string $label = null,
    ) {
    }

    public function labelText(): string
    {
        return $this->label ?? __('pajak::ui.command_palette.search');
    }

    public function render(): View
    {
        return view('pajak::common.command-palette-trigger');
    }
}
