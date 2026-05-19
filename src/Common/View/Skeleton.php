<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\SkeletonShape;

final class Skeleton extends Component
{
    public function __construct(public readonly SkeletonShape $shape = SkeletonShape::Line)
    {
    }

    public function render(): View
    {
        return view('pajak::common.skeleton');
    }
}
