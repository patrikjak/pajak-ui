<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Method;

final class CommandPaletteItem extends Component
{
    /**
     * @param array<int, string> $keywords
     */
    public function __construct(
        public readonly string $label,
        public readonly ?string $href = null,
        public readonly ?string $action = null,
        public readonly Method $method = Method::Post,
        public readonly ?string $event = null,
        public readonly ?string $hint = null,
        public readonly array $keywords = [],
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.command-palette-item');
    }
}
