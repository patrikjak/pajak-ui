<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Detail\DetailVariant;

final class Detail extends Component
{
    public function __construct(
        public readonly DetailVariant $variant = DetailVariant::Default,
        public readonly ?string $title = null,
        public readonly ?string $actionLabel = null,
        public readonly ?string $actionHref = null,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.detail');
    }
}
