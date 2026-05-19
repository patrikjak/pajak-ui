<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\BannerType;

final class Banner extends Component
{
    public readonly ?int $progress;

    public function __construct(
        public readonly BannerType $type = BannerType::Info,
        public readonly ?string $title = null,
        public readonly bool $dismissible = false,
        public readonly bool $top = false,
        ?int $progress = null,
    ) {
        $this->progress = $progress !== null ? max(0, min(100, $progress)) : null;
    }

    public function render(): View
    {
        return view('pajak::common.banner');
    }
}
