<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\AvatarColor;
use Pajak\Ui\Common\Enums\AvatarSize;
use Pajak\Ui\Common\Enums\AvatarStatus;

final class Avatar extends Component
{
    public function __construct(
        public readonly string $initials,
        public readonly AvatarColor $color = AvatarColor::Blue,
        public readonly AvatarSize $size = AvatarSize::Md,
        public readonly ?AvatarStatus $status = null,
        public readonly bool $ring = false,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.avatar');
    }
}
