<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Modal\ModalSize;

final class Modal extends Component
{
    public function __construct(
        public readonly string $id,
        public readonly ModalSize $size = ModalSize::Md,
        public readonly bool $sheet = false,
        public readonly bool $open = false,
        public readonly bool $dismissible = true,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.modal');
    }
}
