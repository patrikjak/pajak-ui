<?php

declare(strict_types=1);

namespace Pajak\Ui\Calendar\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class DatePicker extends Component
{
    public function __construct(
        public readonly string $name,
        public readonly bool $range = false,
        public readonly bool $time = false,
        public readonly ?string $value = null,
        public readonly ?string $start = null,
        public readonly ?string $end = null,
        public readonly ?string $placeholder = null,
        public readonly bool $disabled = false,
        public readonly ?string $min = null,
        public readonly ?string $max = null,
        public readonly ?string $id = null,
        public readonly ?string $label = null,
        public readonly ?string $error = null,
    ) {
    }

    public function inputId(): string
    {
        return $this->id ?? $this->name;
    }

    public function resolvedPlaceholder(): string
    {
        if ($this->placeholder !== null) {
            return $this->placeholder;
        }

        return $this->range
            ? __('pajak::ui.calendar.range_placeholder')
            : __('pajak::ui.calendar.placeholder');
    }

    public function render(): View
    {
        return view('pajak::calendar.date-picker');
    }
}
