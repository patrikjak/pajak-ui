<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Dialog\DialogType;

final class Dialog extends Component
{
    public function __construct(
        public readonly string $id,
        public readonly DialogType $type = DialogType::Info,
        public readonly bool $stacked = false,
        public readonly bool $open = false,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.dialog');
    }
}
