<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\StatCard\StatCardColor;
use Pajak\Ui\Common\Enums\StatCard\StatCardTrend;

final class StatCard extends Component
{
    public function __construct(
        public readonly string $label,
        public readonly string $value,
        public readonly StatCardColor $color = StatCardColor::Primary,
        public readonly ?StatCardTrend $trendDirection = null,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.stat-card');
    }
}
