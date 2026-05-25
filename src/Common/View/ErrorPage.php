<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Lang;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\ErrorPage\ErrorPageCode;

final class ErrorPage extends Component
{
    public readonly ?ErrorPageCode $variant;
    public readonly string $resolvedTitle;
    public readonly string $resolvedDescription;
    public readonly string $colorKey;
    public readonly string $pillLabel;

    public function __construct(
        public readonly int $code = 404,
        public readonly ?string $title = null,
        public readonly ?string $description = null,
    ) {
        $this->variant = ErrorPageCode::tryFrom($code);

        $this->colorKey = $this->variant?->colorKey() ?? 'neutral';
        $this->pillLabel = $this->variant?->pillLabel() ?? sprintf('Error %d', $code);

        $titleKey = sprintf('pajak::ui.error_page.%d.title', $code);
        $descKey = sprintf('pajak::ui.error_page.%d.description', $code);

        $this->resolvedTitle = $title
            ?? (Lang::has($titleKey) ? __($titleKey) : 'Something went wrong');
        $this->resolvedDescription = $description
            ?? (Lang::has($descKey) ? __($descKey) : 'An unexpected error occurred. Please try again later.');
    }

    public function render(): View
    {
        return view('pajak::common.error-page');
    }
}
