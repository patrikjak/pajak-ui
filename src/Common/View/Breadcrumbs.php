<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Dto\BreadcrumbItem;
use Pajak\Ui\Common\Enums\BreadcrumbSeparator;
use Pajak\Ui\Common\Enums\BreadcrumbVariant;
use Traversable;

final class Breadcrumbs extends Component
{
    /** @var array<int, BreadcrumbItem> */
    public readonly array $items;

    /**
     * @param iterable<int, BreadcrumbItem> $items
     */
    public function __construct(
        iterable $items = [],
        public readonly BreadcrumbVariant $variant = BreadcrumbVariant::Default,
        public readonly BreadcrumbSeparator $separator = BreadcrumbSeparator::Chevron,
    ) {
        $this->items = $items instanceof Traversable ? iterator_to_array($items) : (array) $items;
    }

    public function render(): View
    {
        return view('pajak::common.breadcrumbs');
    }
}
