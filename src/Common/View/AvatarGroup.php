<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\AvatarSize;

final class AvatarGroup extends Component
{
    public function __construct(
        public readonly AvatarSize $size = AvatarSize::Md,
        public readonly int $overflow = 0,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.avatar-group');
    }
}
