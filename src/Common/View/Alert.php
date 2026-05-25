<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\AlertType;
use Pajak\Ui\Common\Enums\AlertVariant;

final class Alert extends Component
{
    public function __construct(
        public readonly AlertType $type = AlertType::Info,
        public readonly AlertVariant $variant = AlertVariant::Banner,
        public readonly ?string $title = null,
        public readonly bool $dismissible = false,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.alert');
    }
}
