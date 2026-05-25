<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\SpinnerColor;
use Pajak\Ui\Common\Enums\SpinnerSize;
use Pajak\Ui\Common\Enums\SpinnerType;

final class Spinner extends Component
{
    public readonly string $strokeWidth;

    public function __construct(
        public readonly SpinnerType $type = SpinnerType::Arc,
        public readonly SpinnerSize $size = SpinnerSize::Md,
        public readonly SpinnerColor $color = SpinnerColor::Primary,
    ) {
        $this->strokeWidth = match ($size) {
            SpinnerSize::Xs => '3',
            SpinnerSize::Sm => '2.75',
            SpinnerSize::Md => '2.5',
            SpinnerSize::Lg => '2.25',
            SpinnerSize::Xl => '2',
            SpinnerSize::Xxl => '1.75',
        };
    }

    public function render(): View
    {
        return view('pajak::common.spinner');
    }
}
