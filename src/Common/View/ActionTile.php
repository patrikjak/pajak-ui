<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\ActionTile\ActionTileColor;

final class ActionTile extends Component
{
    public function __construct(
        public readonly string $title,
        public readonly string $href = '#',
        public readonly ?string $description = null,
        public readonly ActionTileColor $color = ActionTileColor::Blue,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.action-tile');
    }
}
